<?php

namespace Delta\TaskForce\classes_for_DB;

use Delta\TaskForce\exceptions\FileFormatException;
use Delta\TaskForce\exceptions\SourceFileException;

class ConversionCity extends ConversionFromCSVtoSQL
{
    CONST FILENAME = 'data/cities.csv';
    CONST COLUMNS = ['name','lat','long'];
    CONST NAME_TABLE = 'city';
    CONST COLUMNS_SQL = ['name','latitude','longitude'];
    /**
     * @throws SourceFileException
     * @throws FileFormatException
     */
    public function getConversion(){
        $fileObjectSql = ConversionFromCSVtoSQL::getFileObjectSql(self::FILENAME);
        $fileObjectCsv = ConversionFromCSVtoSQL::getFileObjectCsv(self::COLUMNS, self::FILENAME);
        foreach (ConversionFromCSVtoSQL::getNextLine($fileObjectCsv) as $line) {
            list($name, $lat, $long) = $line;
            $fileObjectSql->fwrite("INSERT INTO " . self::NAME_TABLE . " (". self::COLUMNS_SQL[0] . ", ".self::COLUMNS_SQL[1]. ", ".self::COLUMNS_SQL[2].") VALUES ('" . $name . "', '" . $lat . "', '" . $long ."');\n");
        }
    }
}
