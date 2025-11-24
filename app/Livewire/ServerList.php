<?php

namespace App\Livewire;

use App\Models\Server;
use App\Services\SSHService;
use Livewire\Component;
use Livewire\WithPagination;

class ServerList extends Component
{
    use WithPagination;

    public $search = '';
    public $showForm = false;
    public $editingServer = null;

    protected $listeners = [
        'serverSaved' => 'refreshServers',
        'serverDeleted' => 'refreshServers'
    ];

    public function updatedSearch(): void
    {
        $this->resetPage();
    }

    public function addServer(): void
    {
        $this->editingServer = null;
        $this->showForm = true;
    }

    public function editServer(Server $server): void
    {
        $this->editingServer = $server;
        $this->showForm = true;
    }

    public function deleteServer(Server $server): void
    {
        $server->delete();
        $this->dispatch('serverDeleted');
        session()->flash('message', "Server '{$server->name}' deleted successfully!");
    }

    public function testConnection(Server $server): void
    {
        $sshService = app(SSHService::class);
        $result = $sshService->testConnection($server);

        if ($result['success']) {
            session()->flash('message', "Connection to '{$server->name}' successful!");
        } else {
            session()->flash('error', "Connection failed: " . $result['message']);
        }
    }

    public function connectToServer(Server $server)
    {
        return redirect()->route('console', ['server' => $server->id]);
    }

    public function closeForm(): void
    {
        $this->showForm = false;
        $this->editingServer = null;
    }

    public function refreshServers(): void
    {
        $this->closeForm();
    }

    public function render()
    {
        $servers = Server::query()
            ->when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('host', 'like', '%' . $this->search . '%')
                    ->orWhere('username', 'like', '%' . $this->search . '%');
            })
            ->where('is_active', true)
            ->latest()
            ->paginate(10);

        return view('livewire.server-list', [
            'servers' => $servers
        ]);
    }
}
