<?php

namespace Delta\TaskForce\DataConversion;

use Delta\TaskForce\exceptions\FileFormatException;
use Delta\TaskForce\exceptions\SourceFileException;

class ConversionCategory extends ConversionFromCSVtoSQL
{
    public function getFileName(): string
    {
        return 'data/categories.csv';
    }

    public function getColumns(): array
    {
        return ['name', 'icon'];
    }

    public function getTableName(): string
    {
        return 'category';
    }

    public function getColumnsSQL(): array
    {
        return ['name', 'icon'];
    }
}
