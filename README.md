Basic Functionality
===================
Basic Functionality

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist jakharbek/jakharbek-core "*"
```

or add

```
"jakharbek/jakharbek-core": "*"
```

to the require section of your `composer.json` file.


Usage
-----

Once the extension is installed, you mast configurate extension;
To do this, you need to open the src/bootstrap.php
And specify the mail component and your email

    public static $mailer_component = "mailer";
    public static $email_from = "";