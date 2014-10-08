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
use Qandidate\Toggle\Toggle;
use RuntimeException;

/**
 * Hand written serializer to serialize a Toggle to a php array.
 */
class ToggleSerializer
{
    private $operatorConditionSerializer;

    public function __construct(OperatorConditionSerializer $operatorConditionSerializer)
    {
        $this->operatorConditionSerializer = $operatorConditionSerializer;
    }

    /**
     * @param Toggle $toggle
     *
     * @return string
     */
    public function serialize(Toggle $toggle)
    {
        return array(
            'name' => $toggle->getName(),
            'conditions' => $this->serializeConditions($toggle->getConditions()),
            'status' => $this->serializeStatus($toggle),
        );
    }

    /**
     * @param array $data
     *
     * @return Toggle
     */
    public function deserialize(array $data)
    {
        $this->assertHasKey('name', $data);
        $this->assertHasKey('conditions', $data);

        if ( ! is_array($data['conditions'])) {
            throw new RuntimeException('Key "conditions" should be an array.');
        }

        $toggle = new Toggle(
            $data['name'],
            $this->deserializeConditions($data['conditions'])
        );

        if (isset($data['status'])) {
            $this->deserializeStatus($toggle, $data['status']);
        }

        return $toggle;
    }

    private function serializeConditions(array $conditions)
    {
        $serialized = array();

        foreach ($conditions as $condition) {
            if ( ! $condition instanceof OperatorCondition) {
                throw new RuntimeException(sprintf('Unable to serialize %s.', get_class($condition)));
            }

            $serialized[] = $this->operatorConditionSerializer->serialize($condition);
        }

        return $serialized;
    }

    private function deserializeConditions(array $conditions)
    {
        $deserialized = array();

        foreach ($conditions as $condition) {
            $deserialized[] = $this->operatorConditionSerializer->deserialize($condition);
        }

        return $deserialized;
    }

    private function serializeStatus(Toggle $toggle)
    {
        switch ($toggle->getStatus()) {
            case Toggle::ALWAYS_ACTIVE:
                return 'always-active';
            case Toggle::INACTIVE:
                return 'inactive';
            case Toggle::CONDITIONALLY_ACTIVE:
                return 'conditionally-active';
        }
    }

    private function deserializeStatus(Toggle $toggle, $status)
    {
        switch ($status) {
            case 'always-active':
                $toggle->activate(Toggle::ALWAYS_ACTIVE);
                return;
            case 'inactive':
                $toggle->deactivate();
                return;
            case 'conditionally-active':
                $toggle->activate(Toggle::CONDITIONALLY_ACTIVE);
                return;
        }

        throw new RuntimeException(sprintf('Unknown toggle status "%s".', $status));
    }

    private function assertHasKey($key, array $data)
    {
        if ( ! array_key_exists($key, $data)) {
            throw new RuntimeException(sprintf('Missing key "%s" in data.', $key));
        }
    }
}
