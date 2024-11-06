<?php

namespace Sajtiii\LockableAttributes\Contracts;

interface HasLockedAttributes
{
    /**
     * @return string[]
     */
    public function getLockableAttributes(): array;

    /**
     * @return string[]
     */
    public function getLockedAttributes(): array;

    public function isAttributeLocked(string $attribute): bool;

    public function lockAttribute(string $attribute): static;

    public function unlockAttribute(string $attribute): static;

    public function toggleAttributeLock(string $attribute): static;
}
