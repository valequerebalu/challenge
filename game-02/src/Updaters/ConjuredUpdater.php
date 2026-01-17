<?php

declare(strict_types=1);

namespace GildedRose\Updaters;

use GildedRose\Item;

class ConjuredUpdater implements ItemUpdater
{
    public function update(Item $item): void
    {
        if ($item->quality > 0) {
            $item->quality = max(0, $item->quality - 2);
        }

        $item->sellIn--;

        if ($item->sellIn < 0 && $item->quality > 0) {
            $item->quality = max(0, $item->quality - 2);
        }
    }
}
