<?php

/**
 * Middleware class matches routes to their actions
 */

class Router {

    private $routes;

    public function __construct() {
        $routesFile = ROOT.'/routes/web.php';
        $this->routes = include_once($routesFile);
    }

    public function run() {
        $action = $this->getAction();
        if(!count($action)) Router::response_404();
        $l = count($action[0]);
        $dir = '';
        $dirNS = '';
        if($l > 2) {      // if dirs exists check
            for($i = 0; $i < ($l - 2); $i ++) {
                $dn = $action[0][$i];
                $dir .= $dn . '/';
                $dirNS .= ucfirst($dn) . '\\';
            }
        }
        $controllerName = $action[0][$l - 2]; // Actual Controller
        $methodName = $action[0][$l - 1]; // Actual action
        $params = $action[1];
        require_once(ROOT . '/controllers/' . $dir . $controllerName . '.php');
        $fullControllerName = 'Controllers\\' . $dirNS . $controllerName;
	    $controller = new $fullControllerName;  // die($controllerName . '->' . $methodName);
	    if(method_exists($controller, $methodName))
            call_user_func_array([$controller, $methodName], $params);
	    else
	        throw new Exception('There is no such method as "' . $methodName . '" in class ' . $fullControllerName);
	}

    /**
     * Get the controller, method and params according the request
     * @return array two strings Controller-Action 
     */
	private function getAction()
    {
        if (empty($_SERVER['REQUEST_URI'])) return [];
        $request = $_SERVER['REQUEST_URI'];
        $getSplit = explode('?', $request);
        $request = $getSplit[0];
        $request = $request === '/' ? '/' : trim($request, '/');
        if($request === '/')
            return $this->parseAction($request, '/', $this->routes['/']);
        foreach ($this->routes as $route => $action) {
            if($route === '/') continue;
            $reg = preg_replace('~{[a-zA-Z0-9_-]+}~', '[a-zA-Z0-9_-]+', $route);
            $reg = trim($reg, '/');
            $route = trim($route, '/');
            if (preg_match('~^' . $reg . '$~', $request)) {
                return $this->parseAction($request, $route, $action);
            }
        }
        return [];
    }

    private function parseAction($request, $route, $action) {
        $arrRequest  = explode('/', $request);
        $arrRoute  = explode('/', $route);
        $params = [];
        for($i = 0; $i < count($arrRoute); $i++) {
            if($arrRoute[$i] && $arrRoute[$i][0] == '{') {
                $params[] = trim($arrRequest[$i], '{}');
            }
        }
        $controllerAction = explode('/', $action);
        return [$controllerAction, $params];
    }

    // Error Redirect for page 404
    public static function response_404() {
    	http_response_code(404);
		include(ROOT . '/view/404.php');
		exit();
    }
}





