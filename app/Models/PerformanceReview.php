<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PerformanceReview extends Model
{
    protected $fillable = [
        'user_id', 'review_id', 'review_period', 'quality_of_work', 'productivity', 'communication', 'teamwork', 'leadership', 'overall_rating',
        'strengths','areas_for_improvement','goals','comments',
    ];


    protected $casts = [
        'overall_rating' => 'decimal:2',
    ];
    
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function reviewer(){
    return $this->belongsTo(User::class, 'review_id');
    }


}
