<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class relation extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'node_relation';
    protected $fillable = [
        //table tijd
        'node_yes', 'node_no', 'parent_node',
    ];

//    public function bedrijf()
//    {
//        return $this->belongsTo(Bedrijf::class );
//    }

    public function node()
    {
        return $this->belongsTo(node::class );
    }

    public function yes()
    {
        return $this->belongsTo(node::class, 'node_yes');
    }

    public function no()
    {
        return $this->belongsTo(node::class, 'node_no');
    }
}
