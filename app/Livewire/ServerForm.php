<?php

namespace App\Livewire;

use App\Models\Server;
use Livewire\Attributes\Validate;
use Livewire\Component;

class ServerForm extends Component
{
    public ?Server $server = null;

    #[Validate('required|string|max:255')]
    public ?string $name = '';

    #[Validate('required|string|max:255')]
    public ?string $host = '';

    #[Validate('required|integer|min:1|max:65535')]
    public ?int $port = 22;

    #[Validate('required|string|max:255')]
    public ?string $username = '';

    #[Validate('required|in:password,key')]
    public ?string $auth_type = 'password';

    #[Validate('nullable|string')]
    public ?string $password = '';

    #[Validate('nullable|string')]
    public ?string $private_key = '';

    public ?bool $is_active = true;

    public function mount(?Server $server = null): void
    {
        if ($server) {
            $this->server = $server;
            $this->fill([
                'name' => $server->name,
                'host' => $server->host,
                'port' => $server->port,
                'username' => $server->username,
                'auth_type' => $server->auth_type,
                'is_active' => $server->is_active,
            ]);

            // Don't populate sensitive data for security
            $this->password = '';
            $this->private_key = '';
        }
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'host' => $this->host,
            'port' => $this->port,
            'username' => $this->username,
            'auth_type' => $this->auth_type,
            'is_active' => $this->is_active,
        ];

        // Only update password/key if provided
        if ($this->auth_type === 'password' && $this->password) {
            $data['password'] = $this->password;
            $data['private_key'] = null;
        } elseif ($this->auth_type === 'key' && $this->private_key) {
            $data['private_key'] = $this->private_key;
            $data['password'] = null;
        }

        if ($this->server) {
            $this->server->update($data);
            $message = "Server '{$this->name}' updated successfully!";
        } else {
            $data['user_id'] = auth()->id();
            Server::create($data);
            $message = "Server '{$this->name}' created successfully!";
        }

        session()->flash('message', $message);
        $this->dispatch('serverSaved');
        $this->reset();
    }

    public function cancel(): void
    {
        $this->reset();
        $this->dispatch('serverSaved');
    }

    public function render()
    {
        return view('livewire.server-form');
    }
}
