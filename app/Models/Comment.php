<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    use HasFactory;

    /**
     * @var string
     */
    protected $table = 'comments';

    /**
     * @var string[]
     */
    protected $fillable = [
        'author_id',
        'record_id',
        'comment_text',
        'is_active'
    ];

    /**
     * @return BelongsTo
     */
    public function author() {
        return $this->belongsTo('App\Models\User');
    }

    /**
     * @return BelongsTo
     */
    public function record() {
        return $this->belongsTo('App\Models\Record');
    }
}
