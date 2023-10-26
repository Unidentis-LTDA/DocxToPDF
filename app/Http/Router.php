<?php

namespace App\Http;

use Closure;
use Exception;
use ReflectionFunction;

class Router
{
    private string $url;
    private string $prefix;
    private array $routes;
    private string $contentType;
    private Request $request;

    public function __construct($url)
    {
        $this->request = new Request($this);
        $this->url = $url;
        $this->setPrefix();
    }

    public function setPrefix()
    {
        list('path' => $prefix) = parse_url($this->url);
        $this->prefix = $prefix ?? '';
    }

    private function addRoute(string $method, string $route, array $params = [])
    {
        foreach ($params as $key => $value) {
            if ($value instanceof Closure) {
                $params['controller'] = $value;
                unset($params[$key]);
            }
        }

        $params['middlewares'] = $params['middlewares'] ?? [];

        $params['variables'] = [];

        $patternVariable = '/{(.*?)}/';

        if (preg_match_all($patternVariable, $route, $matches)) {
            $route = preg_replace($patternVariable, '(.*?)', $route);
            $params['variables'] = $matches[1];
        }

        $patternRoute = '/^' . str_replace('/', '\/', $route) . '$/';
        $this->routes[$patternRoute][$method] = $params;
    }

    public function get($route, $params = [])
    {
        return $this->addRoute('GET', $route, $params);
    }

    public function post($route, $params = [])
    {
        return $this->addRoute('POST', $route, $params);
    }

    private function getUri()
    {
        $uri = $this->request->getUri();
        $aux = strlen($this->prefix) ? explode($this->prefix, $uri) : [$uri];
        $lastIndex = end($aux);
        return strlen($lastIndex) > 1 ? rtrim($lastIndex, '/') : $lastIndex;
    }

    private function getRoute()
    {
        $uri = $this->getUri();
        $httpMethod = $this->request->getHttpMethod();
        foreach ($this->routes as $patternRoute => $methods) {
            if (preg_match($patternRoute, $uri, $metches)) {
                if (isset($methods[$httpMethod])) {
                    unset($metches[0]);

                    $keys = $methods[$httpMethod]['variables'];
                    $methods[$httpMethod]['variables'] = array_combine($keys, $metches);
                    $methods[$httpMethod]['variables']['request'] = $this->request;

                    return $methods[$httpMethod];
                }
                throw new Exception("Método não é permitido", 405);
            }
        }
        throw new Exception("Url não encontrada", 404);
    }

    public function run(): Response
    {
        try {
            $route = $this->getRoute();
            if (!isset($route['controller'])) {
                throw new Exception("A URL não pôde ser processada", 500);
            }
            $args = [];

            $reflection = new ReflectionFunction($route['controller']);
            foreach ($reflection->getParameters() as $parameter) {
                $name = $parameter->getName();
                $args[$name] = $route['variables'][$name] ?? '';
            }

            return (new Middleware\Queue($route['middlewares'], $route['controller'], $args))->next($this->request);
        } catch (Exception $e) {
            return new Response($this->getErrorMessage($e->getMessage()), $e->getCode(), $this->contentType);
        }
    }

    private function getErrorMessage($message)
    {
        switch ($this->contentType) {
            case 'application/json':
                return [
                    'error' => $message
                ];
            default:
                return $message;
        }
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    public function getPrefix(): string
    {
        return $this->prefix;
    }

    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function setRoutes(array $routes): void
    {
        $this->routes = $routes;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setRequest(Request $request): void
    {
        $this->request = $request;
    }

    public function setContentType(string $contentType): void
    {
        $this->contentType = $contentType;
    }

}
