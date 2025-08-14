<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $fillable = [
        'report_category',
        'report_name',
        'report_description',
        'report_fields',
        'report_view',
    ];
}
