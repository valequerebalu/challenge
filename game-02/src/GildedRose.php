<?php

declare(strict_types=1);

namespace GildedRose;

use GildedRose\Updaters\AgedBrieUpdater;
use GildedRose\Updaters\BackstageUpdater;
use GildedRose\Updaters\ConjuredUpdater;
use GildedRose\Updaters\ItemUpdater;
use GildedRose\Updaters\NormalItemUpdater;
use GildedRose\Updaters\SulfurasUpdater;

final class GildedRose
{
    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            $updater = $this->getUpdater($item);
            $updater->update($item);
        }
    }

    private function getUpdater(Item $item): ItemUpdater
    {
        return match ($item->name) {
            'Aged Brie' => new AgedBrieUpdater(),
            'Backstage passes to a TAFKAL80ETC concert' => new BackstageUpdater(),
            'Sulfuras, Hand of Ragnaros' => new SulfurasUpdater(),
            default => str_contains($item->name, 'Conjured') ? new ConjuredUpdater() : new NormalItemUpdater(),
        };
    }
}
