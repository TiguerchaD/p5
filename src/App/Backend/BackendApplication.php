<?php

namespace App\Backend;

use App\Backend\Modules\Connexion\ConnexionController;
use App\Frontend\Modules\Connection\ConnectionController;
use GuzzleHttp\Psr7\Response;
use OpenFram\Page;
use OpenFram\RedirectException;
use function GuzzleHttp\Psr7\stream_for;
use function Http\Response\send;

class BackendApplication extends \OpenFram\Application
{

    public function __construct($request)
    {
        parent::__construct($request);
        $this->name = 'Backend';
    }

    /**
     * @return mixed
     */
    public function run()
    {

        try {
            $controller = $this->getController();

            if (!$this->currentUser->isAuthenticated()) {
                $url = '/connection';
                $redirectionResponse = (new Response())
                    ->withStatus(301, 'Fedirection')
                    ->withHeader('Location', $url);
                throw new RedirectException($redirectionResponse,'Merci de vous authentifier','info');
            }

            if (!$this->currentUser->hasAccess()) {
                $url = '/admin/';
                $redirectionResponse = (new Response())
                    ->withStatus(301, 'redirection')
                    ->withHeader('Location', $url);
                throw new RedirectException($redirectionResponse,'Vous avez pas les permissions nécessaires','danger');
            }

            $controller->execute();
            $controller->getpage()->addVar('module', $controller->getModule());
            $page = $controller->getPage()->getGeneratedPage();
            send($this->response->withBody(stream_for($page)));
        }catch (RedirectException $e){

            if($e->getCode() === 404){
                $page = new Page($this);
                $page->addVar('title', 'Erreur 404');
                $page->addVar('message', $e->getMessage());
                $page->addVar('pageType', 'Erreur 404');
                $page->addVar('module', $controller->getModule());

                $page->setContentFile(__DIR__.'/../../Errors/404.php');

                $e->run($page->getGeneratedPage());
            }else{
                $this->currentUser->setFlash($e->getMessageType(), $e->getMessage());
                $e->run();
            }
        }
    }
}
