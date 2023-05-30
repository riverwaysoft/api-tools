# Api tools

## Installation

`composer req riverwaysoft/api-tools`

## What's inside?

### TelephoneObject
Wrapper around libphonenumber + PhoneNumberType for DoctrineType. 
Configuration: 
```yaml
doctrine:
    dbal:
        types:
            phone_number: Riverwaysoft\ApiTools\Telephone\Doctrine\DBAL\Types\TelephoneObjectType
```

### ApiPlatform extra

#### Extra serializers (Enum, TelephoneObject, Money)
Configuration: 

```injectablephp
$services
    ->load('Riverwaysoft\\ApiTools\\ApiPlatform\\Serializer\\', __DIR__ . '/../vendor/riverwaysoft/api-tools/src/Lib/ApiPlatform/Serializer');
```

#### Extra filters (look riveradmin ;))
* RiverAdminEnumSearchFilter
* RiverAdminSearchFilter
* RiverAdminBooleanFilter
* AbstractFullTextSearchFilter

### Domain Events

Usage:
```php
class UserRegisteredMessage {
    public function __construct(public string $username) {}
}

use Riverwaysoft\ApiTools\DomainEvents\EventSourceCollector;
class User extends EventSourceCollector {
    
    public function signUp(string $username){
        $this->rememberMessage(new UserRegisteredMessage($username));
    }
    
}

# After that message can be consumed with:

$user = new User();
$messages = $user->popMessages();
```
Or it can be done automatically with doctrine adapter:

Configuration:
```php
    $services->set(Riverwaysoft\ApiTools\DomainEvents\Doctrine\DoctrineDomainEventsCollector::class)->public()
        ->tag('doctrine.event_listener', ['event' => "postPersist"])
        ->tag('doctrine.event_listener', ['event' => "postUpdate"])
        ->tag('doctrine.event_listener', ['event' => "postFlush"])
        ->tag('doctrine.event_listener', ['event' => "postLoad"]);
```

### UnicodeIgnoreOrderJsonDriver

A driver for the [phpunit-snapshot-assertions](https://github.com/spatie/phpunit-snapshot-assertions) library. This driver is responsible for 3 main things:

1) Show unicode characters unescaped in json, so you'll see `Привет` instead of `\u041F\u0440\u0438\u0432\u0435\u0442`
2) Ignore property order. Example equal json `{a: 1, b: 2}` and `{b: 2, a: 1}`
3) Ignore order of array elements in json. Example equal json arrays `[{a: 1}, {b: 2}]` and `[{b: 2}, {a: 1}]`

#### How to use

Add the following trait to all your tests:

```php
public function assertMatchesJsonUnicodeSnapshot(mixed $actual): void
{
    $this->assertMatchesSnapshot($actual, new UnicodeIgnoreOrderJsonDriver());
}
```

Use `assertMatchesJsonUnicodeSnapshot` instead of the `assertMatchesJsonSnapshot`.
