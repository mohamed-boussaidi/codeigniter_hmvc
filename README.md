### MIND KERNEL

#### Introduction

Mind kernel is **CodeIgniter 3** based application, we build it to be light, 
fast and modular, to achieve that we used an architecture called 
**HMVC** it is an improved version of the **MVC** approach, the difference
between **MVC** and **HMVC** is that the last one is a collection of many **MVC**
that communicate together using a front controller, we can consider each one of this collection as 
separated module.

Our kernel includes many technologies to work smoothly and to achieve
many features that **Native PHP** do not support like the **websocket** 
for the real time web and a **REST** api controller.

#### Requirements

* PHP 5.6
* Ubuntu 16.04 LTS
* Apache/2.4.18 (Ubuntu)
* PHP extensions mysqli curl mbstring
* MySAL 10.1.21-MariaDB

The application should work on **PHP 7.x** but you can find some bugs, we hope that you
report them to **mind_kernel_bug_tracker@mind.engineering**.

#### Installation

1. Unzip the application archive to your web server.
2. Update `/application/config/config.php` and set the base url as example `$config['base_url'] = 'https://mind.engineering/';`.
3. Make sure that the index page defined `$config['index_page'] = 'index.php';`.
4. Update `/application/config/database.php` and set the database access parameters.
5. Run migration via the **CLI** to create database tables using `php index.php tools migrate`.
6. Login to system via `base_url/index.php/signin`.

**PS** : to remove the `index.php` from the links you need to use `.htaccess`, 
put the file into the main folder of your application then put this codes into it 
```apacheconfig
RewriteEngine on
RewriteCond $1 !^(index\.php|resources|robots\.txt)
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php/$1 [L,QSA]
```

then update the config file and make sure to remove the index page 
`$config['index_page'] = '';` then you can access the links without the 
`index.php`.

You may need to set permission `777` to the `/assets`, `/files`, `/application/logs`, `/application/migrations`.

#### Configuration

##### Emails

If your server send mail not work correctly you can use SMTP 
located under `settings/emails/`.

##### WebSocket

To enable web socket go to `settings/websocket`, set the server IP / Port and run it.

##### REST

to use **REST API** you should generate **RSA KEYS** using `settings/rsa_keys` 
and create an **API KEY** to the client using `settings/rest_keys`.

##### Modules

You could install a module using `settings/modules`, the modules should
be a zipped archive.

To consider an archive as valid module it should contain `helpers/building_helper.php`.

##### Features

To enable or disable a feature you can use 
`application/modules/modules_manager/config/features.php`, as example to enable
modules deleting `$features["delete_module"] = true;`.


