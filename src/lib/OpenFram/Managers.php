<?php


namespace OpenFram;

class Managers
{

    protected $api;
    protected $dao;
    protected $managers = [];

    /**
     * Managers constructor.
     *
     * @param $api
     * @param $dao
     */
    public function __construct($api, $dao)
    {
        $this->api = $api;
        $this->dao = $dao;
    }

    public function getManagerOf($module)
    {
        if (!is_string($module) || empty($module)) {
            throw new \InvalidArgumentException('Le module spécifié est invalide');
        }
        if (!isset($this->managers[$module])) {
            $manager = '\\Model\\'.$module.'Manager'.$this->api;
            $this->managers[$module] = new $manager($this->dao);
        }
        return $this->managers[$module];
    }
}
