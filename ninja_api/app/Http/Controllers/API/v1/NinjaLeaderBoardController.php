<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Score;

class NinjaLeaderBoardController extends Controller
{
    public function index(Request $request){
        return  Score::orderBy('score', 'desc')
            ->select('id', 'user_id', 'score')
            ->with('user:id,name')
            ->limit(50)
            ->get();
    }
}
