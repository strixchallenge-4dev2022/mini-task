<?php

declare(strict_types=1);

namespace App;

class Item
{
    public function __construct(
        public readonly int $size,
    )
    {
    }
}
