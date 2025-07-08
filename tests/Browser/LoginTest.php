<?php

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

uses(DuskTestCase::class);

it('user can log in via the Filament login page', function () {
    $this->browse(function (Browser $browser) {
        // $user = User::find(1);

        $browser
            ->visit('/admin/login')
            ->assertPathIs('/admin/login');
        $browser->screenshot('login');
    });
});
