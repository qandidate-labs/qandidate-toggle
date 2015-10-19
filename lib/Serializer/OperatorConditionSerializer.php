<?php

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
use RuntimeException;

/**
 * Hand written serializer to serialize an OperatorCondition to a php array.
 */
class OperatorConditionSerializer
{
    private $operatorSerializer;

    /**
     * @param OperatorSerializer $operatorSerializer
     */
    public function __construct(OperatorSerializer $operatorSerializer)
    {
        $this->operatorSerializer = $operatorSerializer;
    }

    /**
     * @param OperatorCondition $condition
     *
     * @return string
     */
    public function serialize(OperatorCondition $condition)
    {
        return array(
            'name' => 'operator-condition',
            'key'  => $condition->getKey(),
            'operator' => $this->operatorSerializer->serialize($condition->getOperator()),
        );
    }

    /**
     * @param array $condition
     *
     * @return OperatorCondition
     */
    public function deserialize(array $condition)
    {
        $this->assertHasKey('name', $condition);
        $this->assertHasKey('key', $condition);
        $this->assertHasKey('operator', $condition);

        if ($condition['name'] !== 'operator-condition') {
            throw new RuntimeException(sprintf('Unable to deserialize operator with name "%s".', $condition['name']));
        }

        $operator = $this->operatorSerializer->deserialize($condition['operator']);

        return new OperatorCondition($condition['key'], $operator);
    }

    private function assertHasKey($key, array $data)
    {
        if ( ! array_key_exists($key, $data)) {
            throw new RuntimeException(sprintf('Missing key "%s" in data.', $key));
        }
    }
}
