<?php

use App\Models\Recipe;
use App\Models\User;
use Laravel\Sanctum\Sanctum;

function createRecipeFor(User $user, array $attributes = []): Recipe
{
    return $user->recipes()->create(array_merge([
        'titulo' => 'Tortilla de patatas',
        'foto' => 'https://example.com/recipes/tortilla.jpg',
        'pasos' => 'Batir huevos, cocinar patatas y cuajar.',
        'time' => 25,
    ], $attributes));
}

test('guests can list recipes', function () {
    $owner = User::factory()->create();
    $recipe = createRecipeFor($owner);

    $response = $this->getJson('/api/recipes');

    $response
        ->assertOk()
        ->assertJsonFragment([
            'id' => $recipe->id,
            'titulo' => $recipe->titulo,
        ]);
});

test('guests can view recipe detail', function () {
    $owner = User::factory()->create();
    $recipe = createRecipeFor($owner);

    $response = $this->getJson("/api/recipes/{$recipe->id}");

    $response
        ->assertOk()
        ->assertJsonPath('data.id', $recipe->id)
        ->assertJsonPath('data.titulo', $recipe->titulo);
});

test('guests can search recipes', function () {
    $owner = User::factory()->create();

    $matchingRecipe = createRecipeFor($owner, [
        'titulo' => 'Tarta de manzana',
    ]);

    createRecipeFor($owner, [
        'titulo' => 'Sopa castellana',
    ]);

    $response = $this->postJson('/api/recipes/search', [
        'search' => ['value' => 'manzana'],
    ]);

    $response
        ->assertOk()
        ->assertJsonFragment([
            'id' => $matchingRecipe->id,
            'titulo' => $matchingRecipe->titulo,
        ])
        ->assertJsonMissing([
            'titulo' => 'Sopa castellana',
        ]);
});

test('authenticated users can view recipes owned by other users', function () {
    $owner = User::factory()->create();
    $viewer = User::factory()->create();
    $recipe = createRecipeFor($owner);

    Sanctum::actingAs($viewer);

    $response = $this->getJson("/api/recipes/{$recipe->id}");

    $response
        ->assertOk()
        ->assertJsonPath('data.id', $recipe->id);
});

test('guests cannot create recipes', function () {
    $response = $this->postJson('/api/recipes', [
        'titulo' => 'Gazpacho',
        'foto' => 'https://example.com/recipes/gazpacho.jpg',
        'pasos' => 'Triturarlo todo y enfriar.',
        'time' => 10,
    ]);

    $response->assertUnauthorized();
});

test('owners can create recipes', function () {
    $user = User::factory()->create();

    Sanctum::actingAs($user);

    $response = $this->postJson('/api/recipes', [
        'titulo' => 'Gazpacho',
        'foto' => 'https://example.com/recipes/gazpacho.jpg',
        'pasos' => 'Triturarlo todo y enfriar.',
        'time' => 10,
    ]);

    $response
        ->assertCreated()
        ->assertJsonPath('data.titulo', 'Gazpacho');

    $this->assertDatabaseHas('recipes', [
        'titulo' => 'Gazpacho',
        'user_id' => $user->id,
    ]);
});

test('owners can update their own recipes', function () {
    $owner = User::factory()->create();
    $recipe = createRecipeFor($owner);

    Sanctum::actingAs($owner);

    $response = $this->patchJson("/api/recipes/{$recipe->id}", [
        'titulo' => 'Tortilla jugosa',
    ]);

    $response
        ->assertOk()
        ->assertJsonPath('data.titulo', 'Tortilla jugosa');

    $this->assertDatabaseHas('recipes', [
        'id' => $recipe->id,
        'titulo' => 'Tortilla jugosa',
    ]);
});

test('users cannot update recipes from other owners', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();
    $recipe = createRecipeFor($owner);

    Sanctum::actingAs($otherUser);

    $response = $this->patchJson("/api/recipes/{$recipe->id}", [
        'titulo' => 'Intento ajeno',
    ]);

    $response->assertForbidden();

    $this->assertDatabaseMissing('recipes', [
        'id' => $recipe->id,
        'titulo' => 'Intento ajeno',
    ]);
});

test('guests cannot access my recipes', function () {
    $response = $this->getJson('/api/my-recipes');

    $response->assertUnauthorized();
});

test('my recipes only returns recipes from the authenticated user', function () {
    $owner = User::factory()->create();
    $otherUser = User::factory()->create();

    createRecipeFor($owner, ['titulo' => 'Croquetas']);
    createRecipeFor($owner, ['titulo' => 'Lentejas']);
    createRecipeFor($otherUser, ['titulo' => 'Paella ajena']);

    Sanctum::actingAs($owner);

    $response = $this->getJson('/api/my-recipes');

    $response
        ->assertOk()
        ->assertJsonFragment(['titulo' => 'Croquetas'])
        ->assertJsonFragment(['titulo' => 'Lentejas'])
        ->assertJsonMissing(['titulo' => 'Paella ajena']);
});
