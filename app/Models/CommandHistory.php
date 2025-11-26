<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommandHistory extends Model
{
    /** @use HasFactory<\Database\Factories\CommandHistoryFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'server_id',
        'command',
        'current_directory',
        'execution_time',
    ];

    protected function casts(): array
    {
        return [
            'execution_time' => 'decimal:3',
        ];
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function server(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Server::class);
    }
}
