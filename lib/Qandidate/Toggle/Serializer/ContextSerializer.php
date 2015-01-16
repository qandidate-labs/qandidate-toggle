<?php

namespace Qandidate\Toggle\Serializer;

use Qandidate\Toggle\Context;

class ContextSerializer
{
    /**
     * @param array $data
     *
     * @return Context
     */
    public function deserialize(array $data)
    {
        $this->assertHasKey('context', $data);
        if ( ! is_array($data['context'])) {
            throw new \RuntimeException('Key "context" should be an array.');
        }

        $context = new Context();
        foreach ($data['context'] as $key => $value) {
            if (!$context->has($key)) {
                $context->set($key, $value);
            }
        }

        return $context;
    }

    /**
     * @param $key
     * @param array $data
     */
    private function assertHasKey($key, array $data)
    {
        if ( ! array_key_exists($key, $data)) {
            throw new \RuntimeException(sprintf('Missing key "%s" in data.', $key));
        }
    }
} 