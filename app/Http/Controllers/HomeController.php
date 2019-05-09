<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function search(Request $request) {
        $http = new \GuzzleHttp\Client();

        try {
            $response = $http->post('https://api.aliseeks.com/v1/search', [
                'headers' => [
                    'X-Api-Client-Id' => 'NBZQPQKVWNNXAKZQ',
                    'Content-Type' => 'application/json'
                ],
                'json' => ['text' => $request['query']]
            ]);
            $json = (string) $response->getBody();
            $contents = json_decode($json);
            $items = array();
            foreach($contents->items as $item) {
                array_push($items,[
                    'name' => $item->title,
                    'img_url' => $item->imageUrl,
                    'price' => $item->price->value . ' ' . $item->price->currency,
                    'value' => (int)$item->price->value
                ]);                
            }
            $collection = collect($items);
            $sorted = $collection->sortByDesc('value');
            $items = $sorted->values()->all();
            if(!File::exists(public_path('query.json'))) {
                File::put(public_path('query.json'),json_encode($items));
            } else {
                File::append(public_path('query.json'),json_encode($items));
            }

            return $items;

        } catch (\GuzzleHttp\Exception\BadResponseException $e) {

            if ($e->getCode() == 400) {
                return response()->json('Invalid Request', $e->getCode());
            } else if ($e->GetCode() == 401) {
                return response()->json('Incorrect Credentials', $e->getCode());
            }

            return response()->json('Something went wrong on the server.', $e->getCode());
        }
    }
}
