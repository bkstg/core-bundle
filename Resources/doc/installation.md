Installation
============

Download the Bundle
-------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

```console
$ composer require bkstg/core-bundle
```

This command requires you to have Composer installed globally, as explained
in the [installation chapter](https://getcomposer.org/doc/00-intro.md)
of the Composer documentation.

Then, enable the bundle by adding it to the list of registered bundles
in the `app/AppKernel.php` file of your project:

```php
// app/AppKernel.php

// ...
class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            // ...
            new Bkstg\CoreBundle\BkstgCoreBundle(),
        ];

        // ...
    }

    // ...
}
```

Add Routing Files
-----------------

You must import the routing files to your app for this bundle to function.
To install the routing files import the Resources/config/routing/all.yml, when using the default config/routes.yaml file this looks like:

```YAML
bkstg_core:
  resource: '@BkstgCoreBundle/Resources/config/routing/all.yml'
```

Add a User Provider
-------------------

This bundle requires a user provider to function, none is provided out of the box. 
This user provider must provide a service that implements the `MembershipProviderInterface` for autowiring.
The "[Backstage FOS User Bundle](https://packagist.org/packages/friendsofsymfony/user-bundle)" is available to integrate user management into the application.
