spanner
=======

A framework agnostic configuration package.

Installation
------------

1.  Add composer to your project, see https://getcomposer.org/

2.  Add this github repository to the repositories element in your composer.json
    and add the dev stability flag (Note: once stable this package will be
    published on packagist and this step will no longer be necessary)

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
"minimum-stability" : "dev",
"prefer-stable": true,
"repositories" : [ {
    "type" : "composer",
    "url" : "https://packagist.org/"
}, {
    "type": "vcs"
    "url": "https://github.com/sandhje/spanner.git",
} ]
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

1.  Add the package to the require element in your composer.json

    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
    "require" : {
    "sandhje/spanner" : "*"
    },
    ~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

2.  Run `composer install`

Main concepts
-------------

Spanner is build up around three main parts: the Config class, Resources and
ConfigCollections.

A resource contains configuration data. Each Resource is passed a strategy,
responsible for translating the result of the load operation on the resource to
a associative array. The config class allows access through it's public methods
to the translated data and returns that data as a ConfigCollection.

Configuration data is organized in regions and can be overwritten with data in
an environment if one is set. E.g. if the environment 'local' is set and it
contains configuration data, that data is merged with the 'default'
configuration data. In case of duplicate data the environment configuration data
overwrites the 'default' data. Besides overwriting configuration data with an
environment file, configuration data can also be overwritten from within your
code by calling the Config::set method, data set through this method overwrites
both the default and environment specific configuration data.

Spanner is designed to be extended, you can easily create your own resources and
strateies to tailor the package to your needs.

### Basic usage example

Given the configuration data is inside the folder "/path/to/config" and consists
of a file called database.yml.

1.  Setup the configuration class:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
use Sandhje\Spanner\Config;
use Sandhje\Spanner\Resource\LocalFilesystemResource;
use Sandhje\Spanner\Resource\Strategy\YamlStrategy;

$resource = new LocalFilesystemResource('/path/to/config', new YamlStrategy());

$config = new Config();
$config->setEnvironment("local")
    ->attachResource($resource);
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

1.  Load the data in the region 'database':

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$config->get("database");
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

This returns the data in "database.yml" and merges that data with
"local/database.yml" if available.

### Resources

A resource can be anything that holds configuration data, like the local
filesystem, a database, a remote filesystem, etc. Resources are environment
aware, this means that when a load operation is called on the resource and an
environment is setup in the Config class, the resource will search in the
environment location and then in the default location. Currently Spanner comes
with one type of resource: LocalFilesystemResource.

#### Usage

The config class provides the attachResource method to add resources.

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$config->attachResource(new LocalFilesystemResource('/path/to/config'), new YamlStrategy());
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Any class implementing `Sandje\Spanner\Resource\ResourceInterface` can be
passed. See 'creating your own resource' below for details.

#### LocalFilesystemResource

The local filesystem resource serves stuff that is on your local filesystem to
the config class. Upon construction of the LocalFilesystemResource object a path
to the resource location has to be passed, this can be either a file or a
directory. If the LocalFilesystemResource is passed a directory the resource's
load operation will look for the requested file in ./{environment}/{file}.{ext}
and if not found in ./{file}.{ext}. If the LocalFilesystemResource is passed a
file the resource's load operation will look for ./{file}.{environment}.{ext}
and if not found ./{file}.{ext}. The file extension depends on the strategy
used.

#### Creating your own resource

If the inner workings of LocalFilesystemResource do not suit your needs you can
easily create your own resource by creating a class that implements
`\Sandhje\Spanner\Resource\ResourceInterface`. This interface consists of three
methods:

1.  A constructor which is passed the resource and a strategy (a class
    implementing `Sandhje\Spanner\Resource\Strategy\ResourceStrategyInterface`).

2.  A load method which handles the loading of the data in the passed region and
    optionally environment. The environment passed can be a string or an array.
    It also forwards the loaded data to the strategy for translation to an
    associative array. It then returns that array.

3.  A tryLoad method which is basically a wrapper for the load method and
    returns true wen the load operation was successful and false otherwise. It
    receives an array by reference as its first parameter. The result of the
    load method should be assigned to this array.

### Strategies

The strategy is responsible for translating the loaded data from the resource to
a multidimensional associative array. Spanner ships with the following
strategies:

-   ArrayStrategy

-   IniStrategy

-   JsonStrategy

-   XmlStrategy

-   YamlStrategy

#### Usage

Set the strategy upon instantion of the resource class:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
$resource = new LocalFilesystemResource('resource', new YamlAdapter());
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

#### ArrayStrategy

The array strategy transates .php files from your resource. These files need to
return a single (optionally multidimensional) array. e.g.:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?php

return array(
    "driver"       => "mysql",
    "host"         => "127.0.0.1",
    "database"     => "foobar",
    "charset"      => "utf8",
    "collation"    => "utf8_general_ci",
    "prefix"       => "",
);
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

Note: This strategy doesn’t actually translate the data, but just checks that it
has a valid structure (meaning an array) and returns it.

#### IniStrategy

The ini strategy translates .ini files from your resources. These files need to
be valid ini files. Sections are translated to a array. A valid ini
configuration file could look like this:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
driver=mysql
host=127.0.0.1
database=foobar
charset=utf8
collation=utf8_general_ci
prefix=
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

#### JsonStrategy

The json strategy translates .json files from your resources. These files need
to be valid json files with one root object. A valid json configuration file
could look like this:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
{
    "driver"        : "mysql",
    "host"          : "127.0.0.1",
    "database"      : "foobar",
    "charset"       : "utf8",
    "collation"     : "utf8_general_ci",
    "prefix"        : ""
}
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

#### XmlStrategy

The xml strategy translates .xml files from your resources. These files need to
be valid xml files. The element names will be mapped to array keys and the
element contents to the values for the corresponding keys. Nesting elements will
result in a multidimensional array. A xml configuration file could look like
this:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
<?xml version="1.0" encoding="UTF-8"?>

<database>
    <driver>mysql</driver>
    <host>127.0.0.1</host>
    <database>foobar</database>
    <charset>utf8</charset>
    <collation>utf8_general_ci</collation>
    <prefix></prefix>
</database>
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

#### YamlStrategy

The yaml strategy translates .yml files from your resources. These files need to
be valid yaml files. Symfony's Yaml package is used for loading and translating
yaml files to a (optionally multidimensional) array. A valid yaml configuration
file could look like this:

~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~
driver: mysql
host: 127.0.0.1
database: foobar
charset: utf8
collation: utf8_general_ci
prefix:
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

#### Creating your own adapter

You can easily create your own adapter by either extending one of the existing
strategy classes that closely matches your needs or by implementing the
`\Sandhje\Spanner\Resource\Strategy\ResourceStrategyinterface`.

Additionally, to create a strategy to be used in the LocalFilesystemResource,
you need to implement the
`Sandhje\Spanner\Resource\Strategy\FilesystemResourceStrategyInterface`. This
interface defines a getFilename method which is used by the resource to get the
file to load including the correct extension.

TODO
----

-   Add multidimensional environments, e.g. nl/local or en/local

-   Add database resource and strategies

-   Add docblock comment resource and strategy

-   More thorough exception handling

-   Complete documentation

-   Setup sensiolabs insights analyses

-   Add to packagist

-   Finish this list ;)
