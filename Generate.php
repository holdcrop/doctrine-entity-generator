<?php

namespace DoctrineEntityGenerator;

class Generate
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var DatabaseManager|null
     */
    private $databaseManager = null;

    /**
     * @var Database|null
     */
    private $database = null;

    /**
     * Generate constructor.
     *
     * @param string $baseDir
     * @param string $entityDir
     */
    public function __construct()
    {
        $this->config = require('config/config.php');

        $this->databaseManager = new DatabaseManager($this->config['connection']);

        $this->loadDatabase();

        $this->loadTables();
    }

    /**
     * Generate the Entity files
     */
    public function generate()
    {
        foreach ($this->database->getTables() as $table) {
            $entity = new Entity($table, $this->config['entity']['namespace'], $this->config['repository']['namespace']);
            $this->writeEntity($entity);
            $this->writeRepository(
                new Repository(
                    $entity->getEntityName(),
                    $this->config['repository']['namespace'],
                    $this->config['repository']['use'],
                    $this->config['repository']['extend']
                )
            );
        }
    }

    /**
     * @param Entity $entity
     */
    private function writeEntity(Entity $entity)
    {
        $fileName = $this->config['entity']['dir'] . DIRECTORY_SEPARATOR . $entity->getEntityName() . '.php';
        file_put_contents($fileName, $entity->getEntity());
    }

    /**
     * @param Repository $repository
     */
    private function writeRepository(Repository $repository)
    {
        $fileName = $this->config['repository']['dir'] . DIRECTORY_SEPARATOR . $repository->getRepositoryName() . '.php';
        file_put_contents($fileName, $repository->getRepository());
    }

    /**
     * Load the database
     */
    private function loadDatabase()
    {
        $this->database = new Database($this->databaseManager->getTablesList());
    }

    /**
     * Load the tables
     */
    private function loadTables()
    {
        foreach ($this->database->getTableNames() as $tableName) {
            $this->database->addTable(
                new Table(
                    $tableName,
                    $this->databaseManager->getTableDefinition($tableName)
                )
            );
        }
    }
}
