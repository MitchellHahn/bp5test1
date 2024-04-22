<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
class History extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'node_history';
    protected $fillable = [
        //table tijd
        'datum', 'node', "id", "parent_node"
    ];

    //    public function bedrijf()
    //    {
    //        return $this->belongsTo(Bedrijf::class );
    //    }

    //public function relation()
    //{
      //  return $this->hasOne(relation::class, 'parent_node');
    //}

//    public function yes()
//    {
//        return $this->hasOneThrough(node::class, relation::class, 'id', 'parent_node', null, 'node_yes');
//    }

}
