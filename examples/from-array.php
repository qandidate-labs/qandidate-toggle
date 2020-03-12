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

require_once __DIR__.'/../vendor/autoload.php';

use Qandidate\Toggle\Context;
use Qandidate\Toggle\Serializer\InMemoryCollectionSerializer;
use Qandidate\Toggle\ToggleManager;

// Array value
$data = [
    'some-feature' => [
        'name' => 'toggling',
        'conditions' => [
            [
                'name' => 'operator-condition',
                'key' => 'user_id',
                'operator' => ['name' => 'greater-than', 'value' => 41],
            ],
        ],
        'status' => 'conditionally-active',
    ],
];

// Create the ToggleManager
$serializer = new InMemoryCollectionSerializer();
$collection = $serializer->deserialize($data);
$manager = new ToggleManager($collection);

// Create and check a new context for a user with id 42
$context = new Context();
$context->set('user_id', 42);
var_dump($manager->active('toggling', $context)); // true

// Create and check a new context for a user with id 21
$context = new Context();
$context->set('user_id', 21);
var_dump($manager->active('toggling', $context)); // false
