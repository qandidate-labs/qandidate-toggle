<?php

namespace Qandidate\Toggle\Serializer;

use Qandidate\Toggle\ToggleCollection\InMemoryCollection;

class InMemoryCollectionSerializer
{
    /**
     * @param array $data
     * @return InMemoryCollection
     */
    public function deserialize(array $data)
    {
        $collection = new InMemoryCollection();
        $serializer = new ToggleSerializer(new OperatorConditionSerializer(new OperatorSerializer()));

        foreach ($data as $name => $serializedToggle) {
            $toggle = $serializer->deserialize($serializedToggle);
            $collection->set($name, $toggle);
        }

        return $collection;
    }

    /**
     * @param InMemoryCollection $toggleCollection
     * @return array
     */
    public function serialize(InMemoryCollection $toggleCollection)
    {
        $serializer = new ToggleSerializer(new OperatorConditionSerializer(new OperatorSerializer()));
        $ret = array();
        foreach ($toggleCollection->all() as $key => $toggle) {
            $ret[$key] = $serializer->serialize($toggle);
        }

        return $ret;
    }
}
