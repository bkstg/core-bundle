The Backstage Core Bundle
=========================

The backstage core bundle provides all the required bundles, services and templates that build the "Backstage" application.
If this is your first time installing the backstage please consider using the [standard distribution](https://github.com/bkstg/standard-distribution) instead.

[![Build Status](https://travis-ci.org/bkstg/core-bundle.svg?branch=master)](https://travis-ci.org/bkstg/core-bundle)
[![Latest Stable Version](https://poser.pugx.org/bkstg/core-bundle/v/stable)](https://packagist.org/packages/bkstg/core-bundle)
[![Total Downloads](https://poser.pugx.org/bkstg/core-bundle/downloads)](https://packagist.org/packages/bkstg/core-bundle)
[![License](https://poser.pugx.org/bkstg/core-bundle/license)](https://packagist.org/packages/bkstg/core-bundle)

Requirements
------------

This bundle relies on several contributed bundles to function, these are required in the composer.json for this bundle but will require you to configure them correctly (see the [standard distribution](https://github.com/bkstg/standard-distribution) for default configurations):

* [doctrine/doctrine-bundle](https://packagist.org/packages/doctrine/doctrine-bundle)
* [exercise/htmlpurifier-bundle](https://packagist.org/packages/exercise/htmlpurifier-bundle)
* [friendsofsymfony/ckeditor-bundle](https://packagist.org/packages/friendsofsymfony/ckeditor-bundle)
* [knplabs/knp-menu-bundle](https://packagist.org/packages/knplabs/knp-menu-bundle)
* [knplabs/knp-paginator-bundle](https://packagist.org/packages/knplabs/knp-paginator-bundle)
* [midnightluke/group-security-bundle](https://packagist.org/packages/midnightluke/group-security-bundle)
* [sonata-project/block-bundle](https://packagist.org/packages/sonata-project/block-bundle)
* [sonata-project/media-bundle](https://packagist.org/packages/sonata-project/media-bundle)
* [stof/doctrine-extensions-bundle](https://packagist.org/packages/stof/doctrine-extensions-bundle)

Additionally this bundle (and all backstage bundles) requires the doctrine ORM to function, providing entities and configuration to work with these bundles, as well as the twig templating engine.

This bundle requires compatible user and membership provider services to function, they are not included, only interfaces to implement them.
We recommend the [Backstage FOS User Bundle](https://github.com/bkstg/fos-user-bundle) which integrates the contrib [FOS User Bundle](https://packagist.org/packages/friendsofsymfony/user-bundle) into the application.

Documentation
-------------

* [User documentation](https://github.com/bkstg/core-bundle/wiki): managing productions, media, etc.
* [Developer documentation](https://github.com/bkstg/core-bundle/tree/master/Resources/doc/index.md): installation, configuration, etc.
