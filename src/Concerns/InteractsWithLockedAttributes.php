<?php

namespace Sajtiii\LockableAttributes\Concerns;

use Illuminate\Database\Eloquent\Model;
use Sajtiii\LockableAttributes\Contracts\HasLockedAttributes;

trait InteractsWithLockedAttributes // @phpstan-ignore trait.unused
{
    public static function bootInteractsWithLockedAttributes()
    {
        static::saving(function (Model&HasLockedAttributes $model) {
            if (! is_array($model->getAttribute($model->getLockedAttributesColumnName()))) {
                $model->setAttribute($model->getLockedAttributesColumnName(), []);
            }
            foreach ($model->getLockableAttributes() as $attribute) {
                if ($model->isDirty($attribute) && $model->isAttributeLocked($attribute)) {
                    $model->setAttribute($attribute, $model->getOriginal($attribute));
                }
            }
        });
    }

    public function initializeInteractsWithLockedAttributes()
    {
        if ($this->registerCastForLockedAttributeColumn()) {
            $this->mergeCasts([
                $this->getLockedAttributesColumnName() => 'array',
            ]);
        }
    }

    public function isAttributeLocked(string $attribute): bool
    {
        return in_array($attribute, $this->getLockedAttributes());
    }

    public function lockAttribute(string $attribute): static
    {
        if (
            in_array($attribute, $this->getLockableAttributes()) &&
            ! in_array($attribute, $this->getLockedAttributes())
        ) {
            $this->setAttribute($this->getLockedAttributesColumnName(), array_merge($this->getLockedAttributes(), [$attribute]));
        }

        return $this;
    }

    public function unlockAttribute(string $attribute): static
    {
        $this->setAttribute($this->getLockedAttributesColumnName(), array_values(array_diff($this->getLockedAttributes(), [$attribute])));

        return $this;
    }

    public function toggleAttributeLock(string $attribute): static
    {
        $this->isAttributeLocked($attribute) ? $this->unlockAttribute($attribute) : $this->lockAttribute($attribute);

        return $this;
    }

    public function registerCastForLockedAttributeColumn(): bool
    {
        return true;
    }

    public function getLockedAttributesColumnName(): string
    {
        return 'locked_attributes';
    }

    public function getLockedAttributes(): array
    {
        return $this->getAttribute($this->getLockedAttributesColumnName()) ?? [];
    }
}
