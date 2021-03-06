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

use Qandidate\Toggle\Operator\LessThan;
use Qandidate\Toggle\OperatorCondition;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection\PredisCollection;
use Qandidate\Toggle\ToggleManager;

// Create the ToggleManager
$predis = new Predis\Client();
$collection = new PredisCollection('toggle_demo', $predis);
$manager = new ToggleManager($collection);

// A toggle that will be active when  the user id is less than 42
$operator = new LessThan(42);
$condition = new OperatorCondition('user_id', $operator);
$toggle = new Toggle('toggling', [$condition]);

// Add the toggle to the manager
$manager->add($toggle);
