<?php

namespace NovaFrame;

use Carbon\CarbonImmutable;
use Nette\Database\Table\ActiveRow;
use Nette\Database\Table\Selection;
use NovaFrame\Database\Database;
use NovaFrame\Database\SoftDelete;

class Model
{
    use SoftDelete;

    protected string $table = '';

    protected array $fields = [];

    protected array $hidden = [];

    protected Database $db;

    protected Selection $selection;

    public function __construct()
    {
        $this->db = new Database(config('database'));
        $this->selection = Database::table($this->table);
    }

    public function save(array $data): bool
    {
        if (!empty($data['id']) && $this->shouldUpdate($data['id'])) {
            $result = $this->update($data);
        } else {
            $data = $this->setCreateAtValueIfNotExist($data);
            $result = $this->selection->insert($data);
        }

        return (bool)$result;
    }

    public function update(array $data): bool
    {
        if (empty($data)) {
            throw new \BadMethodCallException('No data provided');
        }

        if (empty($data['id'])) {
            throw new \BadMethodCallException('No id provided');
        }

        $id = $data['id'];
        unset($data['id']);

        foreach ($data as $key => $value) {
            if (!array_key_exists($key, $this->fields)) {
                unset($data[$key]);
            }
        }

        $data = $this->setUpdateAtValueIfNotExist($data);

        return (bool) $this->selection->where('id', $id)->update($data);
    }

    public function delete(array $where): bool
    {
        $builder = $this->selection;

        foreach ($where as $key => $value) {
            $builder->where($key, $value);
        }

        return (bool) $builder->delete();
    }

    public function find(string|array $select = '*', array $where = [], array $orderBy = [], ?int $limit = null, ?int $offset = null): array
    {
        $builder = $this->selection;

        $builder->select($select);

        if (!empty($where)) {
            foreach ($where as $key => $value) {
                $builder->where($key, $value);
            }
        }

        if (!empty($orderBy)) {
            foreach ($orderBy as $key => $value) {
                $builder->order($key . ' ' . strtoupper($value));
            }
        }

        if ($limit) {
            $builder->limit($limit, $offset);
        }

        $data = $builder->fetchAll();

        return $this->filterData($data);
    }

    private function shouldUpdate($id): bool
    {
        $result = $this->selection->where('id', $id)->fetch();

        return !empty($result);
    }

    private function setCreateAtValueIfNotExist(array $data): array
    {
        if (in_array('created_at', $this->fields) && !isset($data['created_at'])) {
            $data['created_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    private function setUpdateAtValueIfNotExist(array $data): array
    {
        if (in_array('updated_at', $this->fields) && !isset($data['updated_at'])) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        return $data;
    }

    /**
     * Applies hidden field filtering and date casting to the given data.
     *
     * @param array $data Raw query result
     * @return array Processed data with hidden fields removed and date fields casted
     */
    protected function filterData(array|ActiveRow $data): array
    {
        if ($data instanceof ActiveRow) {
            $data = $data->toArray();
        }

        if (empty($data)) {
            return $data;
        }

        $isAssoc = array_keys($data) !== range(0, count($data) - 1);

        if ($isAssoc) {
            $data = $this->removeHiddenFields($data);
            return $this->castDates($data);
        }

        return array_map([$this, 'filterData'], $data);
    }

    private function removeHiddenFields(array $data): array
    {
        foreach ($this->hidden as $field) {
            if (array_key_exists($field, $data)) {
                unset($data[$field]);
            } else {
                foreach ($data as $value) {
                    if ($value instanceof ActiveRow) {
                        $value = $value->toArray();
                    }
                    unset($value[$field]);
                }
            }
        }

        return $data;
    }

    private function castDates(array $data): array
    {
        foreach ($data as $key => $value) {
            if (in_array($key, $this->fields, true) && $value instanceof \DateTimeInterface) {
                $data[$key] = CarbonImmutable::instance($value);
            }
        }

        return $data;
    }

    private function handleMagicFind(string $method, array $arguments)
    {
        if (str_starts_with($method, 'findBy')) {
            $field = lcfirst(substr($method, 6));
            $result = $this->selection->where($field, $arguments[0])->fetch();
            return $this->filterData($result);
        }

        if (str_starts_with($method, 'findAllBy')) {
            $field = lcfirst(substr($method, 9));
            $result = $this->selection->where($field, $arguments[0])->fetchAll();
            return $this->filterData($result);
        }

        throw new \BadMethodCallException("Method {$method} does not exist on " . static::class);
    }

    public function __call(string $method, array $arguments)
    {
        return $this->handleMagicFind($method, $arguments);
    }

    public static function __callStatic(string $method, array $arguments)
    {
        $instance = new static();
        return $instance->handleMagicFind($method, $arguments);
    }
}
