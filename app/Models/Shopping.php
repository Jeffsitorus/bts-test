<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shopping extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'name',
        'CreatedDate',
    ];
}
