<?php

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
        );
    }

    /**
     * @param array $toggle
     *
     * @return Toggle
     */
    public function deserialize(array $toggle)
    {
        $this->assertHasKey('name', $toggle);
        $this->assertHasKey('conditions', $toggle);

        if ( ! is_array($toggle['conditions'])) {
            throw new RuntimeException('Key "conditions" should be an array.');
        }

        return new Toggle(
            $toggle['name'],
            $this->deserializeConditions($toggle['conditions'])
        );
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

    private function assertHasKey($key, array $data)
    {
        if ( ! array_key_exists($key, $data)) {
            throw new RuntimeException(sprintf('Missing key "%s" in data.', $key));
        }
    }
}
