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
use Qandidate\Toggle\ExpressionCondition;
use Qandidate\Toggle\Toggle;
use Qandidate\Toggle\ToggleCollection\InMemoryCollection;
use Qandidate\Toggle\ToggleManager;
use Symfony\Component\ExpressionLanguage\ExpressionLanguage;

// Create the ToggleManager
$manager = new ToggleManager(new InMemoryCollection());

// Create and check a new context and condition using symfony expression
$context = new Context();
$context->set('user', [
    'active' => true,
    'tags' => ['symfony2', 'qandidate'],
]);

$context->set('product', [
    'price' => 30,
]);

$language = new ExpressionLanguage();
$expression = new ExpressionCondition('user["active"] and product["price"] / 100 >= 0.2', $language);
$toggle = new Toggle('sf-toggling', [$expression]);

$manager->add($toggle);

var_dump($manager->active('sf-toggling', $context)); // true
