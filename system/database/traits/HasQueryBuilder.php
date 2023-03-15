<?php

namespace System\database\traits;

use PDOStatement;
use System\database\dbconnection\DBConnection;

trait HasQueryBuilder
{

    private $sql = '';
    protected $where = [];
    private $orderBy = [];
    private $limit = [];
    private $bindValues = [];

    protected function setSql($query): void
    {
        $this->sql = $query;
    }

    protected function getSql(): string
    {
        return $this->sql;
    }

    protected function resetSql(): void
    {
        $this->sql = '';
    }

    protected function setWhere($operator, $condition): void
    {

        $array = ['operator' => $operator, 'condition' => $condition];
        $this->where[] = $array;

    }

    protected function resetWhere(): void
    {
        $this->where = [];
    }

    protected function setOrderBy($name, $expression): void
    {

        $this->orderBy[] = $this->getAttributeName($name) . ' ' . $expression;

    }

    protected function resetOrderBy(): void
    {
        $this->orderBy = [];
    }

    protected function setLimit($from, $number): void
    {

        $this->limit['from'] = (int)$from;
        $this->limit['number'] = (int)$number;

    }

    protected function resetLimit(): void
    {
        unset($this->limit['from']);
        unset($this->limit['number']);
    }


    protected function addValue($value): void
    {
        $this->bindValues[] = $value;
    }

    protected function removeValues(): void
    {
        $this->bindValues = [];
    }


    protected function resetQuery(): void
    {

        $this->resetSql();
        $this->resetWhere();
        $this->resetOrderBy();
        $this->resetLimit();
        $this->removeValues();

    }

    protected function executeQuery(): bool|PDOStatement
    {

        $query = $this->sql;

        if (!empty($this->where)) {

            $whereString = '';
            foreach ($this->where as $where) {
                $whereString == '' ? $whereString .= $where['condition'] : $whereString .= ' ' . $where['operator'] . ' ' . $where['condition'];
            }
            $query .= ' WHERE ' . $whereString;
        }

        if (!empty($this->orderBy)) {
            $query .= ' ORDER BY ' . implode(', ', $this->orderBy);
        }
        if (!empty($this->limit)) {
            $query .= ' limit ' . $this->limit['from'] . ' , ' . $this->limit['number'] . ' ';
        }
        $query .= ' ;';
        $pdoInstance = DBConnection::getDBConnection();
        $statement = $pdoInstance->prepare($query);
        sizeof($this->bindValues) > 0 ? $statement->execute($this->bindValues) : $statement->execute();
        return $statement;
    }


    protected function getCount()
    {

        $query = "SELECT COUNT(*) FROM " . $this->getTableName();

        if (!empty($this->where)) {

            $whereString = '';
            foreach ($this->where as $where) {
                $whereString == '' ? $whereString .= $where['condition'] : $whereString .= ' ' . $where['operator'] . ' ' . $where['condition'];
            }
            $query .= ' WHERE ' . $whereString;
        }
        $query .= ' ;';
        $pdoInstance = DBConnection::getDBConnection();
        $statement = $pdoInstance->prepare($query);
        sizeof($this->bindValues) > 0 ? $statement->execute($this->bindValues) : $statement->execute();
        return $statement->fetchColumn();
    }

    protected function getTableName(): string
    {

        return ' `' . $this->table . '`';
    }

    protected function getAttributeName($attribute): string
    {

        return ' `' . $this->table . '`.`' . $attribute . '` ';
    }


}