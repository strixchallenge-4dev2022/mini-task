<?php

declare(strict_types=1);

use App\Box;
use App\BoxPacker;
use App\BoxProviderInterface;
use App\Item;
use PHPUnit\Framework\TestCase;

final class PackingIntoTwoBoxesTest extends TestCase
{
    /**
     * @param array|Box[] $availableBoxes
     * @param array|Item[] $itemsToPack
     * @param int $expectedBoxesCount
     * @param int $expectedFirstBoxCapacity
     * @param int $expectedSecondBoxCapacity
     * @return void
     * @throws Exception
     *
     * @dataProvider testArgumentsDataProvider
     */
    public function testShouldPackIntoTwoBoxes(
        array $availableBoxes,
        array $itemsToPack,
        int   $expectedBoxesCount,
        int   $expectedFirstBoxCapacity,
        int   $expectedSecondBoxCapacity,
    ): void
    {
        // Arrange
        $boxProviderStub = $this->createStub(BoxProviderInterface::class);
        $boxProviderStub
            ->method('getAvailableBoxes')
            ->willReturn($availableBoxes);

        $boxPackerSut = new BoxPacker($boxProviderStub);

        // Act
        $packedBoxes = $boxPackerSut->packItems($itemsToPack);

        // Assert
        $this->assertCount($expectedBoxesCount, $packedBoxes, 'Used wrong number of boxes');
        $this->assertEquals($expectedFirstBoxCapacity, ($packedBoxes[0])->capacity, 'Did not use proper box capacity for the first box');
        $this->assertEquals($expectedSecondBoxCapacity, ($packedBoxes[1])->capacity, 'Did not use proper box capacity for the second box');
    }

    /**
     * @return array
     */
    private function testArgumentsDataProvider(): array
    {
        $availableBoxes = [new Box(6), new Box(3)];

        return [
            'data set #0' => [$availableBoxes, [new Item(5), new Item(2)], 2, 6, 3],
            'data set #1' => [$availableBoxes, [new Item(5), new Item(3), new Item(3), new Item(1)], 2, 6, 6],
            'data set #2' => [$availableBoxes, [new Item(3), new Item(4), new Item(2), new Item(3)], 2, 6, 6],
            'data set #3' => [$availableBoxes, [new Item(3), new Item(4), new Item(1), new Item(2), new Item(2)], 3, 6, 6],
        ];
    }
}

