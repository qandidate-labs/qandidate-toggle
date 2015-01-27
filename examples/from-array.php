<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Qandidate\Toggle\Context;
use Qandidate\Toggle\ToggleManager;
use Qandidate\Toggle\Serializer\InMemoryCollectionSerializer;

// Array value
$data = array(
    'some-feature' => array(
        'name' => 'toggling',
        'conditions' => array(
            array(
                'name' => 'operator-condition',
                'key' => 'user_id',
                'operator' => array('name' => 'greater-than', 'value' => 41),
            ),
        ),
        'status' => 'conditionally-active',
    ),
);

// Create the ToggleManager
$serializer = new InMemoryCollectionSerializer();
$collection = $serializer->deserialize($data);
$manager    = new ToggleManager($collection);

// Create and check a new context for a user with id 42
$context = new Context();
$context->set('user_id', 42);
var_dump($manager->active('some-feature', $context)); // true

// Create and check a new context for a user with id 21
$context = new Context();
$context->set('user_id', 21);
var_dump($manager->active('some-feature', $context)); // false
