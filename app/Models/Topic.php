<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $table = 'topic';
}
