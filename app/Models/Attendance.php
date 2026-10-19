<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = ['user_id', 'date', 'check_in', 'check_out', 'status', 'notes'];

    protected $casts = [
        'date' => 'date',
        'check_in'=> 'datetime',
        'check_out' => 'datetime',
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }
}
