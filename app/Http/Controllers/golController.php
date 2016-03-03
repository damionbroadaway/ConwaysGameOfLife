<?php
/**
 * File golController.php
 *
 * @package App\Http\Controllers
 */

namespace App\Http\Controllers;

use App\Life;

/**
 * Controller for web and API.
 *
 * @package App\Http\Controllers
 * @author  Damion M Broadaway <dbroadaw@nerdery.com>
 */
class golController extends Controller
{
    /**
     * @var Life
     */
    private $life;

    /**
     * Web index. Shows the client side.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        //  Totes not using the default Laravel 'Welcome' blade.
        return view('welcome');
    }

    /**
     * One and only API endpoint.
     *
     * TODO: Make more endpoints
     *      ex: /seed /evolve /grid
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiIndex()
    {
        $life = null;

        //  Get POST
        //  No, we're not checking isset().
        $generation = (int) $_POST['generation'];
        $width      = $_POST['width'];
        $height     = $_POST['height'];

        //  Really should be called LifeBuilder.
        $this->setLife(new Life($width, $height));

        //  Evolve if we are past the first generation.
        //      aka: SEED
        if ($generation > 0 && isset($_POST['currentGeneration'])) {
            $currentGeneration = $_POST['currentGeneration'];
            $life = $this->getLife()->evolve($currentGeneration);
        }

        //  Seed if it's the first generation.
        if ($generation == 0) {
            $life = $this->getLife()->seed();
        }

        //  Return a well formatted JSON reponse.
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
     * TODO: Take the method's advice.
     *
     * @return Life
     */
    private function getLife()
    {
        return $this->life;
    }

    /**
     * @param Life $life
     */
    private function setLife(Life $life)
    {
        $this->life = $life;
    }
}
