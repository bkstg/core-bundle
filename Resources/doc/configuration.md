Configuration
=============

Configuration Provided by This Bundle
-------------------------------------

This bundle ships with an optional "Digital Ocean Spaces" CDN for the sonata media bundle.
The full configuration for this looks as follows:

```yaml
bkstg_core:
    cdn:
        bucket:               ~ # Required
        client:               bkstg.core.adapter.service.do_spaces
    filesystem:
        directory:            ''
        bucket:               ~ # Required
        accessKey:            ~ # Required
        secretKey:            ~ # Required
        region:               ~ # Required
        endpoint:             null
        create:               false
        storage:              standard
        cache_control:        ''
        acl:                  public
        encryption:           ''
        version:              latest
        sdk_version:          3 # One of 2; 3
        meta:

            # Prototype
            name:                 ~
```

A more minimal configuration that can be used in production might look similar to:

```yaml
bkstg_core:
    cdn:
        bucket: '%env(DO_BUCKET)%'
    filesystem:
        bucket:      '%env(DO_BUCKET)%'
        accessKey:   '%env(DO_ACCESS_KEY)%'
        secretKey:   '%env(DO_SECRET_KEY)%'
        region:      '%env(DO_REGION)%'
        acl:         'private'

```

This is very similar to the amazon S3 CDN and filesystems provided with the media bundle, however, they are adapted to work with DO spaces.
Once configured you need to configure the appropriate providers with the sonata bundle to use this CDN and filesystem, for example:

```yaml
sonata_media:
    providers:
        image:
            cdn: bkstg.media.cdn.private_do_spaces
            filesystem: bkstg.media.filesystem.do_spaces
        file:
            cdn: bkstg.media.cdn.private_do_spaces
            filesystem: bkstg.media.filesystem.do_spaces
```

Additional Configuration for Contrib Bundles
--------------------------------------------

Some non-standard configuration is required for this bundle to function properly.

### Media Entities and CDNs

This bundle provides a "private" local CDN endpoint that can be utilized to restrict permissions to media based on production memberships.
To use this endpoint you should use the following configuration for the sonata media bundle:

```yaml
sonata_media:
    default_context: default # Important!
    db_driver: doctrine_orm
    class:
        media: Bkstg\CoreBundle\Entity\Media # Important!
        gallery: Bkstg\CoreBundle\Entity\Gallery # Important!
        gallery_has_media: Bkstg\CoreBundle\Entity\GalleryHasMedia # Important!
    providers:
        image:
            resizer: sonata.media.resizer.square
    contexts:
        default:
            providers:
                - sonata.media.provider.image
                - sonata.media.provider.file
            formats:
                square: { width: 256, height: 256, quality: 90 }
                small: { width: 256 , quality: 90 }
                big: { width: 512 , quality: 90 }
                card_banner: { width: 705, height: 370, quality: 90 }
                full_banner: { width: 5120, quality: 90 }
    cdn:
        server:
            path: /media/serve # Important!
    filesystem:
        local:
            directory: "%kernel.root_dir%/../var/uploads/media" # Important (but anywhere outside the webroot is fine)!
            create: true
```

This will store media outside the webroot and use a CDN that bootstraps symfony and checks permissions for media.

### Security Bundle

This bundle and all other backstage bundles use the following role hierarchy:

```yaml
security:
    role_hierarchy:
        ROLE_ADMIN: ROLE_USER
        GROUP_ROLE_ADMIN: GROUP_ROLE_EDITOR
        GROUP_ROLE_EDITOR: GROUP_ROLE_USER
```

Additionally the following access control definition is recommended:

```yaml
security:
    access_control:
        - { path: ^/login$, role: IS_AUTHENTICATED_ANONYMOUSLY } # Only if using an in-application user manager.
        - { path: ^/resetting, role: IS_AUTHENTICATED_ANONYMOUSLY } # Only if using an in-application user manager.
        - { path: ^/admin/, role: ROLE_ADMIN }
        - { path: ^/, roles: ROLE_USER }
```

### Doctrine DBAL Additional Types

This bundle makes use of some additional Doctrine types provided by the php units of measure bundle:

```yaml
doctrine:
    dbal:
        types:
            length: MidnightLuke\PhpUnitsOfMeasureBundle\Doctrine\Types\LengthType
            mass: MidnightLuke\PhpUnitsOfMeasureBundle\Doctrine\Types\MassType
```

### KNP Pagination Templates

This bundle makes use of the KNP pagination and sorting library, while not mandatory, use of the following templates is recommended:

```yaml
knp_paginator:
    template:
        pagination: '@KnpPaginator/Pagination/twitter_bootstrap_v4_pagination.html.twig'
        sortable: '@BkstgCore/_sortable_link.html.twig'
```

### Twig Configuration

This bundle requires the definition of a few global date formats and form themes, the optimal configuration looks like:

```yaml
twig:
    globals:
        full_date: 'F j, Y g:i a'
        full_date_weekday: 'l, F j, Y g:i a'
    form_themes:
        - 'bootstrap_4_layout.html.twig'
        - '@SonataMedia/Form/media_widgets.html.twig'
        - '@BkstgCore/Form/_form_collection.html.twig'
        - '@BkstgCore/Form/_units_widgets.html.twig'
```

Additional Services for Contrib Bundles
----------------------------------------

This bundle relies on the twig extensions and gedmo doctrine extensions, they are enabled in your application's `services.yaml` file as so:


```yaml
services:
    twig.extension.text:
        class: Twig_Extensions_Extension_Text
        tags:
            - { name: twig.extension }
    twig.extension.date:
        class: Twig_Extensions_Extension_Date
        tags:
            - { name: twig.extension }
    Gedmo\Timestampable\TimestampableListener:
        tags:
            - { name: doctrine.event_subscriber, connection: default }
        calls:
            - [ setAnnotationReader, [ "@annotation_reader" ] ]
    Gedmo\Sluggable\SluggableListener:
        tags:
            - { name: doctrine.event_subscriber, connection: default }
        calls:
            - [ setAnnotationReader, [ "@annotation_reader" ] ]
```
