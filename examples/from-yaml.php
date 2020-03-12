<?php

declare(strict_types=1);

require_once __DIR__.'/../vendor/autoload.php';

use Qandidate\Toggle\Context;
use Qandidate\Toggle\Serializer\InMemoryCollectionSerializer;
use Qandidate\Toggle\ToggleManager;
use Symfony\Component\Yaml\Yaml;

/**
 * To parse yml files, make sure you install symfony/yaml otherwise this example won't work. This can be achieved by:.
 *
 * $ composer require symfony/yaml
 */
$yaml = <<<YML
toggles:
  - name: some-feature
    conditions:
      - name: operator-condition
        key: user_id
        operator:
          name: greater-than
          value: 41
    status: conditionally-active
  - name: some-feature2
    conditions:
      - name: operator-condition
        key: user_id
        operator:
          name: greater-than
          value: 42
    status: conditionally-active
YML;

// Create the ToggleManager
$array = Yaml::parse($yaml);
$serializer = new InMemoryCollectionSerializer();
$collection = $serializer->deserialize($array['toggles']);
$manager = new ToggleManager($collection);

// Create and check a new context for a user with id 42
$context = new Context();
$context->set('user_id', 42);
var_dump($manager->active('some-feature', $context)); // true

// Create and check a new context for a user with id 21
$context = new Context();
$context->set('user_id', 21);
var_dump($manager->active('some-feature', $context)); // false
