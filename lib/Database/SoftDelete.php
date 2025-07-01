<?php

namespace NovaFrame\Database;

trait SoftDelete
{
    /**
     * Soft delete a record by setting the `deleted_at` field.
     *
     * Requires an `id` key in the `$where` array to identify the record.
     *
     * @param array $where Conditions to identify the record (must include 'id').
     * @return bool True on successful update, false otherwise.
     *
     * @throws \InvalidArgumentException If 'id' is missing in $where.
     */
    public function delete(array $where): bool
    {
        if (empty($where['id'])) {
            throw new \InvalidArgumentException("SoftDelete requires an id.");
        }

        $where['deleted_at'] = date('Y-m-d H:i:s');

        return $this->update($where);
    }
}
