<?php

namespace Common\DataCollector;

use SpoonDatabase;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DatabaseDataCollector extends DataCollector
{
    public function __construct(private SpoonDatabase $database)
    {
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        $this->data = [
            'queries' => array_map(
                function (array $query) {
                    $query['query_formatted'] = \SqlFormatter::format($query['query']);

                    return $query;
                },
                (array) $this->database->getQueries()
            ),
            'queryCount' => count($this->database->getQueries()),
        ];
    }

    public function getQueryCount(): int
    {
        return $this->data['queryCount'] ?? 0;
    }

    public function getQueries(): array
    {
        return $this->data['queries'] ?? [];
    }

    public function getName(): string
    {
        return 'database';
    }

    public function reset(): void
    {
        $this->data = [];
    }
}
