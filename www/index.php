<?php
/**
 * Created by PhpStorm.
 * User: matthes
 * Date: 27.06.18
 * Time: 17:04
 */

namespace App;

use Phore\MicroApp\App;
use Phore\MicroApp\Handler\JsonExceptionHandler;
use Phore\MicroApp\Handler\JsonResponseHandler;
use Phore\VCS\VcsFactory;
use Phore\VCS\VcsRepository;

use Vipr\Store\Ctrl\VcsStoreCtrl;
use Vipr\Store\System;


require __DIR__ . "/../vendor/autoload.php";

$app = new App();
$app->activateExceptionErrorHandlers();
$app->setOnExceptionHandler(new JsonExceptionHandler());
$app->setResponseHandler(new JsonResponseHandler());

$app->acl->addRule(aclRule()->route("/*")->ALLOW());


$app->add("repo", function() {
    $factory = new VcsFactory();
    $factory->setAuthSshPrivateKey(file_get_contents("/srv/ssh/ssh_key"));
    return $factory->repository(CONF_REPO_TARGET, CONF_REPO_ORIGIN);
});

$app->add("ros", function(VcsRepository $repo) {
    return $repo->getObjectstore();
});

$app->add("system", function (VcsRepository $repo) {
    return new System($repo);
});


$app->router->get("/api/update", function (VcsRepository $repo) {
    $repo->pull();
    return ["msg" => "success - all files up to date"];
});

$app->router->get("/", function () {
    return [
        "system" => "infracamp vipr vcs store",
        "status" => "ready",
        "ssh-public-key" => trim (phore_file("/srv/ssh/ssh_key.pub")->get_contents())
    ];
});

$app->router->delegate("/:scope/::path", VcsStoreCtrl::class);




$app->serve();
