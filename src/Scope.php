<?php
/**
 * Created by PhpStorm.
 * User: matthias
 * Date: 24.09.18
 * Time: 14:30
 */

namespace Vipr\Store;


use Phore\ObjectStore\Type\ObjectStoreObject;
use Phore\VCS\VcsRepository;

class Scope
{

    protected $scopeName;
    protected $config;
    /**
     * @var VcsRepository
     */
    protected $repository;

    public function __construct(string $scopeName, array $config, VcsRepository $repository)
    {
        $this->scopeName = $scopeName;
        $this->config = $config;
        $this->repository = $repository;
    }


    public function hasIndex(string $indexName) : bool
    {
        $ret = phore_pluck(["indexes", $indexName], $this->config, false);
        return ($ret !== false);
    }


    public function getIndex(string $name)
    {
        $indexConfig = phore_pluck(["indexes", $name], $this->config, new \InvalidArgumentException("No index defined for '$name'"));

        $scanPreg = phore_pluck("scan", $indexConfig, "/(?<key>[a-zA-Z0-9\@\.\_\-]+)\.yml$/");
        $prefix = phore_pluck("prefix", $this->config, $this->scopeName);

        $ret = ["_meta_" => [
            "index" => $name,
            "files_scanned" => 0,
            "files_skipped" => [],
            "files" => []
        ]];
        $this->repository->getObjectstore()->walk(function (ObjectStoreObject $object) use (&$ret, $scanPreg, $prefix) {
            //$ret["_meta_"]["files_scanned"]++;
            //$ret["_meta_"]["files"][] = $object->getObjectId();

            if ( ! startsWith($object->getObjectId(), $prefix)) {
                //$ret["_meta_"]["files_skipped"][] = $object->getObjectId() . " Prefix: $prefix";
                return true;
            }
            if ( ! preg_match($scanPreg, $object->getObjectId(), $matches)) {
                return true;
            }
            $data = $object->getYaml();
            $data["_object_id"] = $object->getObjectId();
            if (isset ($matches["key"])) {
                $ret[$matches["key"]] = $data;
            } else {
                $ret[] = $data;
            }

        });
        return $ret;
    }

    public function get(string $name)
    {

    }

    public function all()
    {

    }





}