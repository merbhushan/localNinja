<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Score extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getScoreAttribute($value)
    {
        $seconds = $value/1000;
        $fraction = ($value%1000)/10;
        $minute = $seconds/60;
        $second = $seconds%60;

        return sprintf('%02d:%02d.%02d', $minute, $second, $fraction);
    }
}
