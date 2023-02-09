<?php

namespace App\Http\Controllers;

use App\Http\Resources\GovernorateResource;
use App\Models\City;
use App\Models\Governorate;
use Illuminate\Http\Request;

class CommonController extends Controller
{
    public function governorates() {
        return GovernorateResource::collection(Governorate::all());
    }

    public function cities(Request $request) {
        return City::where('governorate_id', request('governorate_id'))->get();
    }
}
