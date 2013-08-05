<?php

require_once __DIR__ . '/../vendor/autoload.php';

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

// A toggle that will be active is the user id is less than 42
$operator  = new LessThan(42);
$condition = new OperatorCondition('user_id', $operator);
$toggle    = new Toggle('toggling', array($condition));

// Add the toggle to the manager
$manager->add($toggle);
