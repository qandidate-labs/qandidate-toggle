<?php

declare(strict_types=1);

namespace Qandidate\Toggle\Serializer;

use Qandidate\Toggle\ToggleCollection\InMemoryCollection;

class InMemoryCollectionSerializer
{
    /**
     * @return InMemoryCollection
     */
    public function deserialize(array $data)
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

    /**
     * @return array
     */
    public function serialize(InMemoryCollection $toggleCollection)
    {
        $serializer = new ToggleSerializer(new OperatorConditionSerializer(new OperatorSerializer()));
        $ret = [];
        foreach ($toggleCollection->all() as $toggle) {
            $ret[] = $serializer->serialize($toggle);
        }

        return $ret;
    }
}
