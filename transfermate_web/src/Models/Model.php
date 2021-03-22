<?php

namespace Transfermate\Web\Models;

use Transfermate\Web\Core\Interfaces\ModelInterface;
use Transfermate\Web\Utils\SqlHelper;

class Model implements ModelInterface
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
            echo 'kyr';
            echo $ex->getMessage();
            // TODO Implement Exception Handler
        }

        return $insertedRowID;
    }

    /**
     * Retrieve entry by id.
     *
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function read(int $id): array
    {
        $entry = array();

        try {
            $query = sprintf("SELECT * FROM %s WHERE id = :id", $this->table);
            $stmt = $this->db->prepare($query);
            $stmt->execute(SqlHelper::prepareParameters(array(':id' => $id)));

            $entry = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            // TODO Implement Exception Handler
        }

        return $entry;
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
                join(',', SqlHelper::prepareAliases($modelData, '='))
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
     * @param int $id
     * @return int
     * @throws \Exception
     */
    public function delete(int $id): int
    {
        $deletedRowsCount = 0;

        try {
            $query = sprintf('DELETE FROM %s WHERE `id` = :id', $this->table);
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            $deletedRowsCount = $stmt->rowCount();
        } catch (\Exception $ex) {
            // TODO Implement Exception Handler
        }

        return $deletedRowsCount;
    }

    /**
     * Search entries.
     *
     * @param array $data
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function search(array $data = array(), int $offset = 0, int $limit = 0): array
    {
        $results = array();

        try {
            $where = '';
            $params = array();
            if (count($data) > 0) {
                $aliases = SqlHelper::prepareAliases($data, 'LIKE', '', ' AND ');
                $where = sprintf("WHERE %s", join('', $aliases));

                $params = SqlHelper::prepareParameters($data, '%', '%');
            }

            $limitQueryPart = '';
            if($limit > 0){
                $limitQueryPart = "LIMIT $limit";
            }

            $offsetQueryPart = '';
            if($offset > 0){
                $offsetQueryPart = "OFFSET $offset";
            }

            $query = sprintf(
                "SELECT id, author, name FROM %s %s %s %s",
                $this->table,
                $where,
                $limitQueryPart,
                $offsetQueryPart
            );
            $stmt = $this->db->prepare($query);
            $stmt->execute($params);

            $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Exception $ex) {
            // TODO Implement Exception Handler
        }

        return $results;
    }
}