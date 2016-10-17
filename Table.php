<?php

namespace DoctrineEntityGenerator;

class Table
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var array
     */
    private $columns = [];

    /**
     * @var array
     */
    private $columnSQLTypes = [];

    /**
     * @var array
     */
    private $columnExtras = [];

    /**
     * @var array
     */
    private $primaryColumns = [];

    /**
     * Table constructor.
     *
     * @param   string  $name
     * @param   array   $table
     */
    public function __construct($name, array $table)
    {
        $this->name = $name;

        foreach ($table as $column) {
            if (is_array($column)) {
                $column = (object) $column;
            }
            
            $this->columns[] = $column->Field;
            $this->columnSQLTypes[$column->Field] = $column->Type;

            if ($column->Key == 'PRI') {
                $this->primaryColumns[] = $column->Field;
            }
            
            if (empty($column->Extra) == false) {
                $this->columnExtras[$column->Field] = $column->Extra;
            }
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getColumnSQLTypes()
    {
        return $this->columnSQLTypes;
    }

    /**
     * @return array
     */
    public function getPrimaryColumns()
    {
        return $this->primaryColumns;
    }

    /**
     * @return array
     */
    public function getColumnExtras()
    {
        return $this->columnExtras;
    }
}
