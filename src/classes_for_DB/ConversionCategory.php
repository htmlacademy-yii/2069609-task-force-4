<?php

namespace Delta\TaskForce\classes_for_DB;

use Delta\TaskForce\exceptions\FileFormatException;
use Delta\TaskForce\exceptions\SourceFileException;

class ConversionCategory extends ConversionFromCSVtoSQL
{
    CONST FILENAME = 'data/categories.csv';
    CONST COLUMNS = ['name', 'icon'];
    CONST NAME_TABLE = 'category';

    /**
     * @throws SourceFileException
     * @throws FileFormatException
     */
    public function getConversion(){
        $fileObjectSql = ConversionFromCSVtoSQL::getFileObjectSql(self::FILENAME);
        $fileObjectCsv = ConversionFromCSVtoSQL::getFileObjectCsv(self::COLUMNS, self::FILENAME);
        foreach (ConversionFromCSVtoSQL::getNextLine($fileObjectCsv) as $line) {
            list($name, $icon) = $line;
            $fileObjectSql->fwrite("INSERT INTO " . self::NAME_TABLE . " (". self::COLUMNS[0] . ", ".self::COLUMNS[1].") VALUES ('" . $name . "', '" . $icon . "');\n");
        }
    }
}
