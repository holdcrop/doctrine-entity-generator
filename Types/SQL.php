<?php

namespace DoctrineEntityGenerator\Types;

final class SQL
{
    /**
     * Numeric Types
     */

    // Int
    const INT = 'int';
    const MEDIUMINT = 'mediumint';
    const TINYINT = 'tinyint';
    const SMALLINT = 'smallint';
    const BIGINT = 'bigint';

    // Fixed point
    const DECIMAL = 'decimal';
    const NUMERIC = 'numeric';

    // Floating point
    const FLOAT = 'float';
    const DOUBLE = 'double';

    // Bit
    const BIT = 'bit';


    /**
     * String Types
     */

    // Char
    const CHAR = 'char';

    // Varchar
    const VARCHAR = 'varchar';

    // Text
    const TEXT = 'text';

    // Enum
    const ENUM = 'enum';


    /**
     * Date Types
     */

    // Date
    const DATE = 'date';

    // Datetime
    const DATETIME = 'datetime';

    // Timestamp
    const TIMESTAMP = 'timestamp';
}