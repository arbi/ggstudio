<?php
namespace ggs\api;
session_start();

class Request {

    public $urlElements;
    public $requestMethod;
    public $parameters;
 
    public function __construct() {
        $this->requestMethod = $_SERVER['REQUEST_METHOD'];
        $this->urlElements = explode('/', $_SERVER['PATH_INFO']);
   		$this->parseIncomingParams();
        $this->format = 'json';
        if(isset($this->parameters['format'])) {
            $this->format = $this->parameters['format'];
        }
        return true;
    }
 
    public function parseIncomingParams() {
        $parameters = array();
 
        if (isset($_SERVER['QUERY_STRING'])) {
            parse_str($_SERVER['QUERY_STRING'], $parameters);
        }
 
        $body = file_get_contents("php://input");

        $contentType = false;
        if(isset($_SERVER['CONTENT_TYPE'])) {
            $contentType = $_SERVER['CONTENT_TYPE'];
        }

        switch($contentType) {
            case "application/json":
                $body_params = json_decode($body);
                if($body_params) {
                    foreach($body_params as $param_name => $param_value) {
                        $parameters[$param_name] = $param_value;
                    }
                }
                $this->format = "json";
                break;
            case "application/x-www-form-urlencoded; charset=UTF-8":
                parse_str($body, $postvars);
                foreach($postvars as $field => $value) {
                    $parameters[$field] = $value;
 
                }
                $this->format = "html";
                break;
        }

        $this->parameters = $parameters;

        $class  = null;
        $method = null;
        $id     = null;

        if (count($this->urlElements) == 5) {
        	$id     = array_pop($this->urlElements);
        	$method = array_pop($this->urlElements);
        	$class  = array_pop($this->urlElements);

        } else if (count($this->urlElements) == 4) {
        	$param    = array_pop($this->urlElements);
        	preg_match('/^[1-9][0-9]+$/', $param) ? 
        		$id     = $param : 
        		$method = $param;

        	$class = array_pop($this->urlElements);

        } else if (count($this->urlElements) == 3) {
        	$class = array_pop($this->urlElements);
        }

        if (!empty($class)) {
        	$className = strtolower($class);
        	$className = ucfirst($className);

        	require_once('Controller/'. $className . '.php');
        	$namespace = '\ggs\api\Controller\\' . $className;
        	$callClass = new $namespace;

        	if (empty($method)) {
	        	switch ($this->requestMethod) {
	        		case 'GET':
			        	$callClass->get($this->parameters, $id);
	        			break;
	        		case 'POST':
			        	$result = $callClass->create($this->parameters);
			        	echo $result;

	        			break;
	        		case 'PUT':
			        	$callClass->update($this->parameters, $id);
	        			break;
	        		case 'DELETE':
			        	$callClass->delete($id);
	        			break;
	        	}
	        } else {
	        	$result = $callClass->$method($this->parameters, $id);
	        	echo $result;
	        }
        }
    }
}

require 'init_autoloader.php';
$request = new Request();
