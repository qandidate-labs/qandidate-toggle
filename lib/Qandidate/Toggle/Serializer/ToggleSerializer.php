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

use RuntimeException;
use InvalidArgumentException;
use Qandidate\Toggle\OperatorCondition;
use Qandidate\Toggle\Toggle;

/**
 * Hand written serializer to serialize a Toggle to a php array.
 */
class ToggleSerializer
{
    public function __construct(private readonly OperatorConditionSerializer $operatorConditionSerializer)
    {
    }

    public function serialize(Toggle $toggle): array
    {
        return [
            'name' => $toggle->getName(),
            'conditions' => $this->serializeConditions($toggle->getConditions()),
            'status' => $this->serializeStatus($toggle),
            'strategy' => $this->serializeStrategy($toggle),
        ];
    }

    public function deserialize(array $data): Toggle
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

    private function serializeConditions(array $conditions): array
    {
        $serialized = [];

        foreach ($conditions as $condition) {
            if (!$condition instanceof OperatorCondition) {
                throw new RuntimeException(sprintf('Unable to serialize %s.', $condition::class));
            }

            $serialized[] = $this->operatorConditionSerializer->serialize($condition);
        }

        return $serialized;
    }

    private function deserializeConditions(array $conditions): array
    {
        $deserialized = [];

        foreach ($conditions as $condition) {
            $deserialized[] = $this->operatorConditionSerializer->deserialize($condition);
        }

        return $deserialized;
    }

    private function serializeStatus(Toggle $toggle): string
    {
        return match ($toggle->getStatus()) {
            Toggle::ALWAYS_ACTIVE => 'always-active',
            Toggle::INACTIVE => 'inactive',
            Toggle::CONDITIONALLY_ACTIVE => 'conditionally-active',
            default => throw new InvalidArgumentException('unsupported status'),
        };
    }

    private function deserializeStatus(Toggle $toggle, string $status): void
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
        return match ($toggle->getStrategy()) {
            Toggle::STRATEGY_AFFIRMATIVE => 'affirmative',
            Toggle::STRATEGY_MAJORITY => 'majority',
            Toggle::STRATEGY_UNANIMOUS => 'unanimous',
            default => throw new InvalidArgumentException('unsupported strategy'),
        };
    }

    private function deserializeStrategy(string $strategy): int
    {
        return match ($strategy) {
            'affirmative' => Toggle::STRATEGY_AFFIRMATIVE,
            'majority' => Toggle::STRATEGY_MAJORITY,
            'unanimous' => Toggle::STRATEGY_UNANIMOUS,
            default => throw new RuntimeException(sprintf('Unknown toggle strategy "%s".', $strategy)),
        };
    }

    private function assertHasKey(string $key, array $data): void
    {
        if (!array_key_exists($key, $data)) {
            throw new RuntimeException(sprintf('Missing key "%s" in data.', $key));
        }
    }
}
