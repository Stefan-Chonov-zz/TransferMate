<?php


namespace Transfermate\Core;

interface CrudInterface
{
    /**
     * Create entry.
     *
     * @param array $data
     * @return int
     */
    public function create(array $data): int;

    /**
     * Retrieves entry/entries.
     *
     * @param array $data
     * @return array
     */
    public function read(array $data = array()): array;

    /**
     * Update entry.
     *
     * @param array $data
     * @return int
     */
    public function update(array $data): int;

    /**
     * Delete entry.
     *
     * @param array $data
     * @return int
     */
    public function delete(array $data): int;
}