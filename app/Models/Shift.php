<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Shift extends Model
{
    use HasFactory;

    protected $table = 'shift_codes';

    protected $fillable = ['code', 'start_time', 'end_time'];
}