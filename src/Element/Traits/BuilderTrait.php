<?php

declare(strict_types=1);

namespace BinSoul\Symfony\Bundle\Content\Element\Traits;

use BinSoul\Symfony\Bundle\Content\Element\Type;

trait BuilderTrait
{
    private function buildClassName(Type $type): string
    {
        $code = strtolower($type->getCode());
        $asciiCode = preg_replace('/[^a-z0-9\-]/Su', '-', $code) ?? $code;

        return 'ce-'.trim(preg_replace('/[-]+/', '-', $asciiCode) ?? $asciiCode, '-');
    }
}
