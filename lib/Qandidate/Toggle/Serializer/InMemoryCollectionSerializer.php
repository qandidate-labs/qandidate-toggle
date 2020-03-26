<?php

/*
 * This file is part of the qandidate/toggle package.
 *
 * (c) Qandidate.com <opensource@qandidate.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Qandidate\Toggle\Serializer;

use Qandidate\Toggle\ToggleCollection\InMemoryCollection;

class InMemoryCollectionSerializer
{
    public function deserialize(array $data): InMemoryCollection
    {
        $collection = new InMemoryCollection();
        $serializer = new ToggleSerializer(new OperatorConditionSerializer(new OperatorSerializer()));

        foreach ($data as $serializedToggle) {
            $toggle = $serializer->deserialize($serializedToggle);
            $name = $toggle->getName();
            $collection->set($name, $toggle);
        }

        return $collection;
    }

    public function serialize(InMemoryCollection $toggleCollection): array
    {
        $serializer = new ToggleSerializer(new OperatorConditionSerializer(new OperatorSerializer()));
        $ret = [];
        foreach ($toggleCollection->all() as $toggle) {
            $ret[] = $serializer->serialize($toggle);
        }

        return $ret;
    }
}
