<?php

namespace App\Livewire\Server;

use Livewire\Component;

class Show extends Component
{
    public \App\Models\Server $server;

    public function mount(\App\Models\Server $server)
    {
        $this->server = $server;
    }

    public function render()
    {
        return view('livewire.server.show');
    }
}
