<?php

namespace Sajtiii\LockableAttributes\Filament\Forms\Actions;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Support\Colors\Color;
use Illuminate\Database\Eloquent\Model;
use Sajtiii\LockableAttributes\Contracts\HasLockedAttributes;

class ToggleAttributeLockAction extends Action
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->visible(fn ($record) => is_subclass_of($record, HasLockedAttributes::class)
        );

        $this->disabled(fn (string $context) => $context !== 'view');

        $this->label(fn (HasLockedAttributes $record, Component $component) => __('lockable-attributes::action.label.'.($record->isAttributeLocked($component->getStatePath(false)) ? 'locked' : 'unlocked'))
        );

        $this->tooltip(fn (HasLockedAttributes $record, Component $component) => __('lockable-attributes::action.tooltip.'.($record->isAttributeLocked($component->getStatePath(false)) ? 'locked' : 'unlocked'))
        );

        $this->icon(fn (HasLockedAttributes $record, Component $component) => $record->isAttributeLocked($component->getStatePath(false)) ? 'heroicon-o-lock-closed' : 'heroicon-o-lock-open'
        );

        $this->color(fn (HasLockedAttributes $record, Component $component, string $context) => $context !== 'view' ? Color::Gray : ($record->isAttributeLocked($component->getStatePath(false)) ? Color::Red : Color::Green)
        );

        $this->action(fn (HasLockedAttributes&Model $record, Component $component) => $record->toggleAttributeLock($component->getStatePath(false))->save()
        );
    }
}
