<?php

namespace ForkCMS\Utility\Csv;

use Backend\Core\Engine\User;
use ForkCMS\Utility\PhpSpreadsheet\Reader\Filter\ChunkReadFilter;
use ForkCMS\Utility\PhpSpreadsheet\Reader\Filter\ColumnsFilter;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Worksheet\Row;

class Reader
{
    private function getUserOptions(User $user): array
    {
        $options['Delimiter'] = $user->getSetting('csv_split_character');

        $lineEnding = $user->getSetting('csv_line_ending');
        if ($lineEnding === '\n') {
            $options['LineEnding'] = "\n";
        }
        if ($lineEnding === '\r\n') {
            $options['LineEnding'] = "\r\n";
        }

        return $options;
    }

    private function getReader(array $options): Csv
    {
        $reader = new Csv();

        if (!empty($options)) {
            foreach ($options as $option => $value) {
                $methodName = 'set' . $option;
                if (method_exists($reader, $methodName)) {
                    $reader->$methodName($value);
                }
            }
        }

        return $reader;
    }

    public function findColumnIndexes(string $path, array $columns, User $user): array
    {
        $reader = $this->getReader($this->getUserOptions($user));
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new ChunkReadFilter(1, 1));
        $spreadSheet = $reader->load($path);

        $indexes = array_fill_keys(array_values($columns), null);

        foreach ($spreadSheet->getSheet(0)->getRowIterator() as $row) {
            foreach ($row->getCellIterator() as $cell) {
                if (in_array($cell->getValue(), $columns)) {
                    $indexes[$cell->getValue()] = $cell->getColumn();
                }
            }
        }

        return $indexes;
    }

    public function convertFileToArray(string $path, array $mapping, User $user): array
    {
        $data = [];

        $reader = $this->getReader($this->getUserOptions($user));
        $reader->setReadDataOnly(true);
        $reader->setReadFilter(new ColumnsFilter(array_keys($mapping)));
        $spreadSheet = $reader->load($path);

        foreach ($spreadSheet->getActiveSheet()->getRowIterator() as $row) {
            // skip the first row as it contains the headers
            if ($row->getRowIndex() === 1) {
                continue;
            }

            $data[] = $this->convertRowIntoMappedArray(
                $row,
                $mapping
            );
        }

        return $data;
    }

    public function convertRowIntoMappedArray(Row $row, array $mapping): array
    {
        $data = array_fill_keys(
            array_values($mapping),
            null
        );

        $cellIterator = $row->getCellIterator();
        foreach ($cellIterator as $cell) {
            if (in_array($cell->getColumn(), array_keys($mapping))) {
                $key = $mapping[$cell->getColumn()];
                $data[$key] = $cell->getValue();
            }
        }

        return $data;
    }
}
