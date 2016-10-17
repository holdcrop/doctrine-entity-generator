<?php

namespace DoctrineEntityGenerator;

class DatabaseManager
{
    /**
     * @var null|\PDO
     */
    private $connection = null;
    /**
     * @var int
     */
    private $fetchType;

    /**
     * @var string
     */
    private $databaseName;

    /**
     * DatabaseManager constructor.
     *
     * @param array $config
     */
    public function __construct(array $config, $fetchType = \PDO::FETCH_OBJ)
    {
        switch ($config['driver']) {
            case 'pdo_mysql':
                $this->connection = $this->connectMySQL($config);
                break;
        }

        $this->fetchType = $fetchType;
        $this->databaseName = $config['dbname'];
    }

    /**
     * @param   array   $config
     * @return \PDO
     */
    private function connectMySQL(array $config)
    {
        $dsn = 'mysql:host=' . $config['host'] . ';';
        $dsn = $dsn . 'dbname=' . $config['dbname'] . ';';
        $dsn = $dsn . 'port=' . $config['port'] . ';';

        $connection = new \PDO($dsn, $config['user'], $config['password']);

        $connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        return $connection;
    }

    /**
     * @return array
     */
    public function getTablesList()
    {
        $sql = 'SHOW tables;';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $this->prepareTableNames($stmt->fetchAll($this->fetchType));
    }

    private function prepareTableNames(array $tablesList)
    {
        $tableNames = [];
        $propertyName = 'Tables_in_' . $this->databaseName;

        foreach ($tablesList as $table) {
            switch ($this->fetchType) {
                case \PDO::FETCH_OBJ:
                    $tableNames[] = $table->{$propertyName};
                    break;
                case \PDO::FETCH_ASSOC:
                    $tableNames[] = $table[$propertyName];
                    break;
            }
        }

        return $tableNames;
    }

    /**
     * @param   string  $table
     * @return  array
     */
    public function getTableDefinition($tableName)
    {
        $dbName = '`' . $this->databaseName . '`';
        $tableName = '`' . $tableName . '`';
        $sql = 'DESCRIBE ' . $dbName . '.' . $tableName . ';';

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll($this->fetchType);
    }
}