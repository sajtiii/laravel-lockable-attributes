<?php

namespace Sajtiii\LockableAttributes\Tests;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Schema\Blueprint;
use Orchestra\Testbench\TestCase as Orchestra;
use Sajtiii\LockableAttributes\LockableAttributesServiceProvider;
use Sajtiii\LockableAttributes\Tests\Models\TestModel;

class TestCase extends Orchestra
{
    protected LockableAttributesServiceProvider $provider;

    protected function setUp(): void
    {
        parent::setUp();

        $this->setUpEnvironment($this->app);
        $this->setUpDatabase($this->app);

        $this->provider = new LockableAttributesServiceProvider($this->app);

        Factory::guessFactoryNamesUsing(
            fn (string $modelName) => 'Sajtiii\\LockableAttributes\\Database\\Factories\\'.class_basename($modelName).'Factory'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            LockableAttributesServiceProvider::class,
        ];
    }

    public function setUpEnvironment($app)
    {
        $app['config']->set('app.env', 'testing');
        $app['config']->set('app.key', 'base64:MDdjejMwdmQweWl4enhxNWcycDdiOGZpZ3ZlajhlZHU=');
    }

    protected function setUpDatabase($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        $app['db']->connection()->getSchemaBuilder()->create('test_models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('slug');
            $table->json('locked_attributes');
            $table->timestamps();
        });

        TestModel::create([
            'name' => 'test',
            'slug' => 'test',
        ]);
    }
}
