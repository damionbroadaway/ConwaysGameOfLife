<?php
/**
 * File LIfe.php
 *
 * @package App
 */

namespace App;

/**
 * [Class desc. text goes here...]
 *
 * @package App
 * @author  Damion M Broadaway <dbroadaw@nerdery.com>
 */
class Life
{
    /**
     * Seed is based on a square.
     * This value is the sides.
     */
    const SEED_SIZE = 5;

    /**
     * @var int
     */
    private $gridWidth;

    /**
     * @var int
     */
    private $gridHeight;

    /**
     * @var array
     */
    private $grid;

    /**
     * @var int
     */
    private $generation;

    /**
     * @var array
     */
    private $life;

    /**
     * Life constructor.
     *
     * @param int  $width
     * @param int  $height
     * @param null $generation
     * @param null $life
     */
    public function __construct($width, $height, $generation = null, $life = null)
    {
        if (!$this->checkForSquare($width, $height)) {
            return false;
        }

        $this->setGridWidth($width);
        $this->setGridHight($height);

        $this->generateGrid($this->getGridWidth(), $this->getGridHeight());


    }

    /**
     * @param $width
     * @param $height
     */
    public function generateGrid($width, $height)
    {
        $widthArray = array_fill(0, $width, 0);
        $this->setGrid(array_fill(0, $height, $widthArray));
    }

    /**
     *
     */
    public function seed()
    {
        //  Seed data
        $seedLife = [];

        $targetLiveCells = round(((self::SEED_SIZE * self::SEED_SIZE) / 2), 0, PHP_ROUND_HALF_UP);

        //  Get inner boundry of grid.
        $half          = $this->getGridWidth() / 2;
        $halfMinusSeed = ($this->getGridWidth() - (self::SEED_SIZE * 2)) / 2;
        $min           = $half - $halfMinusSeed;
        $max           = $this->getGridWidth() - self::SEED_SIZE;

        $seedPointRow    = rand($min, $max);
        $seedPointColumn = rand($min, $max);
        $seedPoint = [
            'row'    => $seedPointRow,
            'column' => $seedPointColumn
        ];
        $seedDelta       = (self::SEED_SIZE - 1) / 2;


        $countRow = 0;
        $deltaRow = -($seedDelta);
        while ($countRow < self::SEED_SIZE) {
            $countColumn = 0;
            $deltaColumn = -($seedDelta);
            while ($countColumn < self::SEED_SIZE) {
                $seedLife['life'][] = [
                    'row'    => $seedPointRow + $deltaRow,
                    'column' => $seedPointColumn + $deltaColumn,
                    'data'   => rand(0, 1)
                ];
                $countColumn++;
                $deltaColumn++;
            }
            $countRow++;
            $deltaRow++;
        }

        $seedLife['seedPoint'] = $seedPoint;

        if (count($seedLife['life']) < $targetLiveCells) {
            $this->seed();
        }

        return $seedLife;
    }

    /**
     * @param $lifeData
     *
     * @return array
     */
    public function evolve($lifeData)
    {
        $evolvedLife = [];
        $grid = $this->sortCurrentGenerationData($lifeData);

        foreach ($grid as $row => $cell) {
            foreach ($cell as $column => $life) {
//                if ($grid[$row][$column] != 1) {
//                    continue;
//                }
                $neighborCount = $this->countNeighbors($row, $column);

                if ($this->fate($neighborCount, $this->getGrid()[$row][$column])  === true) {
                    $evolvedLife[] = [
                        'row'    => $row,
                        'column' => $column,
                        'data'   => 1
                    ];
                }
            }
        }

        return $evolvedLife;
    }

    public function fate($neighborCount, $limbo)
    {
        $hasLife = false;

        switch ($limbo) {
            case 0:
                if ($neighborCount == 3) {
                    $hasLife = true;
                }
                break;
            case 1:
                if ($neighborCount == 2 || $neighborCount == 3) {
                    $hasLife = true;
                }
                break;
            default:
                break;
        }

        return $hasLife;
    }

    public function sortCurrentGenerationData($currentGenerationData)
    {
        $theGrid = $this->getGrid();
        foreach ($currentGenerationData as $life) {
            $theGrid[$life['row']][$life['column']] = 1;
        }

        $this->setGrid($theGrid);
        return $theGrid;
    }

    /**
     *  'nw' => [-1, -1],
     *  'n'  => [-1, 0],
     *  'ne' => [-1, 1],
     *  'w'  => [0, -1],
     *  'e'  => [0, 1],
     *  'se' => [1, 1],
     *  's'  => [1, 0],
     *  'sw' => [1, -1],
     *
     * @param $row
     * @param $col
     *
     * @return int
     */
    public function countNeighbors($row, $col)
    {
        if ($row == 9 && $col == 9) {
            $x = null;
        }
        $hasNeighborCount = 0;
        $compass = [
            [               // NW
                'row' => -1,
                'col' => -1
            ],
            [               // N
                'row' => -1,
                'col' =>  0
            ],
            [               // NE
                'row' => -1,
                'col' =>  1
            ],
            [               // E
                'row' => 0,
                'col' => 1
            ],
            [               // SE
                'row' => 1,
                'col' => 1
            ],
            [               // S
                'row' => 1,
                'col' => 0
            ],
            [               // SW
                'row' =>  1,
                'col' => -1
            ],
            [               // W
                'row' =>  0,
                'col' => -1
            ],

        ];

        foreach ($compass as $direction) {
            $rowΔ = $direction['row'];
            $colΔ = $direction['col'];

            $rowIndex = $row + $rowΔ;
            $colIndex = $col + $colΔ;

            if (!isset($this->getGrid()[$rowIndex][$colIndex])) {
                continue;
            }

            if ($this->getGrid()[$rowIndex][$colIndex] == 1) {
                //var_dump(['VAL >' => $this->getGrid()[$rowIndex][$colIndex], 'ROW >' => $rowIndex, 'COL >' => $colIndex]);
                $hasNeighborCount++;
            }
        }

        return $hasNeighborCount;
    }

    /***************************************************************************
    ****    Ancilliary Methods
    ***************************************************************************/

    /**
     * @param $width
     * @param $height
     *
     * @return bool
     */
    public function checkForSquare($width, $height)
    {
        return $width == $height;
    }



    /***************************************************************************
     ****    Getters & Setters
     ***************************************************************************/



    /**
     * @return mixed
     */
    public function getGridWidth()
    {
        return $this->gridWidth;
    }

    /**
     * @param mixed $gridWidth
     */
    public function setGridWidth($gridWidth)
    {
        $this->gridWidth = $gridWidth;
    }

    /**
     * @return mixed
     */
    public function getGridHeight()
    {
        return $this->gridHeight;
    }

    /**
     * @param mixed $gridHeight
     */
    public function setGridHight($gridHeight)
    {
        $this->gridHeight = $gridHeight;
    }

    /**
     * @return mixed
     */
    public function getGrid()
    {
        return $this->grid;
    }

    /**
     * @param mixed $grid
     */
    public function setGrid($grid)
    {
        $this->grid = $grid;
    }

    /**
     * @return mixed
     */
    public function getGeneration()
    {
        return $this->generation;
    }

    /**
     * @param mixed $generation
     */
    public function setGeneration($generation)
    {
        $this->generation = $generation;
    }

    /**
     * @return mixed
     */
    public function getLife()
    {
        return $this->life;
    }

    /**
     * @param mixed $life
     */
    public function setLife($life)
    {
        $this->life = $life;
    }
}
