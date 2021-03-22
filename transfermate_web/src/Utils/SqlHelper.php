<?php

namespace Transfermate\Web\Utils;

class SqlHelper
{
    /**
     * Prepare aliases for database query.
     *
     * @param array $parameters
     * @param string $operator
     * @param string $prefix
     * @param string $suffix
     * @return array
     */
    public static function prepareAliases(array $parameters, string $operator, string $prefix = '', string $suffix = ''): array
    {
        $results = array();

        try {
            $index = 0;
            foreach ($parameters as $key => $value) {
                $results[$key] = '';
                if (!empty($prefix) && $index < count($parameters) - 1) {
                    $results[$key] .= $prefix;
                }

                if ($key == 'key' || $key == 'id') {
                    $results[$key] .= sprintf("'%s' %s :%s", $key, $operator, $key);
                } else {
                    $results[$key] .= sprintf('%s %s :%s', $key, $operator, $key);
                }

                if (!empty($suffix) && $index < count($parameters) - 1) {
                    $results[$key] .= $suffix;
                }
                $index++;
            }
        } catch (\Exception $ex) {
            // TODO Implement Exception Handler.
        }

        return $results;
    }

    /**
     * Prepare parameters for database query.
     *
     * @param array $parameters
     * @param string $prefix
     * @param string $suffix
     * @return array
     */
    public static function prepareParameters(array $parameters, string $prefix = '', string $suffix = ''): array
    {
        $results = array();

        try {
            foreach ($parameters as $key => $value) {
                $results[':' . $key] = $prefix . $value . $suffix;
            }
        } catch (\Exception $ex) {
            // TODO Implement Exception Handler.
        }

        return $results;
    }
}