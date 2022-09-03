<?php

namespace Delta\TaskForce\DataConversion;

use Delta\TaskForce\exceptions\FileFormatException;
use Delta\TaskForce\exceptions\SourceFileException;
use RuntimeException;

abstract class ConversionFromCSVtoSQL
{
    abstract protected function getFileName():string;
    abstract protected function getColumns():array;
    abstract protected function getTableName():string;
    abstract protected function getColumnsSQL():array;

    protected function getFileObjectSQl(string $filename): \SplFileObject
    {
        $fileObjectInfo = new \SplFileInfo($filename);
        $fileObjectPath = $fileObjectInfo->getPath();
        $fileObjectBasename = $fileObjectInfo->getBasename('.csv');
        $filenameSql = $fileObjectPath . '/' . $fileObjectBasename . '.sql';
        return new \SplFileObject($filenameSql, 'w+');
    }

    private function getHeaderData(\SplFileObject $fileObjectCsv): ?array
    {
        //устанавливаем курсор файла в начало
        $fileObjectCsv->rewind();
        //получаем ПЕРВУЮ строку из файла и её разбираем ее как поля CSV
        return $fileObjectCsv->fgetcsv();
    }

    protected function getNextLine(\SplFileObject $fileObjectCsv): ?iterable
    {
        while (!$fileObjectCsv->eof()) {
            yield $fileObjectCsv->fgetcsv();
        }
        return null;
    }

    /**
     * @throws SourceFileException
     * @throws FileFormatException
     */
    protected function getFileObjectCsv(array $columns, string $filename): \SplFileObject
    {
        if (!file_exists($filename)) {
            throw new SourceFileException("Файл не существует");
        }

        try {
            $fileObjectCsv = new \SplFileObject($filename);
        } catch (RuntimeException $exception) {
            throw new SourceFileException("Не удалось открыть файл на чтение");
        }
        //находим заголовки csv файла
        $header_data = $this->getHeaderData($fileObjectCsv);

        if ($header_data !== $columns) {
            throw new FileFormatException("Исходный файл не содержит необходимых столбцов");
        }
        return $fileObjectCsv;
    }

    /**
     * @throws SourceFileException
     * @throws FileFormatException
     */
    public function doConversion(): void
    {
        $fileObjectSql = $this->getFileObjectSql($this->getFileName());
        $fileObjectCsv = $this->getFileObjectCsv($this->getColumns(), $this->getFileName());
        foreach ($this->getNextLine($fileObjectCsv) as $line) {
             {
                 $valueColumns ="('".implode("','", $line)."')";
                 $fileObjectSql->fwrite("INSERT INTO " . $this->getTableName() . " (" . implode(", ", $this->getColumnsSQL()) . ") VALUES " . $valueColumns . ";\n");
             }
        }
    }
}
