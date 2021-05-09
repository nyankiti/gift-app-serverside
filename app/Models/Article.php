<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory;
        /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    // table名を指定
    protected $table = 'articles';

    // カラムを指定
    protected $fillable = [
        'id', 'title', 'author', 'imageUrl', 'html', 'created_at', 'updated_at'
    ];
}
