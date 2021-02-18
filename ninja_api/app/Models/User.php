<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        // 'email',
        // 'password',
        'avatar_file_original_name',
        'gender',
        'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    // protected $hidden = [
    //     'password',
    //     'remember_token',
    // ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    protected $appends = ['avatar_url'];

    public function getAvatarUrlAttribute(){
        return empty($this->avatar) ? null : asset('images/avatar/'.$this->avatar);
    }

    // public function getAvatarAttribute($value){
    //     if(empty($value)){
    //         return null;
    //     }

    //     // dd($value);
    //     return [
    //         "fileName" => $value,
    //         "url" => asset('images/avatar/'.$value)
    //     ];
    // }
}
