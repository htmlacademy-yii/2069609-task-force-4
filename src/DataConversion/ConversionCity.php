<?php

namespace Delta\TaskForce\DataConversion;

use Delta\TaskForce\exceptions\FileFormatException;
use Delta\TaskForce\exceptions\SourceFileException;

class ConversionCity extends ConversionFromCSVtoSQL
{
    const FILENAME = 'data/cities.csv';
    const COLUMNS = ['name','lat','long'];
    const NAME_TABLE = 'city';
    const COLUMNS_SQL = ['name','latitude','longitude'];
    /**
     * @throws SourceFileException
     * @throws FileFormatException
     */
    public function getConversion(): void
    {
        $fileObjectSql = ConversionFromCSVtoSQL::getFileObjectSql(self::FILENAME);
        $fileObjectCsv = ConversionFromCSVtoSQL::getFileObjectCsv(self::COLUMNS, self::FILENAME);
        foreach (ConversionFromCSVtoSQL::getNextLine($fileObjectCsv) as $line) {
            list($name, $lat, $long) = $line;
            $fileObjectSql->fwrite("INSERT INTO " . self::NAME_TABLE . " (". self::COLUMNS_SQL[0] . ", ".self::COLUMNS_SQL[1]. ", ".self::COLUMNS_SQL[2].") VALUES ('" . $name . "', '" . $lat . "', '" . $long ."');\n");
        }
    }
}
