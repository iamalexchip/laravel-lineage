<?php

namespace Zerochip;

use Zerochip\Lineage\Picker;
use Exception;

class Lineage
{
    /**
     * Lineage source
     *
     * @var object
     */
    private $source;

    /**
     * Lineage structure
     *
     * @var array
     */
    private $structure;

    /**
     * Get lineage
     *
     * @param object $source
     * @param string $structure
     *
     * @return object
     */
    public static function get($source, $structure)
    {
        $lineage = new Lineage;
        $lineage->source = $source;
        $lineage->setStructure($structure);

        return $lineage->parse();
    }

    /**
     * Set lineage structure
     *
     * @param string $structure
     *
     * @return void
     */
    private function setStructure($structure)
    {
        $levels = $this->getLevels($structure);
        $structureString = '';
        $structureString2 = '';
        $levelCounter = 0;

        foreach ($levels as $level) {
            if ($levelCounter == count($levels) - 1) {
                $structureString2 = $structureString .'[lineage]';
            }

            if ($levelCounter != 0) {
                $structureString .= '['. $level .']';
            } else {
                $structureString .= $level;
            }

            $levelCounter++;
        }

        parse_str($structureString .'&'. $structureString2, $this->structure);
    }

    /**
     * Get lineage levels
     *
     * @param string $structure
     *
     * @return array
     */
    private function getLevels($structure)
    {
        if (substr($structure, 0, 2) == '->') {
            $structure = substr($structure, 2);
        }

        $levels = explode('->', $structure);

        if (count($levels) < 2) {
            $msg = "Lineage: Chain should have at least 2 levels eg. '->level1->level2'";
            throw new Exception($msg);
        }

        if (substr($structure, - 2) == '->') {
            throw new Exception("Lineage: End of chain should not be empty");
        }

        return $levels;
    }

    /**
     * Parse lineage
     *
     * @return object
     */
    private function parse()
    {
        $picker = new Picker($this->structure, $this->source);
        return $picker->get();
    }
}
