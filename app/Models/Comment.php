<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $table = 'comments';

    protected $fillable = [
        'author_id',
        'record_id',
        'comment_text',
        'is_active'
    ];

    public function author() {
        return $this->belongsTo('App\Models\User');
    }

    public function record() {
        return $this->belongsTo('App\Models\Record');
    }
}
