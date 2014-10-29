<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Qandidate\Toggle\Context;
use Qandidate\Toggle\ExpressionCondition;
use Qandidate\Toggle\Operator\LessThan;
use Qandidate\Toggle\OperatorCondition;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection\InMemoryCollection;
use Qandidate\Toggle\ToggleManager;

// Create the ToggleManager
$manager = new ToggleManager(new InMemoryCollection());

// A toggle that will be active is the user id is less than 42
$operator  = new LessThan(42);
$condition = new OperatorCondition('user_id', $operator);
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

// Create and check a new context and condition using symfony expression
$context = new Context();
$context->set('user', array(
    'active' => true,
    'tags' => array('symfony2', 'qandidate'),
));

$context->set('product', array(
    'price' => 30,
));

$expression = new ExpressionCondition('user["active"] and product["price"] / 100 >= 0.2');
$toggle     = new Toggle('sf-toggling', array($expression));

$manager->add($toggle);

var_dump($manager->active('sf-toggling', $context)); // true