<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Match;
use App\Models\Odd;

class Team extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'teams';
    /**
     * @var string[]
     */
    protected $fillable = ['name'];

    public function matches()
    {
        return $this->belongsToMany(Match::class);
    }

    public function odds()
    {
        return $this->hasMany(Odd::class);
    }
}
