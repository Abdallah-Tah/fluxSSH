<?php

use App\Livewire\QuickConnect;
use Livewire\Livewire;

it('renders successfully', function () {
    Livewire::test(QuickConnect::class)
        ->assertStatus(200);
});
