<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Predis\Client;
use Qandidate\Trigger\Context;
use Qandidate\Trigger\Operator\LessThan;
use Qandidate\Trigger\OperatorCondition;
use Qandidate\Trigger\Toggle;
use Qandidate\Trigger\ToggleCollection\PredisCollection;
use Qandidate\Trigger\ToggleManager;

// Create the ToggleManager
$predis     = new Predis\Client();
$collection = new PredisCollection('toggle_demo', $predis);
$manager    = new ToggleManager($collection);

// Create and check a new context for a user with id 42
$context = new Context();
$context->set('user_id', 42);
var_dump($manager->active('toggling', $context)); // false

// Create and check a new context for a user with id 21
$context = new Context();
$context->set('user_id', 21);
var_dump($manager->active('toggling', $context)); // true
