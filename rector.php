<?php

declare(strict_types=1);

use Qandidate\Toggle\Operator;
use PHPStan\Type\MixedType;
use PHPStan\Type\StringType;
use Qandidate\Toggle\Operator\EqualTo;
use Qandidate\Toggle\Operator\EqualityOperator;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\ClassMethod\AddParamTypeDeclarationRector;
use Rector\TypeDeclaration\ValueObject\AddParamTypeDeclaration;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/lib',
    ]);

    $rectorConfig->importNames();

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_81,
        SetList::TYPE_DECLARATION,
        SetList::CODE_QUALITY,
        SetList::STRICT_BOOLEANS,
    ]);
};
