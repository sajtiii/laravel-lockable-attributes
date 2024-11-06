# Lockable Attributes for Laravel

## Usage
1.

2. Prepare your model
Add the `` interface and `` trait to your model:
```php
use Sajtiii\LockableAttributes\Concerns\InteractsWithLockedAttributes;
use Sajtiii\LockableAttributes\Contracts\HasLockedAttributes;

class MyModel extends Model implements HasLockedAttributes
{
    use InteractsWithLockedAttributes;
    
```

### Filament
This package also comes with support for [[filament](https://filamentphp.com/)]. It provides an action that can be added to form components allowing you to nicely lock attributes on the view page.