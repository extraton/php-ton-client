<?php

declare(strict_types=1);

namespace Extraton\TonClient\Entity\Net;

use Extraton\TonClient\Entity\Params;
use Extraton\TonClient\Exception\LogicException;

use function get_class;
use function sprintf;

class ParamsOfBatchQuery implements Params
{
    private const MAP = [
        ParamsOfQueryCollection::class     => 'QueryCollection',
        ParamsOfWaitForCollection::class   => 'WaitForCollection',
        ParamsOfAggregateCollection::class => 'AggregateCollection',
    ];

    /** @var array<array<mixed>> */
    private array $paramsOfQueries = [];

    /**
     * @param QueryInterface $query
     */
    public function add(QueryInterface $query): void
    {
        $class = get_class($query);

        if (!array_key_exists($class, self::MAP)) {
            throw new LogicException(sprintf('Unsupported query type %s was passed.', $class));
        }

        $params = $query->jsonSerialize();
        $params['type'] = self::MAP[$class];

        $this->paramsOfQueries[] = $params;
    }

    public function jsonSerialize(): array
    {
        return $this->paramsOfQueries;
    }
}
