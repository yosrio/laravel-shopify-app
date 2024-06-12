<?php
/**
 * IndexController
 *
 * PHP version 8.2
 *
 * @package  App\Http\Controllers\Shopify\Home
 * @category Controllers
 * @license  http://opensource.org/licenses/MIT MIT License
 */
namespace App\Http\Controllers\Shopify\Home;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

/**
 * IndexController
 *
 * This controller home shopify app.
 */
class IndexController extends Controller
{
    /**
     * Method index
     *
     * @return void
     */
    public function index()
    {
        echo "home";
    }
}
