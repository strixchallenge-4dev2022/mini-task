<?php

declare(strict_types=1);

namespace App;

class Box
{
    /**
     * @param int $capacity
     * @param array|Item[] $items
     */
    public function __construct(
        public        readonly int $capacity,
        private array $items = [],
    )
    {
    }

    /**
     * @param Item $item
     * @return void
     */
    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    /**
     * @return array|Item[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    /**
     * @return int
     */
    public function getUsedCapacity(): int
    {
        $usedCapacity = 0;

        foreach ($this->items as $item) {
            $usedCapacity += $item->size;
        }

        return $usedCapacity;
    }

    /**
     * @return int
     */
    public function getRemainingCapacity(): int
    {
        return $this->capacity - $this->getUsedCapacity();
    }
}
