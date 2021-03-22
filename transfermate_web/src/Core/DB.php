<?php


namespace Transfermate\Web\Core;


class DB
{
    /**
     * @var ?\PDO
     */
    protected static ?\PDO $instance = null;

    /**
     * DB constructor.
     */
    protected function __construct() { }

    /**
     * Get Instance.
     *
     * @return ?\PDO
     */
    public static function getInstance(): ?\PDO
    {
        if (is_null(self::$instance)) {
            $dsn = sprintf(
                'pgsql:host=%s;port=%d;dbname=%s;user=%s;password=%s',
                $_ENV['DB_PGSQL_HOST'],
                $_ENV['DB_PGSQL_PORT'],
                $_ENV['DB_PGSQL_DATABASE'],
                $_ENV['DB_PGSQL_USER'],
                $_ENV['DB_PGSQL_PASS']
            );

            self::$instance = new \PDO($dsn);
            self::$instance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        }

        return self::$instance;
    }

    /**
     * Disable the cloning of this class.
     *
     * @return void
     * @throws \Exception
     */
    private function __clone() { }

    /**
     * Disable the wakeup of this class.
     *
     * @return void
     * @throws \Exception
     */
    final public function __wakeup()
    {
        throw new \Exception('Feature disabled.');
    }
}