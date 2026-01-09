<?php

namespace ForkCMS\Utility\PhpSpreadsheet\Reader\Filter;

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

class ChunkReadFilter implements IReadFilter
{
    public function __construct(private int $start, private int $end)
    {
    }

    public function setStart(int $start): self
    {
        $this->start = $start;

        return $this;
    }

    public function setEnd(int $end): self
    {
        $this->end = $end;

        return $this;
    }

    public function setChunk(int $start, int $numberOfRows): self
    {
        $this->start = $start;
        $this->end = $start + $numberOfRows;

        return $this;
    }

    public function readCell($column, $row, $worksheetName = ''): bool
    {
        if ($row >= $this->start && $row <= $this->end) {
            return true;
        }

        return false;
    }
}
