<?php

namespace DoctrineEntityGenerator\Types;

final class Mappings
{
    /**
     * Map Doctrine types to SQL types
     *
     * @var array
     */
    protected static $sqlToDoctrine = [
        SQL::INT        => Doctrine::INTEGER,
        SQL::MEDIUMINT  => Doctrine::INTEGER,
        SQL::SMALLINT   => Doctrine::INTEGER,
        SQL::BIGINT     => Doctrine::BIGINT,
        SQL::TINYINT    => Doctrine::INTEGER,
        SQL::FLOAT      => Doctrine::FLOAT,
        SQL::DOUBLE     => Doctrine::FLOAT,
        SQL::CHAR       => Doctrine::STRING,
        SQL::VARCHAR    => Doctrine::STRING,
        SQL::TEXT       => Doctrine::STRING,
        SQL::ENUM       => Doctrine::STRING,
        SQL::DATE       => Doctrine::DATE,
        SQL::DATETIME   => Doctrine::DATETIME,
        SQL::TIMESTAMP  => Doctrine::DATETIME,
        SQL::DECIMAL    => Doctrine::DECIMAL,
        SQL::NUMERIC    => Doctrine::STRING,
        SQL::BIT        => Doctrine::BOOLEAN
    ];

    /**
     * @var array
     */
    private static $doctrineToPHP = [
        Doctrine::INTEGER  => PHP::INT,
        Doctrine::BIGINT   => PHP::STRING,
        Doctrine::FLOAT    => PHP::FLOAT,
        Doctrine::STRING   => PHP::STRING,
        Doctrine::TEXT     => PHP::STRING,
        Doctrine::DECIMAL  => PHP::STRING,
        Doctrine::DATE     => PHP::DATETIME,
        Doctrine::DATETIME => PHP::DATETIME,
        Doctrine::BOOLEAN  => PHP::BOOLEAN
    ];

    /**
     * @param   $string $sql
     * @return  $string
     */
    public static function getSqlToDoctrine($sql)
    {
        return self::$sqlToDoctrine[$sql];
    }

    /**
     * @param   string  $doctrine
     * @return  string
     */
    public static function getDoctrineToPHP($doctrine)
    {
        return self::$doctrineToPHP[$doctrine];
    }
}