<?php

namespace Delta\TaskForce\DataConversion;

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
