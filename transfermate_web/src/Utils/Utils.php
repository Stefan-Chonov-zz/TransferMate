<?php


namespace Transfermate\Web\Utils;


use ReflectionClass;
use Transfermate\Web\Core\DB;

class Utils
{
    /**
     * Decode JSON to an object.
     *
     * @param string $json
     * @param string $class
     * @return object
     * @throws \ReflectionException
     */
    public static function decodeJsonToObject(string $json, string $class): object
    {
        $reflection = new \ReflectionClass($class);
        $instance = $reflection->newInstanceWithoutConstructor();
        $json = json_decode($json, true);
        $properties = $reflection->getProperties();
        foreach ($properties as $key => $property) {
            $property->setAccessible(true);
            if (isset($json[$property->getName()])) {
                $property->setValue($instance, $json[$property->getName()]);
            }
        }

        return $instance;
    }

    /**
     * Get short name of class.
     *
     * @param object $class
     * @return string
     * @throws \Exception
     */
    public static function getClassShortName(object $class): string
    {
        if (class_exists(get_class($class), true)) {
            $reflect = new ReflectionClass($class);

            return $reflect->getShortName();
        } else {
            throw new \Exception('Class does not exists!');
        }
    }

    /**
     * Creates 'Books' table in Database if not exists.
     *
     * @param \PDO $db
     */
    public static function createBooksDbTable(\PDO $db): void
    {
        $query = 'CREATE TABLE IF NOT EXISTS books
                (
                    id          serial       not null
                        constraint books_pk
                            primary key,
                    author      varchar(255) not null,
                    name        varchar(100) not null,
                    created_at  integer      not null,
                    modified_at integer      not null
                );';

        $db->exec($query);
    }
}