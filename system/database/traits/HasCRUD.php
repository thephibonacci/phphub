<?php

namespace System\database\traits;

use System\database\dbconnection\DBConnection;
use System\database\orm\Model;

trait HasCRUD
{
    protected function createMethod($values): Model
    {
        $values = $this->arrayToCastEncodeValue($values);
        $this->arrayToAttributes($values, $this);
        return $this->saveMethod();
    }

    protected function updateMethod($values): Model
    {
        $values = $this->arrayToCastEncodeValue($values);
        $this->arrayToAttributes($values, $this);
        return $this->saveMethod();
    }

    protected function deleteMethod($id = null)
    {
        $object = $this;
        $this->resetQuery();
        if ($id) {
            $object = $this->findMethod($id);
            $this->resetQuery();
        }
        $object->setSql("DELETE FROM " . $object->getTableName());
        $object->setWhere("AND", $this->getAttributeName($this->primaryKey) . " = ? ");
        $object->addValue($object->{$object->primaryKey});
        return $object->executeQuery();
    }

    protected function allMethod()
    {

        $this->setSql("SELECT * FROM " . $this->getTableName());
        $statement = $this->executeQuery();
        $data = $statement->fetchAll();
        if ($data) {
            $this->arrayToObjects($data);
            return $this->collection;
        }
        return [];
    }

    protected function findMethod($id)
    {

        $this->setSql("SELECT * FROM " . $this->getTableName());
        $this->setWhere("AND", $this->getAttributeName($this->primaryKey) . " = ? ");
        $this->addValue($id);
        $statement = $this->executeQuery();
        $data = $statement->fetch();
        $this->setAllowedMethods(['update', 'delete', 'save']);
        if ($data)
            return $this->arrayToAttributes($data);
        return null;
    }

    protected function whereMethod($attribute, $firstValue, $secondValue = null): static
    {

        if ($secondValue === null) {
            $condition = $this->getAttributeName($attribute) . ' = ?';
            $this->addValue($firstValue);
        } else {
            $condition = $this->getAttributeName($attribute) . ' ' . $firstValue . ' ?';
            $this->addValue($secondValue);
        }
        $operator = 'AND';
        $this->setWhere($operator, $condition);
        $this->setAllowedMethods(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy','first','last', 'get', 'paginate']);
        return $this;
    }

    protected function whereOrMethod($attribute, $firstValue, $secondValue = null): static
    {

        if ($secondValue === null) {
            $condition = $this->getAttributeName($attribute) . ' = ?';
            $this->addValue($firstValue);
        } else {
            $condition = $this->getAttributeName($attribute) . ' ' . $firstValue . ' ?';
            $this->addValue($secondValue);
        }
        $operator = 'OR';
        $this->setWhere($operator, $condition);
        $this->setAllowedMethods(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy','first','last', 'get', 'paginate']);
        return $this;
    }


    protected function whereNullMethod($attribute): static
    {

        $condition = $this->getAttributeName($attribute) . ' IS NULL ';
        $operator = 'AND';
        $this->setWhere($operator, $condition);
        $this->setAllowedMethods(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy','first','last', 'get', 'paginate']);
        return $this;
    }

    protected function whereNotNullMethod($attribute): static
    {

        $condition = $this->getAttributeName($attribute) . ' IS NOT NULL ';
        $operator = 'AND';
        $this->setWhere($operator, $condition);
        $this->setAllowedMethods(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy','first','last', 'get', 'paginate']);
        return $this;
    }


    protected function whereInMethod($attribute, $values)
    {

        if (is_array($values)) {
            $valuesArray = [];
            foreach ($values as $value) {
                $this->addValue($value);
                $valuesArray[] = '?';
            }
            $condition = $this->getAttributeName($attribute) . ' IN (' . implode(' , ', $valuesArray) . ')';
            $operator = 'AND';
            $this->setWhere($operator, $condition);
            $this->setAllowedMethods(['where', 'whereOr', 'whereIn', 'whereNull', 'whereNotNull', 'limit', 'orderBy', 'first','last','get', 'paginate']);
            return $this;
        }
    }

    protected function orderByMethod($attribute, $expression): static
    {
        $this->setOrderBy($attribute, $expression);
        $this->setAllowedMethods(['limit','first','last', 'orderBy', 'get', 'paginate']);
        return $this;
    }


    protected function limitMethod($from, $number): static
    {
        $this->setLimit($from, $number);
        $this->setAllowedMethods(['limit','first','last', 'get', 'paginate']);
        return $this;
    }


    protected function getMethod($array = [])
    {
        if ($this->sql == '') {
            if (empty($array)) {
                $fields = $this->getTableName() . '.*';
            } else {
                foreach ($array as $key => $field) {
                    $array[$key] = $this->getAttributeName($field);
                }
                $fields = implode(' , ', $array);
            }
            $this->setSql("SELECT $fields FROM " . $this->getTableName());
        }

        $statement = $this->executeQuery();
        $data = $statement->fetchAll();
        if ($data) {
            $this->arrayToObjects($data);
            return $this->collection;
        }
        return [];
    }

    public function firstMethod($array = [])
    {
        $this->limitMethod(0, 1);
        $result = $this->getMethod($array);
        $this->setAllowedMethods(['update', 'delete', 'save']);
        return count($result) > 0 ? $result[0] : null;
    }

    public function lastMethod($array = [])
    {
        $this->orderByMethod($this->primaryKey, 'desc');
        $this->limitMethod(0, 1);
        $result = $this->getMethod($array);
        $this->setAllowedMethods(['update', 'delete', 'save']);
        return count($result) > 0 ? $result[0] : null;
    }

    protected function paginateMethod($perPage)
    {

        $totalRows = $this->getCount();
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $totalPages = ceil($totalRows / $perPage);
        $currentPage = min($currentPage, $totalPages);
        $currentPage = max($currentPage, 1);
        $currentRow = ($currentPage - 1) * $perPage;
        $this->setLimit($currentRow, $perPage);
        if ($this->sql == '') {
            $this->setSql("SELECT " . $this->getTableName() . ".* FROM " . $this->getTableName());
        }
        $statement = $this->executeQuery();
        $data = $statement->fetchAll();
        if ($data) {
            $this->arrayToObjects($data);
            return $this->collection;
        }
        return [];
    }


    protected function saveMethod(): static
    {

        $fillString = $this->fill();

        if (!isset($this->{$this->primaryKey})) {
            $this->setSql("INSERT INTO " . $this->getTableName() . " SET $fillString, " . $this->getAttributeName($this->createdAt) . "= Now()");
        } else {
            $this->setSql("UPDATE " . $this->getTableName() . " SET $fillString, " . $this->getAttributeName($this->updatedAt) . "=Now()");
            $this->setWhere("AND", $this->getAttributeName($this->primaryKey) . " = ?");
            $this->addValue($this->{$this->primaryKey});
        }
        $this->executeQuery();
        $this->resetQuery();

        if (!isset($this->{$this->primaryKey})) {

            $object = $this->findMethod(DBConnection::lastInsertID());
            $defaultVars = get_class_vars(get_called_class());
            $allVars = get_object_vars($object);
            $differentVars = array_diff(array_keys($allVars), array_keys($defaultVars));
            foreach ($differentVars as $attribute) {
                $this->inCastsAttributes($attribute) ? $this->registerAttribute($this, $attribute, $this->castEncodeValue($attribute, $object->$attribute)) : $this->registerAttribute($this, $attribute, $object->$attribute);
            }
        }
        $this->resetQuery();
        $this->setAllowedMethods(['update', 'delete', 'find']);
        return $this;
    }


    protected function fill(): string
    {

        $fillArray = array();
        foreach ($this->fillable as $attribute) {
            if (isset($this->$attribute)) {
                if ($this->$attribute === '') {
                    $this->$attribute = null;
                }
                $fillArray[] = $this->getAttributeName($attribute) . " = ?";
                $this->inCastsAttributes($attribute) ? $this->addValue($this->castEncodeValue($attribute, $this->$attribute)) : $this->addValue($this->$attribute);
            }
        }
        return implode(', ', $fillArray);
    }


}