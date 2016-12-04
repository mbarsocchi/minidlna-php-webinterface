<?php
/**
 * Description of QueryResult
 *
 * @author mbarsocchi
 */
class QueryResult {
    
    public $type;
    public $name;
    public $path;
    public $url;
    
    function __construct($name) {
        $this->name = $name;
    }

    function getUrl() {
        return $this->url;
    }

    function setUrl($url) {
        $this->url = $url;
    }

    function getType() {
        return $this->type;
    }

    function getName() {
        return $this->name;
    }

    function getPath() {
        return $this->path;
    }

    function setType($type) {
        $this->type = $type;
    }

    function setName($name) {
        $this->name = $name;
    }

    function setPath($path) {
        $this->path = $path;
    }


}
