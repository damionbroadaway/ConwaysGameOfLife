<?php
/**
 * File Life.php
 *
 * @package App
 */

namespace App;

/**
 * Kind of a model but not really.
 * Holds the FABRIC OF LIFE!
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
    const SEED_SIZE = 11;

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
        //  Make sure we are using a square.
        if (!$this->checkForSquare($width, $height)) {
            return false;
        }

        //  Set grid dimensions.
        $this->setGridWidth($width);
        $this->setGridHight($height);

        //  Pad an empty grid.
        $this->generateGrid($this->getGridWidth(), $this->getGridHeight());
    }

    /**
     * Fills grid for reference.
     *
     * @param $width
     * @param $height
     */
    public function generateGrid($width, $height)
    {
        $widthArray = array_fill(0, $width, 0);
        $this->setGrid(array_fill(0, $height, $widthArray));
    }

    /**
     * First generation of data points.
     * Random seed.
     */
    public function seed()
    {
        //  Seed data
        $seedLife = [];

        //  Arbitrary % for number of seed cells.
        //  Currently set to be 50%.
        //  Testing indicates less than this will have trouble evolving.
        $targetLiveCells = round(((self::SEED_SIZE * self::SEED_SIZE) / 2), 0, PHP_ROUND_HALF_UP);

        //  Get inner boundry of grid.
        //  Not really required but I didn't want to
        //      check for seeding out of bounds.

        //  Split available space in twain.
        $half          = $this->getGridWidth() / 2;
        //  Remove the outermost cells to account for seed size.
        $halfMinusSeed = ($this->getGridWidth() - (self::SEED_SIZE * 2)) / 2;
        //  The lowest cell available.
        $min           = $half - $halfMinusSeed;
        //  The highest cell available
        $max           = $this->getGridWidth() - self::SEED_SIZE;

        //  Call in Rando Calrissian
        $seedPointRow    = rand($min, $max);
        $seedPointColumn = rand($min, $max);
        //  We now have an 'Eden' point.
        $seedPoint = [
            'row'    => $seedPointRow,
            'column' => $seedPointColumn
        ];
        //  How far around Eden do we seed?
        $seedDelta       = (self::SEED_SIZE - 1) / 2;

        //  Navigate seed size boundries.
        //  Randomly decide life or death.
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

        //  Ensures Rando provided enough life.
        if (count($seedLife['life']) < $targetLiveCells) {
            $this->seed();
        }

        return $seedLife;
    }

    /**
     * If we are past the first generation we must evolve.
     *
     * @param $lifeData
     *
     * @return array
     */
    public function evolve($lifeData)
    {
        $evolvedLife = [];
        //  Take current generation and put them on the grid.
        $grid = $this->sortCurrentGenerationData($lifeData);

        /**
         *      Performance Gainz:
         *          Part I
         *          @see Part II @ ~ line 177 in this method
         */
        $rowMin = $lifeData[0]['row'] - 1;
        $rowMax = $lifeData[(count($lifeData) - 1)]['row'] + 1;

        //  Starting as 0,0 we check every cell for life.
        //  We also count their neighbors since that's how one evolves.
        foreach ($grid as $row => $cell) {

            //  Performance Gainz:
            //      Part II
            //      By setting an upper and lower boundry on
            //      what to check performance was increased
            //      from ~200-300ms to ~50ms
            if ($row < $rowMin) {
                continue;
            } elseif ($row > $rowMax) {
                break;
            }
            foreach ($cell as $column => $life) {
                //  What is around this data point on the grid?!
                $neighborCount = $this->countNeighbors($row, $column);
                //  Apply Conway's Game of Life rules.
                //  If it lives we add it to the dataset to get passed back.
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

    /**
     *  For a space that is 'populated':
     *      Each cell with one or no neighbors dies, as if by solitude.
     *      Each cell with four or more neighbors dies, as if by overpopulation.
     *      Each cell with two or three neighbors survives.
     *  For a space that is 'empty' or 'unpopulated'
     *      Each cell with three neighbors becomes populated.
     *
     * @param $neighborCount
     * @param $limbo
     *
     * @return bool
     */
    public function fate($neighborCount, $limbo)
    {
        //  Life is the anomoly, right?
        $hasLife = false;

        //  SWITCHES ARE A VIABLE LOGIC STRUCTURE!
        //  There are two sets of rules.
        //  One for living and one for dead.
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

    /**
     * Current living cells are passed to API.
     * This puts them on our analog of the grid.
     *
     * @param $currentGenerationData
     *
     * @return mixed
     */
    public function sortCurrentGenerationData($currentGenerationData)
    {
        $theGrid = $this->getGrid();
        foreach ($currentGenerationData as $life) {
            //  The padded grid from the contructor is filled with 0.
            //  We update them to 1 to show they are living.
            $theGrid[$life['row']][$life['column']] = 1;
        }

        //  Why would we set the class parameter and return?
        $this->setGrid($theGrid);
        return $theGrid;
    }



    /**
     * For any given data point we must look at every cell adjacent.
     * Using IRL directions to help mentally map the data.
     * Assume N is up.
     *
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
        //  Assume single.
        //      Wanna talk about my cats?
        $hasNeighborCount = 0;

        //  Dataset of the change required to get to adjacent cells.
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

        //  Loop through compass deltas
        foreach ($compass as $direction) {
            $rowΔ = $direction['row'];
            $colΔ = $direction['col'];

            //  Add delta to current index to get cell location to check.
            $rowIndex = $row + $rowΔ;
            $colIndex = $col + $colΔ;

            //  General safety check but mostly for out of bounds.
            if (!isset($this->getGrid()[$rowIndex][$colIndex])) {
                continue;
            }

            //  If getGrid() has a 1 that means it was part
            //      of the passed in dataset of living cells.
            if ($this->getGrid()[$rowIndex][$colIndex] == 1) {
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
