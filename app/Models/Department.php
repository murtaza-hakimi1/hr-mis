<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $fillable = [
            'name',
            'description',
            'manager_id',
    ]; 

    public function manager() {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function employees() {
        return $this->hasMany(User::class,);
    }

    public function positions() {
        return $this->hasMany(Position::class,);
    }


}
