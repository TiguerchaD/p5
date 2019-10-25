<?php


namespace OpenFram;

use GuzzleHttp\Psr7\Response;

class BackController extends ApplicationComponent
{
    protected $module = '';
    protected $action = '';
    protected $view = '';
    protected $page = null;
    protected $managers = null;

    public function __construct(Application $app, $module, $action)
    {
        parent::__construct($app);

        $this->managers = new Managers('PDO', PDOFactory::getMysqlConnection());
        $this->page = new Page($app);

        $this->setModule($module);
        $this->setAction($action);
        $this->setView($action);
    }


    public function getPage()
    {
        return $this->page;
    }

    public function execute()
    {
        $method = 'execute' . ucfirst($this->action);
        if (!is_callable([$this, $method])) {
            throw new \RuntimeException('L\'action'.$this->action.'n\'est pas définie sur ce module');
        }
        $this->$method($this->app->getRequest());
    }

    public function setModule($module)
    {
        if (!is_string($module) || empty($module)) {
            throw new \InvalidArgumentException('Le module doit être une chaine de caractères valide');
        }

        $this->module = $module;
    }

    public function setAction($action)
    {
        if (!is_string($action) || empty($action)) {
            throw new \InvalidArgumentException('L\'action doit être une chaine de caractères valide');
        }

        $this->action = $action;
    }

    public function setView($view)
    {
        if (!is_string($view) || empty($view)) {
            throw new \InvalidArgumentException('La vue doit être une chaine de caractères valide');
        }

        $this->view = $view;
        $this->page->setContentFile(__DIR__.'/../../App/'.$this->app->getName().'/Modules/'.$this->module.'/Views/'.$this->view.'.php');
    }

    /**
     * @return string
     */
    public function getModule(): string
    {
        return $this->module;
    }

    /**
     * @return string
     */
    public function getAction(): string
    {
        return $this->action;
    }

    /**
     * @return Managers|null
     */
    public function getManagers(): ?Managers
    {
        return $this->managers;
    }

    /**
     * @param $userId
     * @return void
     * @throws RedirectException
     */
    protected function requireSelfAccess($userId)
    {

        $currentUser = $this->app->getCurrentUser()->getAttribute('user');

        if ($currentUser->getRole()->getId() != 1 && $currentUser->getId() !== $userId) {
            $redirectionResponse = (new Response())
                ->withStatus(301, 'Redirection')
                ->withHeader('Location', '/admin/');
            throw new RedirectException($redirectionResponse, 'Accés refusé', 'warning');
        }
    }
}
