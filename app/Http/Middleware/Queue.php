<?php

namespace App\Http\Middleware;

use App\Http\Request;

class Queue
{
    private static array $map;
    private static array $default;
    private array $middlewares;
    private \Closure $controller;

    private array $controllerArgs;

    public function __construct($middlewares, $controller, $controllerArgs)
    {
        $this->middlewares = array_merge(self::$default, $middlewares);
        $this->controller = $controller;
        $this->controllerArgs = $controllerArgs;
    }

    public static function setMap(array $map): void
    {
        self::$map = $map;
    }

    /**
     * @param array $default
     */
    public static function setDefault(array $default): void
    {
        self::$default = $default;
    }

    /**
     * @throws \Exception
     */
    public function next(Request $request)
    {
        if(empty($this->middlewares))
        {
            return call_user_func_array($this->controller, $this->controllerArgs);
        }

        $middleware = array_shift($this->middlewares);

        if(!isset(self::$map[$middleware])) {
            throw new \Exception("Problemas ao processar o middleware da requisição");
        }
        $queue = $this;

        $next = function ($request) use($queue) {
            return $queue->next($request);
        };

        return (new self::$map[$middleware])->handle($request, $next);
    }

    public function getMiddlewares(): array
    {
        return $this->middlewares;
    }

    public function setMiddlewares(array $middlewares): void
    {
        $this->middlewares = $middlewares;
    }

    public function getController(): \Closure
    {
        return $this->controller;
    }

    public function setController(\Closure $controller): void
    {
        $this->controller = $controller;
    }

    public function getControllerArgs(): array
    {
        return $this->controllerArgs;
    }

    public function setControllerArgs(array $controllerArgs): void
    {
        $this->controllerArgs = $controllerArgs;
    }

}
