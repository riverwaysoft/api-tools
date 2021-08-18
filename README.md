# Api tools

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