<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;

uses(RefreshDatabase::class);

beforeEach(function () {
    // Create an authenticated user for all tests
    $user = User::factory()->create();
    Sanctum::actingAs($user);
});