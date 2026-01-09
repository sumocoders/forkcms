<?php

namespace ForkCMS\Utility\PhpSpreadsheet\Reader\Filter;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ColumnsFilter implements IReadFilter
{
    public function __construct(private array $columns)
    {
    }

    public function readCell($column, $row, $worksheetName = ''): bool
    {
        if (in_array($column, $this->columns)) {
            return true;
        }

        return false;
    }
}
