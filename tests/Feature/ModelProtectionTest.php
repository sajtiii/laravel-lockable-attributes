<?php

use Sajtiii\LockableAttributes\Tests\Models\TestModel;

it('saves attributes when not locked', function () {
    $model = TestModel::first();

    expect($model->name)->toBe('test');
    expect($model->slug)->toBe('test');

    $model->update([
        'name' => 'new-name',
        'slug' => 'new-slug',
    ]);

    expect($model->name)->toBe('new-name');
    expect($model->slug)->toBe('new-slug');

    $this->assertDatabaseHas(TestModel::class, [
        'id' => $model->id,
        'name' => 'new-name',
        'slug' => 'new-slug',
    ]);
});

it('reverts attributes when locked', function () {
    $model = TestModel::first();

    expect($model->name)->toBe('test');
    expect($model->slug)->toBe('test');

    $model->lockAttribute('slug');

    $model->update([
        'name' => 'new-name',
        'slug' => 'new-slug',
    ]);

    expect($model->name)->toBe('new-name');
    expect($model->slug)->toBe('test');

    $this->assertDatabaseHas(TestModel::class, [
        'id' => $model->id,
        'name' => 'new-name',
        'slug' => 'test',
    ]);
});
