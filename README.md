#Websoftwares example domain
PHP Example web application with an Action-Domain-Responder skeleton

##System requirements
- PHP 5.5+
- MySQL 5.6+
- Linux system

## Installation

1) Execute the database queries from the sql folder.

2) Create a .env in the root (not document root) folder use the example to match your environment.

3) Install composer in your project:

```
curl -s http://getcomposer.org/installer | php
```
4) Install dependencies:

```
composer install
```

5) Start php web server from root folder (not document root)

```
php -S localhost:8080 -t public/
```

## Testing
In the tests folder u can find several tests.

## License
The [MIT](http://opensource.org/licenses/MIT "MIT") License (MIT).
