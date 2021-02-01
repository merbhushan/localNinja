<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request){
        $perPage = $request->per_page ? $request->per_page : 20;

        $query = User::select('id', 'name')
            ->when($request->filter, function ($query, $keyword) {
                return $query->where('name', 'LIKE', '%'. $keyword .'%')
                    ->orWhere('id', 'LIKE', '%'. $keyword .'%');
            })
            ->when(json_decode($request->sortBy), function ($query, $sortBy) {
                return $query->orderBy($sortBy->sortBy, $sortBy->descending ? 'desc' : 'asc');
            });

        return $query->paginate($perPage);
    }
}
