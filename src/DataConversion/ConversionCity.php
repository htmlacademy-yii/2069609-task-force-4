<?php

namespace Delta\TaskForce\DataConversion;

use Delta\TaskForce\exceptions\FileFormatException;
use Delta\TaskForce\exceptions\SourceFileException;

class ConversionCity extends ConversionFromCSVtoSQL
{
    public function getFileName():string
    {
        return 'data/cities.csv';
    }
    public function getColumns(): array
    {
        return ['name','lat','long'];
    }
    public function getTableName(): string
    {
        return 'city';
    }
    public function getColumnsSQL(): array
    {
        return ['name','latitude','longitude'];
    }

    /**
     * @throws SourceFileException
     * @throws FileFormatException
     */
    public function writeSQL(): void
    {
        $this->doConversion();
    }
}
