# Lockable Attributes for Laravel Models
This package allows you to easily lock model attributes preventing them from a sudden override.

## Usage
1. Setup database \
Add a json column to your database table named however you want, but preferably `locked_attributes`. 
```php
Schema::create('my_models', function (Blueprint $table) {
    ...
    $table->json('locked_attributes');
    ...
});
```

2. Prepare your model \
Add the `HasLockedAttributes` interface and `InteractsWithLockedAttributes` trait to your model:
```php
use Sajtiii\LockableAttributes\Concerns\InteractsWithLockedAttributes;
use Sajtiii\LockableAttributes\Contracts\HasLockedAttributes;

class MyModel extends Model implements HasLockedAttributes
{
    use InteractsWithLockedAttributes;
    
    ...
}
```

3. Define which attributes can be locked. \
Add the `getLockableAttributes()` method to your model:
```php
class MyModel extends Model implements HasLockedAttributes
{
    use InteractsWithLockedAttributes;
    
    public function getLockableAttributes(): array
    {
        return [
            'name',
            'title',
            ...
        ];
    }
```

## Filament support
This package also comes with a form action for [Filament](https://filamentphp.com/), that can be added to form components allowing you to easily lock attributes on the resource page.

*Limitations: This action currently can only be used on the view page of the resource.*

Example (Adding the toggle action to a `TextInput`):
```php
use Filament\Forms\Components\TextInput;
use Sajtiii\LockableAttributes\Contracts\HasLockedAttributes;
use Sajtiii\LockableAttributes\Filament\Forms\Actions\ToggleAttributeLockAction;

TextInput::make('name')
    ->suffixAction(ToggleAttributeLockAction::make('lock'))
    ->disabled(fn (?HasLockedAttributes $record) => $record && $record->isAttributeLocked($this->getStatePath(false))),

```