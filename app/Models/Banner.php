<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'image_path', 'start_date', 'end_date', 'active'
    ];

    protected $dates = ['start_date', 'end_date'];

    public function getStartDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function getEndDateAttribute($value)
    {
        return Carbon::parse($value)->format('Y-m-d');
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::createFromFormat('Y-m-d', $value);
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::createFromFormat('Y-m-d', $value);
    }
}


