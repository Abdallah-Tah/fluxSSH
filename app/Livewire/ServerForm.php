<?php

namespace App\Livewire;

use App\Models\Server;
use Illuminate\View\View;
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

    public bool $resetAfterSave = true;

    public function mount(?Server $server = null, bool $resetAfterSave = true): void
    {
        $this->resetAfterSave = $resetAfterSave;

        if ($server) {
            $this->server = $server;
            $this->fillFromServer($server);
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

        // Handle authentication credentials
        if ($this->auth_type === 'password') {
            // For new servers, password is required; for updates, only if provided
            if (! $this->server || $this->password) {
                $data['password'] = $this->password;
            }
            $data['private_key'] = null;
        } elseif ($this->auth_type === 'key') {
            // For new servers, private_key is required; for updates, only if provided
            if (! $this->server || $this->private_key) {
                $data['private_key'] = $this->private_key;
            }
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

        if ($this->resetAfterSave) {
            $this->reset();
        } elseif ($this->server) {
            $this->server->refresh();
            $this->fillFromServer($this->server);
        }
    }

    public function cancel(): void
    {
        $this->reset();
        $this->dispatch('serverSaved');
    }

    public function render(): View
    {
        return view('livewire.server-form');
    }

    private function fillFromServer(Server $server): void
    {
        $this->fill([
            'name' => $server->name,
            'host' => $server->host,
            'port' => $server->port,
            'username' => $server->username,
            'auth_type' => $server->auth_type,
            'is_active' => $server->is_active,
        ]);

        $this->password = '';
        $this->private_key = '';
    }
}
