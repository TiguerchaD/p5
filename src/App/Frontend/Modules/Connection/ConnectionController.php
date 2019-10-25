<?php

namespace App\Frontend\Modules\Connection;

use Entity\Connection;
use FormBuilder\ContactFormBuilder;
use FormBuilder\LoginFormBuilder;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use OpenFram\RedirectException;
use function OpenFram\u;

class ConnectionController extends \OpenFram\BackController
{

    public function executeLogin(Request $request)
    {
        if ($request->getMethod() ==='POST') {
            $userName = $request->getParsedBody()['userName'];
            $password = $request->getParsedBody()['password'];

            $connection = new Connection([
                'userName' => $userName,
                'password' => $password
            ]);


            $user = $this->managers->getManagerOf('User')->getByUserName($userName);

            $imagePath = $this->app->getRequest()->getServerParams()['DOCUMENT_ROOT'] . '/images/user/user-' . u($user->getId()) . '.jpg';
            $url = file_exists($imagePath) ? '/images/user/user-' . u($user->getId()) . '.jpg' : '/images/user/user-default.jpg';
            $user->setProfileImage($url);


            if ($user !== null  &&  $user->verifyPassword($password)) {
                $this->app->getCurrentUser()->setAuthenticated(true);
                $this->app->getCurrentUser()->setAttribute('user', $user);
                if ($this->app->getCurrentUser()->hasAttribute('lastUrl')) {
                    $url = $this->app->getCurrentUser()->getAttribute('lastUrl');
                    $redirectionResponse = (new Response())
                        ->withStatus(301, 'redirection')
                        ->withHeader('Location', $url);

                    throw new RedirectException($redirectionResponse ,'Redirection');
                } else {
                    $url = '/admin/';
                    $redirectionResponse = (new Response())
                        ->withStatus(301, 'redirection')
                        ->withHeader('Location', $url);
                    throw new RedirectException($redirectionResponse,'Redirection');

                }
            } else {
                $this->app->getCurrentUser()->setFlash('Le userName ou le mot de passe est incorrect');
            }
        } else {
            $connection = new Connection();
        }

        $formBuilder = new LoginFormBuilder($this->app, $connection);
        $formBuilder->build();
        $form = $formBuilder->getFrom();

        $this->page->addVar('form', $form->createView());
        $this->page->addVar('title', 'Connexion');
        $this->page->addVar('pageType', 'login-page');
    }


    public function executeLogout(Request $request)
    {
        session_destroy();

        $this->page->addVar('title', 'Déconnexion');
        $this->page->addVar('user', $this->app->getCurrentUser()->getAttribute('user'));

        $this->getApp()->getCurrentUser()->setFlash('Déconnexion réussie');
        $url = '/';
        $redirectionResponse = (new Response())
            ->withStatus(301, 'redirection')
            ->withHeader('Location', $url);
        throw new RedirectException($redirectionResponse,'Redirection');
    }
}
