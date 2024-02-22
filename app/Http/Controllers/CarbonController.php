<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;

class CarbonController extends Controller
{
    public function fecha()
    {
        $fecha = Carbon::now()->timeZone('America/Lima')->format('Ymd H:i:m');

        $year = Carbon::now()->month;

        return $year;
    }

}
