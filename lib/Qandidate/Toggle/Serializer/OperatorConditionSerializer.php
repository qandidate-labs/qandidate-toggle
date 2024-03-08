<?php

declare(strict_types=1);

/*
 * This file is part of the qandidate/toggle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Qandidate\Toggle\Serializer;

use Qandidate\Toggle\OperatorCondition;

/**
 * Hand written serializer to serialize an OperatorCondition to a php array.
 */
class OperatorConditionSerializer
{
    public function __construct(private readonly OperatorSerializer $operatorSerializer)
    {
    }

    public function serialize(OperatorCondition $condition): array
    {
        return [
            'name' => 'operator-condition',
            'key' => $condition->getKey(),
            'operator' => $this->operatorSerializer->serialize($condition->getOperator()),
        ];
    }

    public function deserialize(array $condition): OperatorCondition
    {
        $this->assertHasKey('name', $condition);
        $this->assertHasKey('key', $condition);
        $this->assertHasKey('operator', $condition);

        if ('operator-condition' !== $condition['name']) {
            throw new \RuntimeException(sprintf('Unable to deserialize operator with name "%s".', $condition['name']));
        }

        $operator = $this->operatorSerializer->deserialize($condition['operator']);

        return new OperatorCondition($condition['key'], $operator);
    }

    private function assertHasKey(string $key, array $data): void
    {
        if (!array_key_exists($key, $data)) {
            throw new \RuntimeException(sprintf('Missing key "%s" in data.', $key));
        }
    }
}
