CdliAutogenUsername
==================
Version 0.1.0 Created by the Centre for Distance Learning and Innovation (www.cdli.ca)

[![Build Status](https://secure.travis-ci.org/cdli/CdliAutogenUsername.png?branch=master)](http://travis-ci.org/cdli/CdliAutogenUsername)

Introduction
------------

CdliAutogenUsername is an extension to [ZfcUser](http://github.com/ZF-Commons/ZfcUser) which provides a flexible username generator.  
At it's heart is a simple, event-driven filter chain to which filter plugins are registered to perform specific actions on the username under construction.
The module also contains an adapter system for interfacing with external user account systems, such as ZfcUser. It uses 
these adapters to ensure that every username generated is unique to the system.

Installation: ZfcUser
------------

### Main Setup

1. Install the [ZfcUser](https://github.com/ZF-Commons/ZfcUser) ZF2 module
   by cloning it into `./vendor/` and enabling it in your
   `application.config.php` file.
2. Clone this project into your `./vendor/` directory and enable it in your
   `application.config.php` file.
4. Copy `./vendor/CdliAutogenUsername/config/module.cdliautogenusername.local.php.dist` to
   `./config/autoload/module.cdliautogenusername.local.php`.
5. Fill in the required configuration variable values in the file you just copied. 

### Activation

To have CdliAutogenUsername automatically inject a generated username when a new user account is created, paste the following
snippet into the `onBootstrap` event of one of your Application's modules:

```php
$serviceManager = $e->getTarget()->getServiceManager();
$zfcServiceEvents = $serviceManager->get('zfcuser_user_service')->events();
$zfcServiceEvents->attach('createFromForm', function($e) use ($serviceManager) {
    $user = $e->getParam('user');
    $generator = $serviceManager->get('CdliAutogenUsername\Generator');
    $username = $generator->generate();
    $user->setUsername($username);
));
```

DISCLAIMER
----------

This code is considered proof-of-concept, and has not been vetted or tested for
inclusion in a production environment.  Use of this code in such environments is
at your own risk. 

Released under the New BSD license.  See file LICENSE included with the source 
code for this project for a copy of the licensing terms. 
