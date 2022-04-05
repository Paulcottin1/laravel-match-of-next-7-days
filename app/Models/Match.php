<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Team;

class Match extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'matches';
    /**
     * @var string[]
     */
    protected $fillable = ['season', 'league', 'matchDate'];

    public function odds()
    {
        return $this->hasMany(Odd::class);
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class);
    }
}
