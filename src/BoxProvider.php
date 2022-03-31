<?php

declare(strict_types=1);

namespace App;

class BoxProvider implements BoxProviderInterface
{
    public function getAvailableBoxes(): array
    {
        return [
            new Box(3),
            new Box(6),
            new Box(9),
        ];
    }
}
