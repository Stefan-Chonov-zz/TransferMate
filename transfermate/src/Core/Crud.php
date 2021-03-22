<?php

namespace Transfermate\Core;

use Transfermate\Utils\SqlHelper;

class Crud implements CrudInterface
{
    /**
     * @var string $table
     */
    private string $table;

    /**
     * @var \PDO $db
     */
    private \PDO $db;

    /**
     * Model constructor.
     *
     * @param string $table
     * @param \PDO $db
     */
    public function __construct(string $table, \PDO $db)
    {
        $this->table = $table;
        $this->db = $db;
    }

    /**
     * Create entry.
     *
     * @param array $data
     * @return int
     * @throws \Exception
     */
    public function create(array $data): int
    {
        $insertedRowID = 0;

        try {
            $parametersAliases = SqlHelper::prepareParameters($data);
            $query = sprintf(
                "INSERT INTO %s (%s) VALUES (%s)",
                $this->table,
                join(",", array_keys($data)),
                join(',', array_keys($parametersAliases))
            );

            $stmt = $this->db->prepare($query);

            if ($stmt->execute($parametersAliases)) {
                $insertedRowID = $this->db->lastInsertId();
            }
        } catch (\Exception $ex) {
            // TODO Implement Exception Handler
        }

        return $insertedRowID;
    }

    /**
     * Retrieves entry/entries.
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function read(array $data = array()): array
    {
        $entries = array();

        try {
            $where = '';
            if (count($data) > 0) {
                $params = SqlHelper::prepareAliases($data, '', ' AND ');
                $where = sprintf("WHERE %s", join('', $params));
            }

            $query = sprintf("SELECT * FROM %s %s", $this->table, $where);
            $stmt = $this->db->prepare($query);
            $stmt->execute(SqlHelper::prepareParameters($data));

            $entries = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            // TODO Implement Exception Handler
        }

        return $entries;
    }

    /**
     * Update entry.
     *
     * @param array $data
     * @return int
     * @throws \Exception
     */
    public function update(array $data): int
    {
        $result = 0;

        try {
            $modelData = $data;
            unset($modelData['id']);
            $query = sprintf(
                "UPDATE %s SET %s WHERE id = :id",
                $this->table,
                join(',', SqlHelper::prepareAliases($modelData))
            );

            $stmt = $this->db->prepare($query);
            $data = SqlHelper::prepareParameters($data);
            $stmt->execute($data);

            $result = $stmt->rowCount();
        } catch (\Exception $ex) {
            // TODO Implement Exception Handler
        }

        return $result;
    }

    /**
     * Delete entry.
     *
     * @param array $data
     * @return int
     * @throws \Exception
     */
    public function delete(array $data): int
    {
        $deletedRowsCount = 0;

        try {
            $query = sprintf('DELETE FROM %s WHERE `id` = :id', $this->table);
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $data['id']);
            $stmt->execute();

            $deletedRowsCount = $stmt->rowCount();
        } catch (\Exception $ex) {
            // TODO Implement Exception Handler
        }

        return $deletedRowsCount;
    }
}