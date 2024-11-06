<?php

use Sajtiii\LockableAttributes\Tests\Models\TestModel;

it('locks attributes', function () {
    $model = TestModel::first();
    $this->assertDatabaseHas(TestModel::class, [
        'id' => $model->id,
        'locked_attributes' => '[]',
    ]);

    expect($model->locked_attributes)->toBeEmpty();
    expect($model->isAttributeLocked('name'))->toBeFalse();
    expect($model->isAttributeLocked('slug'))->toBeFalse();

    $model->lockAttribute('name');
    $model->unlockAttribute('slug');
    $model->save();
    $this->assertDatabaseHas(TestModel::class, [
        'id' => $model->id,
        'locked_attributes' => '["name"]',
    ]);

    expect($model->locked_attributes)->toHaveCount(1)->toContain('name');
    expect($model->isAttributeLocked('name'))->toBeTrue();
    expect($model->isAttributeLocked('slug'))->toBeFalse();

    $model->lockAttribute('slug');
    $model->save();
    $this->assertDatabaseHas(TestModel::class, [
        'id' => $model->id,
        'locked_attributes' => '["name","slug"]',
    ]);

    expect($model->locked_attributes)->toHaveCount(2)->toContain('name', 'slug');
    expect($model->isAttributeLocked('name'))->toBeTrue();
    expect($model->isAttributeLocked('slug'))->toBeTrue();
});

it('unlocks attributes', function () {
    $model = TestModel::first();
    $model->locked_attributes = ['name', 'slug'];
    $model->save();
    $this->assertDatabaseHas(TestModel::class, [
        'id' => $model->id,
        'locked_attributes' => '["name","slug"]',
    ]);

    expect($model->locked_attributes)->toHaveCount(2)->toContain('name', 'slug');
    expect($model->isAttributeLocked('name'))->toBeTrue();
    expect($model->isAttributeLocked('slug'))->toBeTrue();

    $model->unlockAttribute('name');
    $model->lockAttribute('slug');
    $model->save();
    $this->assertDatabaseHas(TestModel::class, [
        'id' => $model->id,
        'locked_attributes' => '["slug"]',
    ]);

    expect($model->locked_attributes)->toHaveCount(1)->toContain('slug');
    expect($model->isAttributeLocked('name'))->toBeFalse();
    expect($model->isAttributeLocked('slug'))->toBeTrue();

    $model->unlockAttribute('slug');
    $model->save();
    $this->assertDatabaseHas(TestModel::class, [
        'id' => $model->id,
        'locked_attributes' => '[]',
    ]);

    expect($model->locked_attributes)->toBeEmpty();
    expect($model->isAttributeLocked('name'))->toBeFalse();
    expect($model->isAttributeLocked('slug'))->toBeFalse();
});

it('toggles attribute lock', function () {
    $model = TestModel::first();
    $this->assertDatabaseHas(TestModel::class, [
        'id' => $model->id,
        'locked_attributes' => '[]',
    ]);

    expect($model->locked_attributes)->toBeEmpty();
    expect($model->isAttributeLocked('name'))->toBeFalse();
    expect($model->isAttributeLocked('slug'))->toBeFalse();

    $model->toggleAttributeLock('name');
    $model->save();
    $this->assertDatabaseHas(TestModel::class, [
        'id' => $model->id,
        'locked_attributes' => '["name"]',
    ]);

    expect($model->locked_attributes)->toHaveCount(1)->toContain('name');
    expect($model->isAttributeLocked('name'))->toBeTrue();
    expect($model->isAttributeLocked('slug'))->toBeFalse();

    $model->toggleAttributeLock('slug');
    $model->save();
    $this->assertDatabaseHas(TestModel::class, [
        'id' => $model->id,
        'locked_attributes' => '["name","slug"]',
    ]);

    expect($model->locked_attributes)->toHaveCount(2)->toContain('name', 'slug');
    expect($model->isAttributeLocked('name'))->toBeTrue();
    expect($model->isAttributeLocked('slug'))->toBeTrue();

    $model->toggleAttributeLock('name');
    $model->save();
    $this->assertDatabaseHas(TestModel::class, [
        'id' => $model->id,
        'locked_attributes' => '["slug"]',
    ]);

    expect($model->locked_attributes)->toHaveCount(1)->toContain('slug');
    expect($model->isAttributeLocked('name'))->toBeFalse();
    expect($model->isAttributeLocked('slug'))->toBeTrue();
});
