<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Qandidate\Trigger\Context;
use Qandidate\Trigger\Operator\LessThan;
use Qandidate\Trigger\OperatorCondition;
use Qandidate\Trigger\Toggle;
use Qandidate\Trigger\ToggleCollection\InMemoryCollection;
use Qandidate\Trigger\ToggleManager;

// Create the ToggleManager
$manager = new ToggleManager(new InMemoryCollection());

// A toggle that will be active is the user id is less than 42
$operator  = new LessThan(42);
$condition =  new OperatorCondition('user_id', $operator);
$toggle    = new Toggle('toggling', array($condition));

// Add the toggle to the manager
$manager->add($toggle);

// Create and check a new context for a user with id 42
$context = new Context();
$context->set('user_id', 42);
var_dump($manager->active('toggling', $context)); // false

// Create and check a new context for a user with id 21
$context = new Context();
$context->set('user_id', 21);
var_dump($manager->active('toggling', $context)); // true
