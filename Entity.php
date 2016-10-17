<?php

namespace DoctrineEntityGenerator;

use DoctrineEntityGenerator\Types\Mappings;
use DoctrineEntityGenerator\Types\SQL;

class Entity
{
    /**
     * @var string
     */
    private $entityNamespace;

    /**
     * @var string
     */
    private $repositoryNamespace;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var string
     */
    private $annotation;

    /**
     * @var array
     */
    private $properties = [];

    /**
     * @var array
     */
    private $getters = [];

    /**
     * @var array
     */
    private $setters = [];

    /**
     * Entity constructor.
     * @param   Table   $table
     * @param   string  $namespace
     */
    public function __construct(Table $table, $entityNamespace, $repositoryNamespace)
    {
        $this->entityNamespace = $entityNamespace;
        $this->repositoryNamespace = $repositoryNamespace;
        $this->entityName = $this->snakeToCamel($table->getName(), true);
        $this->buildAnnotation($table->getName());
        $this->buildEntityProperties($table->getPrimaryColumns(),  $table->getColumns(), $table->getColumnSQLTypes(), $table->getColumnExtras());
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        $entity = "<?php\n\nnamespace " . $this->entityNamespace . ";\n\n";
        $entity = $entity . $this->annotation . "\n";
        $entity = $entity . "class " . $this->entityName . "\n";
        $entity = $entity . "{\n";

        foreach ($this->properties as $key => $property) {
            $entity = $entity . $property . "\n\n";
        }

        foreach ($this->getters as $key => $getter) {
            $entity = $entity . $getter . "\n\n";
        }

        foreach ($this->setters as $key => $setter) {
            $entity = $entity . $setter . "\n\n";
        }

        return $entity . "}\n";
    }

    /**
     * @param   string  $tableName
     */
    private function buildAnnotation($tableName)
    {
        $annotation = "/**\n * @Entity(repositoryClass=\"\\" . $this->repositoryNamespace . '\\' . $this->snakeToCamel($tableName, true) . "\")\n";
        $annotation = $annotation . ' * @Table(name="' . $tableName . "\")\n";
        $this->annotation = $annotation . ' */';
    }

    /**
     * Convert snake case to camel case
     *
     * @param   string  $tableName
     * @param   bool    $upperFirst
     * @return  mixed
     */
    private function snakeToCamel($string, $upperFirst = false)
    {
        if ($upperFirst) {
            $string = ucfirst($string);
        }

        $func = function ($c) {
            return ucfirst($c[1]);
        };

        return preg_replace_callback('/_([a-z])/', $func, $string);
    }

    /**
     * @param array $primaryColumns
     * @param array $columns
     * @param array $types
     * @param array $extras
     */
    private function buildEntityProperties(array $primaryColumns, array $columns, array $types, array $extras)
    {
        foreach ($columns as $key => $column) {
            $doctrine = Mappings::getSqlToDoctrine($this->getSQLType($types[$column]));
            $php = Mappings::getDoctrineToPHP($doctrine);
            $isPrimary = in_array($column, $primaryColumns);
            $extra = array_key_exists($column, $extras) ? $extras[$column] : '';

            $this->generateProperty($column, $doctrine, $php, $isPrimary, $extra);
            $this->generateGetter($column, $php);
            $this->generateSetter($column, $php);
        }
    }

    /**
     * @param   string  $column
     * @param   string  $doctrine
     * @param   string  $php
     * @param   bool    $isPrimary
     * @param   string  $extra
     */
    private function generateProperty($column, $doctrine, $php, $isPrimary, $extra)
    {
        $property = "\t/**\n\t * @var\t" . $php . "\n";
        if ($isPrimary) {
            $property = $property . "\t * @Id\n";
        }
        $property = $property . "\t * @Column(type=\"" . $doctrine . "\")\n";
        if ($extra) {
            switch ($extra) {
                case 'auto_increment':
                    $property = $property . "\t * @GeneratedValue(strategy=\"AUTO\")\n";
            }
        }
        $property = $property . "\t */\n";
        $property = $property . "\tprotected $" . $column . ';';

        $this->properties[] = $property;
    }

    /**
     * @param   string  $column
     * @param   string  $php
     */
    private function generateGetter($column, $php)
    {
        $getter = "\t/**\n\t * @return\t" . $php . "\n\t */\n";
        $getter = $getter . "\tpublic function get" . $this->snakeToCamel($column, true) . "()\n";
        $getter = $getter . "\t{\n";
        $getter = $getter . "\t\treturn $" . "this->" . $column . ";\n";
        $getter = $getter . "\t}";

        $this->getters[] = $getter;
    }

    /**
     * @param   string  $column
     * @param   string  $php
     */
    private function generateSetter($column, $php)
    {
        $setter = "\t/**\n\t * @param\t" . $php . "\t$" . $this->snakeToCamel($column, false) . "\n\t */\n";
        $setter = $setter . "\tpublic function set" . $this->snakeToCamel($column, true) . '($' . $this->snakeToCamel($column, false) . ")\n";
        $setter = $setter . "\t{\n";
        $setter = $setter . "\t\t$" . "this->" . $column . ' = $' . $this->snakeToCamel($column, false) . ";\n";
        $setter = $setter . "\t}";

        $this->setters[] = $setter;
    }

    /**
     * @param   string  $type
     * @return  string
     */
    private function getSQLType($type)
    {
        $sqlType = SQL::VARCHAR;

        switch (true) {
            case stripos($type, SQL::VARCHAR, 0) !== false:
                $sqlType = SQL::VARCHAR;
                break;
            case stripos($type, SQL::TEXT, 0) !== false:
                $sqlType = SQL::TEXT;
                break;
            case stripos($type, SQL::ENUM, 0) !== false:
                $sqlType = SQL::ENUM;
                break;
            case stripos($type, SQL::CHAR, 0) !== false:
                $sqlType = SQL::CHAR;
                break;
            case stripos($type, SQL::MEDIUMINT, 0) !== false:
                $sqlType = SQL::MEDIUMINT;
                break;
            case stripos($type, SQL::BIGINT, 0) !== false:
                $sqlType = SQL::BIGINT;
                break;
            case stripos($type, SQL::TINYINT, 0) !== false:
                $sqlType = SQL::TINYINT;
                break;
            case stripos($type, SQL::SMALLINT, 0) !== false:
                $sqlType = SQL::SMALLINT;
                break;
            case stripos($type, SQL::INT, 0) !== false:
                $sqlType = SQL::INT;
                break;
            case stripos($type, SQL::DATETIME, 0) !== false:
                $sqlType = SQL::DATETIME;
                break;
            case stripos($type, SQL::TIMESTAMP, 0) !== false:
                $sqlType = SQL::TIMESTAMP;
                break;
            case stripos($type, SQL::DATE, 0) !== false:
                $sqlType = SQL::DATE;
                break;
            case stripos($type, SQL::DECIMAL, 0) !== false:
                $sqlType = SQL::DECIMAL;
                break;
            case stripos($type, SQL::NUMERIC, 0) !== false:
                $sqlType = SQL::NUMERIC;
                break;
            case stripos($type, SQL::FLOAT, 0) !== false:
                $sqlType = SQL::FLOAT;
                break;
            case stripos($type, SQL::DOUBLE, 0) !== false:
                $sqlType = SQL::DOUBLE;
                break;
            case stripos($type, SQL::BIT, 0) !== false:
                $sqlType = SQL::BIT;
                break;
        }

        return $sqlType;
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }
}