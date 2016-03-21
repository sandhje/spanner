# spanner

A framework agnostic configuration package.

## Installation

1) Add composer to your project, see https://getcomposer.org/

2) Add this github repository to the repositories element in your composer.json (Note: once stable this package will be published on packagist and this step will no longer be necessary)

```
"repositories" : [ {
    "type" : "composer",
    "url" : "https://packagist.org/"
}, {
    "url": "https://github.com/sandhje/spanner.git",
    "type": "git"
} ]
```

3) Add the package to the require element in your composer.json
```
"require" : {
    "sandhje/spanner" : "*"
},
```

4) Run `composer install`

## Main concepts

Spanner is build up around four main parts: the Config class, Resources, Adapters and ConfigCollections.

A resource contains configuration data, which is interpreted by an adapter. The config class allows access through it's public methods to the interpreted data and returns that data as a ConfigCollection. 

Configuration data is organized in regions. The adapter is responsible for translating the region to a load operation on the resource and interpreting it's result, if any.

Configuration data can be overwritten with data in an environment if one is set. E.g. if the environment 'local' is set and it contains configuration data, that data is merged with the 'default' configuration data. In case of duplicate data the environment configuration data overwrites the 'default' data. Besides overwriting configuration data with an environment file, configuration data can also be overwritten from within your code by calling the Config::set method, data set through this method overwrites both the default and environment specific configuration data.

Spanner is designed to be extended, you can easily create your own resources and adapters to tailor the package to your needs.

### Basic usage example

Given the configuration data is inside the folder "/path/to/config" and consists of a file called database.yml.

1) Setup the configuration class:

```
$config = new Sandhje\Spanner\Config(new YamlAdapter());
$config->appendResource('/path/to/config')->setEnvironment("local");
```

2) Load the data in the region 'database':

```
$config->get("database");
```

This returns the data in "database.yml" and if available "local/database.yml". 

### Resources

A resource can be anything that holds configuration data, like the local filesystem, a database, a remote filesystem, etc. Resources are environment aware, this means that when a load operation is called on the resource and an environment is passed, the resource will search in the environment location and then in the default location. Currently Spanner comes with one type of resource: LocalFilesystemResource.

#### Usage

The config class provides several methods to add resources.

```
$config->appendResource('path/to/config');
``` 

This is a shorthand for:

```
$config->appendResource(new LocalFilesystemResource('path/to/config'));
```

Instead of appendResource, prependResource can be used to control the order in which the resources are called by the adapter. E.g.:

```
$config->prependResource('path/to/config');
```

It can be easier to set an array with resource instead of appending them one-by-one. This can be done with:

```
$config->setResourceArray(array(
    'path/to/config1',
    new LocalFilesystemResource('path/to/config2'),
    ...
));
```

The default resource is LocalFilesystemResource but an instance of any custom resource type can be passed. See 'creating your own resource' below for details.

#### LocalFilesystemResource

The local filesystem resource serves stuff that is on your local filesystem to the adapter. Upon construction of the LocalFilesystemResource object a path to the resource location has to be passed, this can be either a file or a directory. If the LocalFilesystemResource is passed a directory the resource's load operation will look for the requested file in ./{environment}/{file}.{ext} and if not found in ./{file}.{ext} If the LocalFilesystemResource is passed a file the resource's load operation will look for {file}.{environment}.{ext} and if not found {file}.{ext}. The extension depends on the used adapter.

#### Creating your own resource

If the inner workings of LocalFilesystemResource do not suit your needs you can easily create your own resource by creating a class that implements `\Sandhje\Spanner\Resource\ResourceInterface`.

### Adapters

The adapter is responsible for calling the load function on the resource with the correct arguments. When Config::get is called (and it's result is not cached) internally this call is forwarded to the adapter that has been set upon construction of the config class. The adapter will than call the load operation on each resource in the config class, optionally with an environment parameter if it was set in the config class. The adapter translates the result from the resource's load operation to a multidimensional associative array. Spanner ships with the following adapters:

* ArrayAdapter
* IniAdapter
* JsonAdapter
* XmlAdapter
* YamlAdapter

#### Usage

Set the adapter upon instantion of the config class:

```
$config = new Config(new YamlAdapter());
```

if no argument is passed to the config class contructor the ArrayAdapter is used.

#### ArrayAdapter

The array adapter tries to load .php files from your resource. These files need to return a single (optionally multidimensional) array. e.g.:

```
<?php

return array(
    "driver"       => "mysql",
    "host"         => "127.0.0.1",
    "database"     => "foobar",
    "charset"      => "utf8",
    "collation"    => "utf8_general_ci",
    "prefix"       => "",
);
```

#### IniAdapter

The ini adapter tries to load .ini files from your resources. These files need to be valid ini files. Sections are translated to a array. A valid ini configuration file could look like this:

```
driver=mysql
host=127.0.0.1
database=foobar
charset=utf8
collation=utf8_general_ci
prefix=
``` 

#### JsonAdapter

The json adapter tries to load .json files from your resources. These files need to be valid json files with one root object. A valid json configuration file could look like this:

```
{
	"driver"		: "mysql",
    "host"			: "127.0.0.1",
    "database"		: "foobar",
	"charset"		: "utf8",
    "collation"		: "utf8_general_ci",
    "prefix"		: ""
}
```

#### XmlAdapter

The xml adapter tries to load .xml files from your resources. These files need to be valid xml files. The element names will be mapped to array keys and the element contents to the values for the corresponding keys. Nesting elements will result in a multidimensional array. A xml configuration file could look like this:

```
<?xml version="1.0" encoding="UTF-8"?>

<database>
	<driver>mysql</driver>
	<host>127.0.0.1</host>
	<database>foobar</database>
	<charset>utf8</charset>
	<collation>utf8_general_ci</collation>
	<prefix></prefix>
</database>
```

#### YamlAdapter

The yaml adapter tries to load .yml files from your resources. These files need to be valid yaml files. Symfony's Yaml package is used for loading and translating yaml files to a (optionally multidimensional) array. A valid yaml configuration file could look like this:

```
driver: mysql
host: 127.0.0.1
database: foobar
charset: utf8
collation: utf8_general_ci
prefix:
```

#### Creating your own adapter

You can easily create your own adapter by either extending one of the existing adapters that closely matches your needs or by implementing the `\Sandhje\Spanner\Adapter\Adapterinterface`.

## TODO

- Add database adapter/resource
- Add docblock comment adapter/resource
- Add custom exception classes
- Add to packagist
- Finish this list ;)