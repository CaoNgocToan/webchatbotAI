<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use MongoDB\Laravel\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $connection = 'mongodb';
    protected $collection = 'topic';
    protected $fillable = [
        'ten_topic',
        'ten_khong_dau',
        // thêm các trường khác nếu có
    ];
}
