<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class node extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'node';
    protected $fillable = [
        //table tijd
        'question', 'answer', "id"
    ];

    //    public function bedrijf()
    //    {
    //        return $this->belongsTo(Bedrijf::class );
    //    }

    public function relation()
    {
         return $this->hasOne(relation::class, 'parent_node');
    }

//    public function yes()
//    {
//        return $this->hasOneThrough(node::class, relation::class, 'id', 'parent_node', null, 'node_yes');
//    }

}
