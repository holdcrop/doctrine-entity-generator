<?php

namespace DoctrineEntityGenerator;

class Database
{
    /**
     * @var array
     */
    private $tableNames = [];

    /**
     * @var array
     */
    private $tables = [];

    /**
     * Database constructor.
     */
    public function __construct(array $tableNames)
    {
        $this->tableNames = $tableNames;
    }

    /**
     * @param Table $table
     */
    public function addTable(Table $table)
    {
        $this->tables[$table->getName()] = $table;
    }

    /**
     * @return array
     */
    public function getTableNames()
    {
        return $this->tableNames;
    }

    /**
     * @return array
     */
    public function getTables()
    {
        return $this->tables;
    }
}