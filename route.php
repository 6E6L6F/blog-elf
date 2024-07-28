<?php

class Request {
    public function getGet($key) {
        return $_GET[$key];
    }

    public function getPost($key) {
        return $_POST[$key];
    }
}

class Router {
    protected $routes = [];
    private $session;

    public function __construct(Session $session) {
        $this->session = $session;
    }
    public function addRoute(string $method, string $url, bool $permission, Closure $target) {
        $this->routes[$method][$url] = [
            'target' => $target,
            'permission' => $permission,
        ];
    }

    public function matchRoute() {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = $_SERVER['REQUEST_URI'];
        if (isset($this->routes[$method])) {
            foreach ($this->routes[$method] as $routeUrl => $route) {
                $pattern = preg_replace('/\/:([^\/]+)/', '/(?P<$1>[^/]+)', $routeUrl);
                if (preg_match('#^' . $pattern . '$#', $url, $matches)) {
                    $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                    $request = new Request();
                    if ($route['permission'] && !$this->session->isLoggedIn()) {
                        http_response_code(403);
                        exit;
                    }
                    call_user_func_array($route['target'], [$request, ...$params]);
                    return;
                }
            }
        }
    }
}