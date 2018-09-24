<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 24.09.18
 * Time: 14:24
 */

namespace  Vipr\Store;


use Phore\ObjectStore\ObjectStore;
use Phore\VCS\VcsRepository;
use Vipr\Store\Scope;

class System
{

    private $repository;

    private $config;

    public function __construct(VcsRepository $repository)
    {
        $this->repository = $repository;
        $this->config = $repository->object("vipr-store-config.yml")->getYaml();
    }



    public function scope(string $scope) : Scope
    {
        $config = phore_pluck(["scopes", $scope], $this->config, new \InvalidArgumentException("Scope '$scope' undefined in config file"));
        return new Scope($scope, $config, $this->repository);
    }



}