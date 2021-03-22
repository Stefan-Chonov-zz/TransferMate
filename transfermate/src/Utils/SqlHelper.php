<?php

namespace Transfermate\Utils;

class SqlHelper
{
    /**
     * Prepare parameters for database query.
     *
     * @param array $parameters
     * @return array
     */
    public static function prepareParameters(array $parameters): array
    {
        $results = array();

        try {
            foreach ($parameters as $key => $value) {
                $results[':' . $key] = $value;
            }
        } catch (\Exception $ex) {
            // TODO Implement Exception Handler.
        }

        return $results;
    }

    /**
     * Prepare aliases for database query.
     *
     * @param array $parameters
     * @param string $prefix
     * @param string $suffix
     * @return array
     */
    public static function prepareAliases(array $parameters, string $prefix = '', string $suffix = ''): array
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
                    $results[$key] .= sprintf("'%s' = :%s", $key, $key);
                } else {
                    $results[$key] .= sprintf('%s = :%s', $key, $key);
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
}