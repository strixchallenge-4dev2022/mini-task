<?php

declare(strict_types=1);

namespace App;

interface BoxProviderInterface
{
    /**
     * @return array|Box[]
     */
    public function getAvailableBoxes(): array;
}
