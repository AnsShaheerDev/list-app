<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ListItem extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'lists';

    /* 
     * Allowing Mass Assignment
     */
    protected $guarded = [];

    /*
     * Extend Progress Col
     *
     */
    public function getProgressAttribute($value){
        if($this->cards()->count() > 0){
            return "{$value}%";
        }else{
            return "TBC";
        }
    }

    /*
     * Relationships
     */

    public function user()
    {
        return $this->hasOne(User::class);
    }

    public function cards()
    {
        return $this->hasMany(Card::class,'list_id');
    }
}
