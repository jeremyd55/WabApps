<?php

namespace Framework\Core\Route;


use Framework\Core\Http\{
    Request, RequestInterface, UriInterface
};
use Src\Controller\ErrorController;

class Container
{

    private $path = null;
    private $uri = null;
    private $method = null;
    private $request = [];

    public function __construct()
    {
        $this->setRequest(new Request());
        $this->matchRoute(new Route($this->path), $this->uri);
    }

    private function setRequest(RequestInterface $request): self
    {
        $this->request = $request->getEnvironment();
        $this->path = $request->getEnvironment('ROUTE');
        $this->uri = $request->getUri();
        $this->method = $request->getMethod();
        return $this;
    }

    private function matchRoute(RouteInterface $route, UriInterface $uri)
    {
        $path = $uri->getPath();

        if((! empty($this->request['APP_DEV'])) && ! empty($this->request['APP_DIR'])) {
            $path = '/' . substr($path,strlen($this->request['APP_DIR']));
        }

        foreach($route->getPath() as $val) {

            $routeMatch = (stripos($path, $val) !== false);

            if(! $routeMatch) continue;

            $position = strlen($path);
            $len[] = strncmp($path, $val, $position);
            $name[] = $val;
        }

        if(empty($len)) return $this->error();

        foreach($len as $key => $val) {
            if($val === min($len)) {
                $path = $name[$key];
            }
        }
        $this->controllers($route->getPath($path));
    }

    private function controllers($match)
    {
        $file = '..'. DIRECTORY_SEPARATOR . $match['controller'] . '.php';
        if(file_exists($file)) {
            require $file;
            if(method_exists($match['controller'], $match['function'])) {
                call_user_func(array(new $match['controller'], $match['function']), $match);
            }
        }
    }

    private function error()
    {
        $file = '..'. DIRECTORY_SEPARATOR . 'Src/Controller/ErrorController' . '.php';
        if(file_exists($file)) {
            require $file;
            $controller = new ErrorController();
            if(method_exists($controller, 'error404')) {
                call_user_func(array( $controller, 'error404'));
            }
        }
    }
}