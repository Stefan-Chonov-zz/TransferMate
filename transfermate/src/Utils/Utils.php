<?php


namespace Transfermate\Utils;

use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class Utils
{
    /**
     * Retrieves XML files paths recursively.
     *
     * @param string $rootDirectory
     * @return array
     */
    public static function getXmlFilesRecursive(string $rootDirectory): array
    {
        $files = array();

        $rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($rootDirectory));

        /** @var SplFileInfo $file */
        foreach ($rii as $file) {
            if ($file->isDir()) {
                continue;
            }

            if (strtolower($file->getExtension()) === 'xml') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    /**
     * Decode XML files data to an array of objects.
     *
     * @param array $files
     * @param string $class
     * @return array
     * @throws \ReflectionException
     */
    public static function decodeXmlFilesDataToObjects(array $files, string $class): array
    {
        $results = array();

        foreach ($files as $file) {
            $results[$file] = self::decodeXmlFileDataToObjects($file, $class);
        }

        return $results;
    }

    /**
     * Decode XML file data to an array of objects.
     *
     * @param string $file
     * @param string $class
     * @return array
     * @throws \ReflectionException
     */
    public static function decodeXmlFileDataToObjects(string $file, string $class): array
    {
        $results = array();

        /** @var \SimpleXMLElement $xmlData */
        $xmlData = simplexml_load_file($file);
        foreach ($xmlData as $xml) {
            $jsonObject = json_encode($xml);
            $results[] = self::jsonDecodeObject($jsonObject, $class);
        }

        return $results;
    }

    /**
     * Decode JSON to an object.
     *
     * @param string $json
     * @param string $class
     * @return object
     * @throws \ReflectionException
     */
    public static function jsonDecodeObject(string $json, string $class): object
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