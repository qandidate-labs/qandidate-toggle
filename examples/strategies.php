<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Qandidate\Toggle\Context;
use Qandidate\Toggle\Operator\LessThan;
use Qandidate\Toggle\Operator\GreaterThan;
use Qandidate\Toggle\OperatorCondition;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection\InMemoryCollection;
use Qandidate\Toggle\ToggleManager;

// Create the ToggleManager
$manager = new ToggleManager(new InMemoryCollection());

// Affirmative strategy
// A toggle that will be active if the user id is less than 42 or if the age is greater than 24
$conditions   = [];
$operator     = new LessThan(42);
$conditions[] = new OperatorCondition('user_id', $operator);
$operator     = new GreaterThan(24);
$conditions[] = new OperatorCondition('age', $operator);
$toggle       = new Toggle('toggling', $conditions);

// Add the toggle to the manager
$manager->add($toggle);

// Create and check a new context for a user with id 41 and age 23
$context = new Context();
$context->set('user_id', 41);
$context->set('age', 23);
var_dump($manager->active('toggling', $context)); // true

// Majority strategy
// A toggle that will be active if at least two of the following requirements are met :
// - The user id is less than 42
// - The user age is greater than 24
// - The user height is greater than 5.7
$conditions   = [];
$operator     = new LessThan(42);
$conditions[] = new OperatorCondition('user_id', $operator);
$operator     = new GreaterThan(24);
$conditions[] = new OperatorCondition('age', $operator);
$operator     = new GreaterThan(5.7);
$conditions[] = new OperatorCondition('height', $operator);
$toggle       = new Toggle('toggling', $conditions, Toggle::STRATEGY_MAJORITY);
$toggle->activate(Toggle::CONDITIONALLY_ACTIVE);

// Add the toggle to the manager
$manager->add($toggle);

// Create and check a new context for a user with id 41, age 25 and height 5.6
$context = new Context();
$context->set('user_id', 41);
$context->set('age', 25);
$context->set('height', 5.6);
var_dump($manager->active('toggling', $context)); // true

// Create and check a new context for a user with id 41, age 23 and height 5.6
$context = new Context();
$context->set('user_id', 41);
$context->set('age', 23);
$context->set('height', 5.6);
var_dump($manager->active('toggling', $context)); // false

// Unanimous strategy
// A toggle that will be active if the user id is less than 42 and if the age is greater than 24
$conditions   = [];
$operator     = new LessThan(42);
$conditions[] = new OperatorCondition('user_id', $operator);
$operator     = new GreaterThan(24);
$conditions[] = new OperatorCondition('age', $operator);
$toggle       = new Toggle('toggling', $conditions, Toggle::STRATEGY_UNANIMOUS);
$toggle->activate(Toggle::CONDITIONALLY_ACTIVE);

// Add the toggle to the manager
$manager->add($toggle);

// Create and check a new context for a user with id 41 and age 25
$context = new Context();
$context->set('user_id', 41);
$context->set('age', 25);
var_dump($manager->active('toggling', $context)); // true

// Create and check a new context for a user with id 41 and age 23
$context = new Context();
$context->set('user_id', 41);
$context->set('age', 23);
var_dump($manager->active('toggling', $context)); // false
