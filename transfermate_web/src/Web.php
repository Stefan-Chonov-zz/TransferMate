<?php

namespace Transfermate\Web;


use Transfermate\Web\Core\DB;
use Transfermate\Web\Models\Model;

class Web
{
    /**
     * @var string|mixed
     */
    protected string $controller = 'Home';

    /**
     * @var string
     */
    protected string $method = 'index';

    /**
     * @var array|false|string[]
     */
    protected array $params = array();

    /**
     * Web constructor.
     */
    public function __construct()
    {
        $uri = $this->parseUrl();

        if (file_exists(__DIR__ . '/Controllers/' . ucfirst($uri[0]) . '.php')) {
            $this->controller = ucfirst($uri[0]);
            unset($uri[0]);
        } elseif (isset($uri[0]) && !empty($uri[0])) {
            $redirectUrl = sprintf(
                "%s://%s:%d/error/index",
                strtolower($_ENV['HOST_PROTOCOL']),
                $_ENV['HOST'],
                $_ENV['HOST_PORT']
            );
            header("Location: $redirectUrl");
            die();
        }

        require_once __DIR__ . '/Controllers/' . $this->controller . '.php';

        $this->controller = sprintf('Transfermate\Web\Controllers\%s', $this->controller);

        if (isset($uri[1])) {
            if (method_exists($this->controller, $uri[1])) {
                $this->method = $uri[1];
                unset($uri[1]);
            } else {
                $redirectUrl = sprintf(
                    "%s://%s:%d/error/index",
                    strtolower($_ENV['HOST_PROTOCOL']),
                    $_ENV['HOST'],
                    $_ENV['HOST_PORT']
                );
                header("Location: $redirectUrl");
                die();
            }
        }

        $this->params = $uri ? array_values($uri) : array();

        $obj = new $this->controller(new Model($_ENV['DB_PGSQL_BOOKS_TABLE'], DB::getInstance()));
        $func = $this->method;
        $obj->$func();
    }

    /**
     * Parse URL.
     *
     * @return array
     */
    public function parseUrl(): array
    {
        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $uri = explode('/', $uri);

        unset($uri[0]);

        $params = array_values($uri);
        if ($params && is_array($params)) {
            return array_values($uri);
        }

        return array();
    }
}