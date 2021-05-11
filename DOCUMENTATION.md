# Documentation

This document will explain how to set up and use the development environment
based on Docker Compose for the purposes of this project. This environment
contains the following containers:

* php-fpm
* nginx
* elasticsearch
* mariadb
* tika

## Requirements

In order to use this environment, Docker Compose will be required. 
To install Docker Compose, read the linked 
[instructions](https://docs.docker.com/compose/install).

## Execution

Assuming you have installed and configured Docker Compose, enter a
command prompt and execute the command `docker-compose up`. When you execute 
this command for the first time, Docker will automatically download all files
required by the development environment. No custom ports are used and all 
containers are automatically started, so no further action is required.

## Configuration

### MariaDB

If you are running MariaDB for the first time, use the default user/password 
credentials "root/example".