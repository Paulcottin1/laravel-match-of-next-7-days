<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odd extends Model
{
    use HasFactory;

    /**
     * @var string[]
     */
    protected $type = [
        1 => 'Match Winner'
    ];

    /**
     * @var string[]
     */
    protected $side = [
        1 => 'home',
        2 => 'away',
        3 => 'draw'
    ];

    /**
     * @var string
     */
    protected $table = 'odds';
    /**
     * @var string[]
     */
    protected $fillable = ['odd', 'type', 'side'];

    public function match()
    {
        return $this->belongsTo(Match::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
