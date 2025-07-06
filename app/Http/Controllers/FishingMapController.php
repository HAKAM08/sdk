<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FishingMapController extends Controller
{
    /**
     * Display the fishing map and weather information page.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // You'll need to get an API key from OpenWeatherMap and Google Maps
        // For now, we'll pass placeholder values to the view
        $openWeatherMapApiKey = config('8bdb2b7b1fd2d6e8f312f9bee49bd563 ');
        $googleMapsApiKey = config('AIzaSyCQtivlEEdJjhjq_F4_VGL6uuCmby5qXCo');
        
        return view('fishing-map.index', compact('openWeatherMapApiKey', 'googleMapsApiKey'));
    }
}