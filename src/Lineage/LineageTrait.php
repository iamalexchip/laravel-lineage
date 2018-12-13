<?php

namespace Zerochip\Lineage;

use Zerochip\Lineage;

trait LineageTrait
{
    /**
     * Get lineage
     *
     * @param string $lineage
     *
     * @return mixed
     */
    public function lineage($lineage) {
        return Lineage::get($this, $lineage);
    }
}
