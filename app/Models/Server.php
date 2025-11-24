<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Crypt;

class Server extends Model
{
    use HasFactory;
    protected $fillable = [
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
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'connection_options' => 'array',
            'last_connected_at' => 'datetime',
        ];
    }

    // Encrypt/decrypt private key
    protected function privateKey(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn(?string $value) => $value ? Crypt::encryptString($value) : null,
        );
    }

    // Encrypt/decrypt password
    protected function password(): Attribute
    {
        return Attribute::make(
            get: fn(?string $value) => $value ? Crypt::decryptString($value) : null,
            set: fn(?string $value) => $value ? Crypt::encryptString($value) : null,
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
}
