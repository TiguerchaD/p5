<?php

namespace OpenFram;

use OpenFram\Session\Session;

session_start();

class CurrentUser extends ApplicationComponent
{

    public function hasFlash()
    {
        return  isset($_SESSION['flash']);
    }

    public function getFlash()
    {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);

        return $flash;
    }

    public function setFlash($type, $message)
    {
        $_SESSION['flash'] = ['type'=>$type,'message'=> $message];
    }


    public function getAttribute($attr)
    {
        return $_SESSION[$attr];
    }

    public function hasAttribute($attr)
    {
        return isset($_SESSION[$attr]);
    }

    public function setAttribute($attr, $value)
    {
        $_SESSION [$attr] = $value;
    }

    public function isAuthenticated()
    {
        return isset($_SESSION['auth']) && $_SESSION['auth'] === true;
    }

    public function setAuthenticated($authenticated = true)
    {
        if (!is_bool($authenticated)) {
            throw new \InvalidArgumentException('La valeur spécaifiée à la méthode CurrentUser::setAuthenticated() doitêtre un boolean ');
        }
        $_SESSION['auth'] = $authenticated;
    }

    public function hasAccess()
    {
        $controller = $this->app->getController();
        $controller->getPage()->addVAr('user', $this);
        $permissions = $this->getAttribute('user')->getRole()->getPermissions();
        $couple = [];
        foreach ($permissions as $permission) {
            $couple [] = [$permission->getModule(), $permission->getAction()];
        }
        $control = [$controller->getModule(), $controller->getAction()];
        if (!in_array($control, $couple)) {
            return false;
        }
        return true;
    }

    public function hasAccessTo($module, $action)
    {
        $permissions = $this->getAttribute('user')->getRole()->getPermissions();
        $couple = [];
        foreach ($permissions as $permission) {
            $couple [] = [$permission->getModule(), $permission->getAction()];
        }
        $control = [$module, $action];
        if (!in_array($control, $couple)) {
            return false;
        }
        return true;
    }

}
