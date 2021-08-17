<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @mixin Eloquent
 */
class Message extends Model
{
    use HasFactory;

    protected $fillable = ['from', 'to', 'message', 'deleted_by_sender', 'deleted_by_receiver'];

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'from');
    }
}
