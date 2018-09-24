<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 04.09.18
 * Time: 08:55
 */

namespace Vipr\Store\Ctrl;


use Phore\MicroApp\Type\Request;
use Phore\MicroApp\Type\RouteParams;
use Vipr\Store\System;

class VcsStoreCtrl
{


    public function __construct()
    {
    }


    public function on_post (RouteParams $routeParams, Request $request, System $system)
    {

        $scope = $system->scope($routeParams->get("scope"));

    }


    public function on_get (RouteParams $routeParams, Request $request, System $system)
    {
        $scope = $system->scope($routeParams->get("scope"));
        $path = $routeParams->get("path");

        if ($scope->hasIndex($path)) {
            return $scope->getIndex($path);
        }

        return $scope->get($path);
    }


}
