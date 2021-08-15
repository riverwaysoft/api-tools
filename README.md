# Api tools

[libphonenumber for PHP](https://github.com/giggsey/libphonenumber-for-php)
[PHP Enum](https://github.com/myclabs/php-enum)
[Money](https://github.com/moneyphp/money)

# Что внутри?

### TelephoneObject
Обертка над libphonenumber + PhoneNumberType для DoctrineType. 
Конфигурация: 
```
doctrine:
    dbal:
        types:
            phone_number: Riverwaysoft\ApiTools\PhoneNumber\Doctrine\DBAL\Types\PhoneNumberType
```

### ApiPlatform extra
Дополнительные сериалайзеры (Enum, TelephoneObject, Money)
Конфигурация: 
```
doctrine:
    dbal:
        types:
            phone_number: Riverwaysoft\ApiTools\PhoneNumber\Doctrine\DBAL\Types\PhoneNumberType
```
