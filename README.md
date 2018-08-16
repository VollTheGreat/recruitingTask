[![Build Status](https://travis-ci.org/VollTheGreat/recruitingTask.svg?branch=master)](https://travis-ci.org/VollTheGreat/recruitingTask)
# Recruiting Task
## Description
 Test task was done with:
 * Docker;
 * Laravel Framework v.5.6;
 * PHPUnit 7.3.1;
 * Connected with TravisCI.
 
 Application is an API service with functionality that was required in test task.

## Main file structure guideline
* [Application main files](https://github.com/VollTheGreat/recruitingTask/blob/master/components/app/src): components/app/src
* [Application tests files](https://github.com/VollTheGreat/recruitingTask/blob/master/components/app/src/tests): components/app/src/tests
* [Application database migrations](https://github.com/VollTheGreat/recruitingTask/blob/master/components/app/src/database/migrations): components/app/src/database/migrations
* [Application factories](https://github.com/VollTheGreat/recruitingTask/blob/master/components/app/src/database/factories): components/app/src/database/factories
* [Application Device domain](https://github.com/VollTheGreat/recruitingTask/blob/master/components/app/src/app/Domain/Device): components/app/src/app/Domain/Device
* [Application Device Controller](https://github.com/VollTheGreat/recruitingTask/blob/master/components/app/src/app/Http/Controllers/DeviceController.php): components/app/src/app/Http/Controllers/DeviceController.php
* [Application Routes](https://github.com/VollTheGreat/recruitingTask/tree/master/components/app/src/routes/api.php): components/app/src/routes/api.php
* [TravisCI configuration file](https://github.com/VollTheGreat/recruitingTask/tree/master/.travis.yml): .travis.yml

## TravisCI Build Status
[![Build Status](https://travis-ci.org/VollTheGreat/recruitingTask.svg?branch=master)](https://travis-ci.org/VollTheGreat/recruitingTask)
## Fire up project
To start a project you need docker and docker-compose installed.

* ***optional*** if LAMP is running 
```
- sudo service mysql stop
```
* start project
```
- docker-compose up -d
```
you are ready to go!

