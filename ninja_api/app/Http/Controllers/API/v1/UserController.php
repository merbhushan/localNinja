<?php

namespace App\Http\Controllers\API\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;

use Validator;

class UserController extends Controller
{
    public function index(Request $request){
        $perPage = $request->per_page ? $request->per_page : 20;

        $query = User::select('id', 'name', 'avatar', 'gender')
            ->when($request->filter, function ($query, $keyword) {
                return $query->where('name', 'LIKE', '%'. $keyword .'%')
                    ->orWhere('id', 'LIKE', '%'. $keyword .'%');
            })
            ->when(json_decode($request->sortBy), function ($query, $sortBy) {
                return $query->orderBy($sortBy->sortBy, $sortBy->descending ? 'desc' : 'asc');
            });

        return $query->paginate($perPage);
    }

    public function store(Request $request){
        $objValidatedData   =   Validator::make($request->all(), [
            'name' => 'required|max:255',
            'gender' => 'required|in:Male,Female',
            'avatar' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:4096',
        ]);

        if ($objValidatedData->fails()){
            return $objValidatedData->errors();
        }

        $strAvatarImageName = time().'.'.$request->avatar->getClientOriginalExtension(); // Might fail when there are multiple user created consequently.
        $request->file('avatar')->move('images/avatar/', $strAvatarImageName);

        return User::create([
            'name' => $request->name,
            'avatar_file_original_name' => $request->avatar->getClientOriginalName(),
            'gender' => $request->gender,
            'avatar' => $strAvatarImageName
        ]);
    }

    public function delete(User $user){
        if($user->delete())
        {
            return [
                "message"=> "User deleted successfully",
                "code"=> 1
            ];
        }

        return [
            "message"=> "Unexpected error occurred while deleting a user.",
            "code"=> 0
        ];
    }
}
