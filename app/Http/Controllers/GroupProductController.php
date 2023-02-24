<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Group;

class GroupProductController extends Controller
{
    public function show(Group $group) {
        return ProductResource::collection($group->products()->paginate(1));
    }

    public function edit(Group $group) {
        return $group;
    }
}
