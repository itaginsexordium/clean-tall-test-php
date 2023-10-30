<?php

namespace App\Http\Controllers;

use App\Http\Requests\HomeRequest;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index(HomeRequest $request)
    {
        $data = [];

        if (!empty($request->ip)) {
            $data = DB::select("SELECT DISTINCT  idv.ip_address, idv.subnet_mask, g.country_name AS country
            FROM ip_data_v4 AS idv 
            JOIN geonames AS g ON idv.geoname_id = g.geoname_id
            WHERE idv.network_as_number <= INET_ATON('$request->ip')
            ORDER BY idv.subnet_mask ASC
            LIMIT 50;");
        } else {
            $data = DB::select("SELECT DISTINCT  idv.ip_address, idv.subnet_mask, g.country_name AS country
            FROM ip_data_v4 AS idv 
            JOIN geonames AS g ON idv.geoname_id = g.geoname_id
            ORDER BY idv.subnet_mask ASC");
        }

        if (!empty($request->as_json)) {
            return response()->json($data);
        } else {
            return view('welcome', [
                'ips' => $data,
            ]);
        }
    }
}
