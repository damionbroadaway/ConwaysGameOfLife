<?php
/**
 * File yourMomController.php
 *
 * @package App\Http\Controllers
 */

namespace App\Http\Controllers;

use App\Life;

/**
 * [Class desc. text goes here...]
 *
 * @package App\Http\Controllers
 * @author  Damion M Broadaway <dbroadaw@nerdery.com>
 */
class golController extends Controller
{
    private $life;

    public function index()
    {
        return view('welcome');
    }

    public function apiIndex()
    {
        $life = null;

        $generation = (int) $_POST['generation'];
        $width      = $_POST['width'];
        $height     = $_POST['height'];

        $lifeBuilder = new Life($width, $height);

        if ($generation > 0 && isset($_POST['currentGeneration'])) {
            $currentGeneration = $_POST['currentGeneration'];
            $life = $lifeBuilder->evolve($currentGeneration);
        }


        if ($generation == 0) {
            $life = $lifeBuilder->seed();
        }


        return response()->json(
            [
                'success' => true,
                'data' => [
                    'generation' => $generation + 1,
                    'width'      => $width,
                    'height'     => $height,
                    'life'       => $life
                ]
            ]
        );
    }

    /***************************************************************************
    ****    Getters & Setters
    ***************************************************************************/

    /**
     * @return mixed
     */
    private function getLife()
    {
        return $this->life;
    }

    /**
     * @param mixed $life
     */
    private function setLife($life)
    {
        $this->life = $life;
    }
}
