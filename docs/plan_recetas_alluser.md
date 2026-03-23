# Acceso publico al recetario y correccion del detalle de recetas

## Resumen

El objetivo es corregir el acceso al recetario para que:

- un invitado pueda listar recetas, buscar y abrir su detalle
- un usuario autenticado pueda seguir creando recetas
- solo el propietario pueda editar o borrar su receta
- `my-recipes` siga mostrando unicamente las recetas del usuario logueado

Este cambio no introduce todavia estados como `published`, `draft` o `visibility`. En el estado actual del proyecto, "recetas publicadas" debe entenderse como "todas las recetas existentes".

## Problema real detectado

El fallo principal no esta en el listado, sino en el detalle de receta.

Ahora mismo el backend bloquea `GET /api/recipes/{recipe}` cuando la receta no pertenece al usuario autenticado. Eso provoca que un usuario nuevo, o cualquier usuario intentando abrir una receta ajena, termine recibiendo un `403`.

El origen tecnico del problema es `RecipePolicy@view`. La operacion `show` de Orion termina usando ese metodo, y ahora mismo esta definido en modo owner-only. Como resultado, el detalle de una receta queda tratado como si fuera un recurso privado, cuando funcionalmente el recetario deberia ser visible.

Ademas, el recurso `recipes` entero sigue metido en `auth:sanctum`, asi que un invitado tampoco puede listar, buscar ni abrir recetas.

Por eso hay dos necesidades juntas:

- corregir el bug real del detalle
- abrir la lectura publica del recetario

## Estado actual del codigo

### Rutas actuales

```php
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/me', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthTokenController::class, 'logout']);
    Route::get('/my-recipes', [AuthTokenController::class, 'myRecipes']);
});

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Orion::resource('recipes', RecipeController::class)->withoutBatch();
});
```

### RecipePolicy actual

```php
class RecipePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Recipe $recipe): bool
    {
        return $recipe->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Recipe $recipe): bool
    {
        return $recipe->user_id === $user->id;
    }

    public function delete(User $user, Recipe $recipe): bool
    {
        return $recipe->user_id === $user->id;
    }
}
```

## Que esta fallando y por que

`GET /api/recipes/{recipe}` cae en `view()`. Como `view()` exige que `user_id` coincida con el usuario autenticado, cualquier receta ajena termina bloqueada.

El fallo se resume asi:

- `index` no es el bug principal
- `show` si es el bug principal
- `view()` esta modelado como privado por propietario
- el producto quiere que el recetario sea visible

Y, por separado, sigue existiendo una limitacion funcional adicional:

- todo `recipes` esta bajo `auth:sanctum`
- un invitado no puede ver ni listado ni detalle ni filtros

## Aclaraciones tecnicas importantes

### Orion y el tercer argumento `options`

En esta base de codigo, `Orion::resource()` si acepta un tercer argumento `array $options = []`.

Por tanto, una llamada como esta es valida:

```php
Orion::resource('recipes', RecipeController::class, [
    'only' => ['index', 'show', 'search'],
]);
```

### `only` y `except` si se aplican

Orion reutiliza el registrador de recursos de Laravel para determinar que acciones registrar. Eso significa que `only` y `except` si se procesan y no son decorativos.

Por tanto, la propuesta puede apoyarse legitimamente en `only` para separar lectura y escritura.

### Doble registro del recurso `recipes`

Registrar `recipes` dos veces no es correcto si ambas llamadas intentan registrar las mismas acciones, porque ahi si habria rutas duplicadas o nombres en conflicto.

Pero si cada registro usa conjuntos de acciones disjuntos, el enfoque es viable. En este caso la separacion propuesta es:

- recurso publico: `index`, `show`, `search`
- recurso autenticado: `store`, `update`, `destroy`

Mientras no haya solape, la propuesta sigue siendo valida.

### Policies y acceso guest

En esta version de Laravel, una policy puede aceptar invitados si el primer parametro del metodo permite `null`.

Por eso esta firma si habilita acceso guest:

```php
public function view(?User $user, Recipe $recipe): bool
```

No hace falta introducir aqui `allowGuests()` ni un cambio adicional en `AuthServiceProvider` para este caso concreto.

## Propuesta de cambio unificada

La propuesta recomendada es mantener Orion, pero separar claramente la lectura publica de la escritura autenticada.

El comportamiento final quedaria asi:

### Lectura publica

- `GET /api/recipes`
- `GET /api/recipes/{recipe}`
- `POST /api/recipes/search`

### Zona privada autenticada

- `GET /api/my-recipes`
- `POST /api/recipes`
- `PUT/PATCH /api/recipes/{recipe}`
- `DELETE /api/recipes/{recipe}`

### Reglas funcionales

- cualquier invitado puede ver el recetario
- cualquier usuario autenticado puede ver cualquier receta
- solo un usuario autenticado puede crear recetas
- solo el propietario puede editar o borrar su receta
- `my-recipes` solo devuelve recetas del usuario logueado

## Codigo actual vs codigo corregido

### 1. Rutas

#### Codigo actual

```php
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/me', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthTokenController::class, 'logout']);
    Route::get('/my-recipes', [AuthTokenController::class, 'myRecipes']);
});

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Orion::resource('recipes', RecipeController::class)->withoutBatch();
});
```

#### Codigo propuesto

```php
Route::middleware(['throttle:api'])->group(function () {
    Orion::resource('recipes', RecipeController::class, [
        'only' => ['index', 'show', 'search'],
        'except' => ['batchStore', 'batchUpdate', 'batchDestroy', 'batchRestore', 'restore'],
    ]);
});

Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::get('/me', fn(Request $request) => $request->user());
    Route::post('/logout', [AuthTokenController::class, 'logout']);
    Route::get('/my-recipes', [AuthTokenController::class, 'myRecipes']);

    Orion::resource('recipes', RecipeController::class, [
        'only' => ['store', 'update', 'destroy'],
        'except' => ['batchStore', 'batchUpdate', 'batchDestroy', 'batchRestore', 'restore'],
    ]);
});
```

#### Por que este cambio es correcto

- La lectura se hace publica sin abrir la escritura.
- `my-recipes` sigue estando protegida.
- Se reutiliza el controlador actual.
- Se mantiene Orion como base del CRUD.
- La separacion se apoya en `only`, que si funciona en este stack.
- No hay solape entre acciones publicas y privadas.

### 2. Policy

#### Codigo actual

```php
class RecipePolicy
{
    public function viewAny(User $user): bool
    {
        return true;
    }

    public function view(User $user, Recipe $recipe): bool
    {
        return $recipe->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Recipe $recipe): bool
    {
        return $recipe->user_id === $user->id;
    }

    public function delete(User $user, Recipe $recipe): bool
    {
        return $recipe->user_id === $user->id;
    }
}
```

#### Codigo propuesto

```php
class RecipePolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Recipe $recipe): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return true;
    }

    public function update(User $user, Recipe $recipe): bool
    {
        return $recipe->user_id === $user->id;
    }

    public function delete(User $user, Recipe $recipe): bool
    {
        return $recipe->user_id === $user->id;
    }
}
```

#### Por que este cambio es correcto

- `viewAny` permite listado y busqueda publica.
- `view` permite abrir cualquier receta.
- `update` y `delete` siguen protegidos por propietario.
- `create` sigue requiriendo usuario autenticado porque la ruta de escritura continuaria dentro de `auth:sanctum`.
- `?User $user` permite acceso guest en esta version de Laravel.

## Detalles tecnicos importantes

### Orion y `search()`

En Orion, `search()` forma parte del recurso y debe abrirse junto con la lectura publica si se quieren filtros y busqueda sin login.

### Orion y `show()`

La operacion `show()` depende de `view`. Por eso el bug real se concentra en `RecipePolicy@view`.

### `my-recipes`

`my-recipes` no necesita cambios conceptuales. Ya esta separado del recetario general y ya devuelve las recetas del usuario autenticado.

### `beforeStore` y `beforeSave`

La logica actual de `RecipeController` para asignar `user_id` puede quedarse igual. Como `store` seguira siendo privado, la asignacion del propietario se mantiene correcta.

## Validacion y casos de prueba

### Invitado

- puede hacer `GET /api/recipes`
- puede hacer `GET /api/recipes/{recipe}`
- puede usar `POST /api/recipes/search`
- no puede hacer `POST /api/recipes`
- no puede hacer `PUT/PATCH /api/recipes/{recipe}`
- no puede hacer `DELETE /api/recipes/{recipe}`
- no puede acceder a `GET /api/my-recipes`

### Usuario autenticado

- puede ver listado y detalle de cualquier receta
- puede usar filtros y busqueda
- puede crear receta propia
- `my-recipes` solo devuelve sus recetas
- no puede editar receta ajena
- no puede borrar receta ajena

### Regresiones a vigilar

- `update` y `delete` deben seguir siendo owner-only
- login, register, logout y `me` no deben romperse
- el detalle de receta ajena ya no debe devolver `403`
- no deben aparecer rutas duplicadas si las acciones publicas y privadas estan bien separadas

## Limitacion actual: no existe `published`

En el esquema actual no existe ningun campo como `published`, `status`, `draft` o `visibility`. Por tanto, en esta fase documental, "recetas publicadas" debe entenderse como "todas las recetas existentes".

Eso significa que esta propuesta resuelve la visibilidad del recetario tal como esta hoy el backend, pero no introduce una capa real de publicacion. Si en el futuro se quiere distinguir entre borradores y recetas visibles, habra que hacer un cambio aparte con:

- migracion nueva
- reglas de visibilidad
- filtros de consulta
- ajustes en la policy

## Conclusion

El bug real esta en el detalle de receta y su causa esta en `RecipePolicy@view`. La solucion recomendada es abrir la lectura publica del recetario y, al mismo tiempo, mantener toda la gestion de recetas dentro del area autenticada.

Con este enfoque se logra:

- invitados pueden ver recetas
- usuarios autenticados pueden crear las suyas
- `my-recipes` sigue siendo privado
- nadie puede modificar recetas ajenas

La propuesta sigue siendo viable. Lo que necesitaba correccion no era el objetivo funcional, sino la fundamentacion tecnica del documento.
