<?php

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

uses(DuskTestCase::class);

it('logs user in', function () {
    $admin = User::find(1);

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin)
            ->visit('/admin')
            ->assertPathIs('/admin');

    });
});
