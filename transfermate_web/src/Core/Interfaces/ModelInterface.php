<?php


namespace Transfermate\Web\Core\Interfaces;


interface ModelInterface
{
    /**
     * Create entry.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int;

    /**
     * Retrieve entry by id.
     *
     * @param int $id
     * @return array
     */
    public function read(int $id): array;

    /**
     * Update entry.
     *
     * @param array $data
     * @return int
     */
    public function update(array $data): int;

    /**
     * Delete entry by id.
     *
     * @param int $id
     * @return int
     */
    public function delete(int $id): int;

    /**
     * Search entries.
     *
     * @param array $data
     * @param int $offset
     * @param int $limit
     * @return array
     */
    public function search(array $data = array(), int $offset = 0, int $limit = 0): array;
}