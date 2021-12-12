# TodoList [![Codacy Badge](https://app.codacy.com/project/badge/Grade/20872575b2934283ba8773a14a334d63)](https://www.codacy.com/gh/leCheveuCodeur/TodoList/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=leCheveuCodeur/TodoList&amp;utm_campaign=Badge_Grade) [![Maintainability](https://api.codeclimate.com/v1/badges/2d02201b1d9fa991db44/maintainability)](https://codeclimate.com/github/leCheveuCodeur/TodoList/maintainability) [![Codacy Badge](https://app.codacy.com/project/badge/Coverage/20872575b2934283ba8773a14a334d63)](https://www.codacy.com/gh/leCheveuCodeur/TodoList/dashboard?utm_source=github.com&utm_medium=referral&utm_content=leCheveuCodeur/TodoList&utm_campaign=Badge_Coverage)

## Description

This project is the 8th project of the [Developer PHP / Symfony](https://openclassrooms.com/fr/paths/59-developpeur-dapplication-php-symfony) formation of [Openclassrooms](https://openclassrooms.com/).

The first objective of this project was to **migrate a Symfony 3.3 project to a newer** version, here my choice was **the latest Symfony LTS, 5.4**.

The second objective was to implement **unit and functional tests via [PHPUnit](https://phpunit.readthedocs.io/en/9.5/)** and to fix/add some features.

The last objective was to perform a **quality and performance audit of the project, via [Blackfire](https://blackfire.io/) and [Codacy](https://www.codacy.com/)** mainly.

## How it work

![TodoList](TodoList.gif)

## Build with

### Server :

- [PHP v7.4.26](https://www.php.net/releases/index.php)
- [Apache v2.4.48](https://www.apachelounge.com/download/VC15/)
- [MySQL v8.0.24](https://downloads.mysql.com/archives/installer/)
- **Server** : *for the server you can turn to the classics: [WAMP](https://www.wampserver.com/), [MAMP](https://www.mamp.info/en/downloads/), [XAMPP](https://www.apachefriends.org/fr/index.html) ...Or test the best of the swiss knives server: [Laragon](https://laragon.org/), my favorite ❤️*

### Framework & Libraries :

- [Symfony 5.4.1](https://symfony.com/releases/5.4)
- [Composer](https://getcomposer.org/download/)
- [Bootstrap v5.1.3](https://getbootstrap.com/)

## Installation

### **Clone or download the repository**, and put files into your environment,

```
https://github.com/leCheveuCodeur/TodoList.git
```

### Install libraries with **composer**,

```
composer install
```

### Configure your environment with `.env` file :

```
# DATABASE_URL="mysql://db_user:db_password@127.0.0.1:3306/db_name?serverVersion=8"


###> Admin configuration, used in fixtures loading ###
ADMIN_PSEUDO='Admin'
ADMIN_EMAIL='your@email.com'
ADMIN_PASSWORD='yourPassword'
```

### Initialise your Database :
#### *OPTION 1 - Use the shortcut of my Makefile* ❤️
```
make db
```
#### *OPTION 2 - Use the next commands with the PHP console*
1 - create your database :

````
php bin/console d:d:c
````

2 - create the structure in your database :

```
php bin/console d:m:m
```

3 - and install fixturesfor have first contents and your Admin account :

```
php bin/console d:f:l -n
```
### Activate APCu cache on your Database :
1 - Download the latest version of the APCu file [👉HERE](https://pecl.php.net/package/APCU),

2 - Unzip the file,

3 - Copy/paste the `php_apcu.dll` file in your `php_last_version/ext` folder,

4 - Activate in the control panel of your server the APCu extension for PHP,

5 - Check the activation via the command `php -m`, where you should see the extension in the listed modules.

### And Voilà ! 🎉

## Launched the tests :
- *OPTION 1 - Use the shortcut of my Makefile* ❤️
```
make t
```
- *OPTION 2 - Use the command with the PHP console*
```
php bin/phpunit
```
