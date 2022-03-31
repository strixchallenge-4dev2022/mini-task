<?php

declare(strict_types=1);

namespace App;

use Exception;

class BoxPacker
{
    public function __construct(
        private readonly BoxProviderInterface $boxProvider
    )
    {
    }

    /**
     * @param array $items |Item[]
     * @return array |Box[]
     * @throws Exception
     */
    public function packItems(array $items): array
    {
        $packedBoxes = [];

        // @TODO Verify whether we should pack biggest items first, all tests are passing so probably we do not have to

        /** @var Item $item */
        foreach ($items as $item) {
            if ($existingBox = $this->findExistingBoxWithEnoughRemainingCapacity($packedBoxes, $item->size)) {
                $existingBox->addItem($item);
            } else {
                $newBox = $this->getBiggestBox();
                $newBox->addItem($item);
                $packedBoxes[] = $newBox;
            }
        }

        if(!empty($packedBoxes)) {
            /** @var Box $lastBox */
            $lastBox = array_pop($packedBoxes);

            if ($smallerBox = $this->findSuitableSmallerBox($lastBox)) {
                foreach ($lastBox->getItems() as $item) {
                    $smallerBox->addItem($item);
                }
                $lastBox = $smallerBox;
            }

            $packedBoxes[] = $lastBox;
        }

        return $packedBoxes;
    }

    /**
     * @return Box
     * @throws Exception
     */
    private function getBiggestBox(): Box
    {
        $boxes = $this->boxProvider->getAvailableBoxes();

        if (empty($boxes)) {
            throw new Exception('There are no boxes available');
        }
        $biggestBox = reset($boxes);

        foreach ($boxes as $box) {
            if ($box->capacity > $biggestBox->capacity) {
                $biggestBox = $box;
            }
        }

        return clone $biggestBox;
    }

    /**
     * @param Box $existingBox
     * @return Box|null
     */
    private function findSuitableSmallerBox(Box $existingBox): ?Box
    {
        if ($existingBox->getRemainingCapacity() > 0) {
            $boxes = $this->boxProvider->getAvailableBoxes();

            usort($boxes, function (Box $a, Box $b) {
                return $a->capacity - $b->capacity;
            });

            foreach ($boxes as $box) {
                if ($box->capacity >= $existingBox->getUsedCapacity() && $box->capacity < $existingBox->capacity) {
                    return clone $box;
                }
            }
        }

        return null;
    }

    /**
     * @param array|Box[] $packedBoxes
     * @param int $desiredCapacity
     * @return Box|null
     */
    private function findExistingBoxWithEnoughRemainingCapacity(array $packedBoxes, int $desiredCapacity): ?Box
    {
        if (!empty($packedBoxes)) {
            usort($packedBoxes, function (Box $a, Box $b) {
                return $a->getRemainingCapacity() - $b->getRemainingCapacity();
            });

            foreach ($packedBoxes as $box) {
                if ($box->getRemainingCapacity() >= $desiredCapacity) {
                    return $box;
                }
            }
        }

        return null;
    }
}
