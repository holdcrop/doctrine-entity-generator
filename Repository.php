<?php

namespace DoctrineEntityGenerator;

class Repository
{
    /**
     * @var string
     */
    private $repositoryName;

    /**
     * @var string
     */
    private $namespace;

    /**
     * @var string
     */
    private $extend;

    /**
     * @var string
     */
    private $use;

    /**
     * Repository constructor.
     * @param   string  $repositoryName
     * @param   string  $namespace
     * @param   string  $use
     * @param   string  $extend
     */
    public function __construct($repositoryName, $namespace, $use = '', $extend = '')
    {
        $this->repositoryName = $repositoryName;
        $this->namespace = $namespace;
        $this->use = $use;
        $this->extend = $extend;
    }

    /**
     * @return string
     */
    public function getRepositoryName()
    {
        return $this->repositoryName;
    }

    /**
     * @return string
     */
    public function getRepository()
    {
        $repository = "<?php\n\nnamespace " . $this->namespace . ";\n\n";
        if ($this->use) {
            $repository = $repository . 'use ' . $this->use . ";\n\n";
        }
        if ($this->extend) {
            $repository = $repository . 'class ' . $this->repositoryName . ' extends ' . $this->extend . "\n";
        } else {
            $repository = $repository . 'class ' . $this->repositoryName . "\n";
        }
        return $repository . "{\n}\n";
    }
}