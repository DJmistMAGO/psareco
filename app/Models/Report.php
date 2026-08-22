<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'report_type',
        'generated_by',
        'data_content',
    ];

    protected $dates = [
        'generated_date',
    ];

}
