<?php

namespace Sajtiii\LockableAttributes\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Sajtiii\LockableAttributes\Concerns\InteractsWithLockedAttributes;
use Sajtiii\LockableAttributes\Contracts\HasLockedAttributes;

class TestModel extends Model implements HasLockedAttributes
{
    use InteractsWithLockedAttributes;

    public $table = 'test_models';

    protected $fillable = [
        'name',
        'slug',
    ];

    public function getLockableAttributes(): array
    {
        return [
            'name',
            'slug',
        ];
    }
}
