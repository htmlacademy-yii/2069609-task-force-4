<?php

namespace Delta\TaskForce\DataConversion;

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
}
