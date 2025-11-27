<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'host',
        'port',
        'username',
        'auth_type',
        'private_key',
        'password',
        'is_active',
        'connection_options',
        'last_connected_at',
        'server_details',
        'cpu_usage',
        'memory_usage',
        'disk_usage',
        'os_info',
        'kernel_version',
        'uptime',
        'last_detail_fetch_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'connection_options' => 'array',
            'last_connected_at' => 'datetime',
            'server_details' => 'array',
            'cpu_usage' => 'decimal:2',
            'last_detail_fetch_at' => 'datetime',
        ];
    }

    // Encrypt/decrypt private key
    protected function privateKey(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn (?string $value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    // Store password as plain text (no encryption)
    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn (?string $value) => $value,
            set: fn (?string $value) => $value,
        );
    }

    public function getConnectionString(): string
    {
        return "{$this->username}@{$this->host}:{$this->port}";
    }

    public function isKeyAuth(): bool
    {
        return $this->auth_type === 'key';
    }

    public function isPasswordAuth(): bool
    {
        return $this->auth_type === 'password';
    }

    public function commandHistories(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(CommandHistory::class);
    }

    public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
