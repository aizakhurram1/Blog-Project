<?php

use App\Models\Category;
use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

uses(DuskTestCase::class);

it('shows if the categories page is opened', function () {
    $admin = User::find(1);

    $this->browse(function (Browser $browser) use ($admin) {
        $browser->loginAs($admin)
            ->visit('/admin/categories')
            ->assertPathIs('/admin/categories');
        $browser->screenshot('category-page');
    });
});
it('shows the category title exists on the page', function () {
    $this->browse(function (Browser $browser) {
        $admin = User::find(1);
        $browser->loginAs($admin)
            ->visit('/admin/categories')
            ->waitFor('table', 10)
            ->assertSee('JavaScript')
            ->assertSee('Laravel')
            ->assertSee('test');
    });

});
it('does not show non existing category', function () {
    $this->browse(function (Browser $browser) {
        $admin = User::find(1);
        $browser->loginAs($admin)
            ->visit('/admin/categories')
            ->waitFor('table', 10)
            ->assertDontSee('PHP');

    });

});
it('matches exact category name from database and page', function () {
    $this->browse(function (Browser $browser) {
        $admin = User::find(1);
        $expected = Category::where('title', 'JavaScript')->first()?->title;

        $browser->loginAs($admin)
            ->visit('/admin/categories')
            ->waitFor('table', 10);

        $actual = $browser->text('td.filament-tables-cell.filament-table-cell-title');

        expect($actual)->toContain($expected);
    });
});
