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
     * @return array
     */
    public function serialize(Toggle $toggle)
    {
        return [
            'name' => $toggle->getName(),
            'conditions' => $this->serializeConditions($toggle->getConditions()),
            'status' => $this->serializeStatus($toggle),
            'strategy' => $this->serializeStrategy($toggle),
        ];
    }

    /**
     * @return Toggle
     */
    public function deserialize(array $data)
    {
        $this->assertHasKey('name', $data);
        $this->assertHasKey('conditions', $data);

        if (!is_array($data['conditions'])) {
            throw new RuntimeException('Key "conditions" should be an array.');
        }

        $toggle = new Toggle(
            $data['name'],
            $this->deserializeConditions($data['conditions']),
            isset($data['strategy']) ? $this->deserializeStrategy($data['strategy']) : Toggle::STRATEGY_AFFIRMATIVE
        );

        if (isset($data['status'])) {
            $this->deserializeStatus($toggle, $data['status']);
        }

        return $toggle;
    }

    private function serializeConditions(array $conditions)
    {
        $serialized = [];

        foreach ($conditions as $condition) {
            if (!$condition instanceof OperatorCondition) {
                throw new RuntimeException(sprintf('Unable to serialize %s.', get_class($condition)));
            }

            $serialized[] = $this->operatorConditionSerializer->serialize($condition);
        }

        return $serialized;
    }

    private function deserializeConditions(array $conditions)
    {
        $deserialized = [];

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

        throw new \InvalidArgumentException('unsupported status');
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

    private function serializeStrategy(Toggle $toggle): string
    {
        switch ($toggle->getStrategy()) {
            case Toggle::STRATEGY_AFFIRMATIVE:
                return 'affirmative';
            case Toggle::STRATEGY_MAJORITY:
                return 'majority';
            case Toggle::STRATEGY_UNANIMOUS:
                return 'unanimous';
        }

        throw new \InvalidArgumentException('unsupported strategy');
    }

    /**
     * @param string $strategy
     *
     * @return int
     */
    private function deserializeStrategy($strategy)
    {
        switch ($strategy) {
            case 'affirmative':
                return Toggle::STRATEGY_AFFIRMATIVE;
            case 'majority':
                return Toggle::STRATEGY_MAJORITY;
            case 'unanimous':
                return Toggle::STRATEGY_UNANIMOUS;
        }

        throw new RuntimeException(sprintf('Unknown toggle strategy "%s".', $strategy));
    }

    private function assertHasKey($key, array $data)
    {
        if (!array_key_exists($key, $data)) {
            throw new RuntimeException(sprintf('Missing key "%s" in data.', $key));
        }
    }
}
