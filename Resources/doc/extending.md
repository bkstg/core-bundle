Extending
=========

The backstage bundles are all designed to be easily extendable.
They stick as close as possible to symfony conventions and use standard events and services.
This page outlines some tools that can be used for writing your own backstage bundle.

Group Security and Group Context
--------------------------------

This bundle uses the MidnightLuke group security bundle for defining and enforcing group security.
Productions are "groups" and users are members of various groups, with various roles attached through group memberships.
To check group permissions you can use the standard symfony security system, simply pass the current group as the subject, for example:

```php
  // Check permissions for this action.
  if (!$auth->isGranted('GROUP_ROLE_USER', $production)) {
      throw new AccessDeniedException();
  }
```

or in twig:

```twig
  {% if is_granted('GROUP_ROLE_ADMIN', production) %}
    {# Some secure stuff. #}
  {% endif %}
```

Additionally, this bundle provides a "production context" service (see `Context/ProductionContextProvider.php`).
This is designed to provide any services that require the current production access to it through an easily accessible service.
To ensure any routes you create are "production-aware" ensure you use the `{production_slug}` placeholder, this is how context is derived.
You can use this service in twig templates using the `current_production()` function, which will retrieve the production using the production context service.

Menus
-----

This bundle provides several menus that can be added to using the events system.
These are all built using the [KNP menu bundle](https://github.com/KnpLabs/KnpMenuBundle), for further documentation see that bundle.

### Admin Menu

* Main sidebar menu on "admin" pages (ie pages using the admin layout).
* Items should only be accessible to users with "ROLE_ADMIN".
  * This is not guaranteed, so check security when building the menu items.
* Event listener or subscriber should listen to `AdminMenuCollectionEvent` (bkstg.core.menu.admin_collection).
* `getMenu()` returns root menu item, which you can use to modify the menu.
* Maximum depth is 2. 
  * Second level items are rendered when the parent is in the active trail. 
  * Any items that are farther down in the tree will not be rendered.

### Main Menu

* Menu that appears in the navbar at the top of every page.
* Event listener or subscriber should listen to the `MainMenuCollectionEvent` (bkstg.core.menu.main_collection).
* `getMenu()` returns the root menu item, which you can use to modify the menu.
* Maximum depth is 2. 
  * Second level items are rendered as a drop-down from the parent item.
  * Any items that are farther down in the tree will not be rendered.

### Production Menu

* Main sidebar menu on "production" pages (ie pages using the standard layout).
* Event listener or subscriber should listen to the `ProductionMenuCollectionEvent` (bkstg.core.menu.production_collection).
* `getMenu()` returns the root menu item, which you can use to modify the menu.
* `getGroup()` returns the current production (from production context), which can be used to create links, customize output, etc.
* Maximum depth is 2.
  * Second level items are only rendered when the parent is in the active trail.
  * Any items that are farther down in the tree will not be rendered.

### User Menu

* Menu that appears in the navbar at the top of every page on the right.
* Event listener or subscriber should listen to the `UserMenuCollectionEvent` (bkstg.core.menu.user_collection).
* `getMenu()` returns the root menu item, which you can use to modify the menu.
* Maximum depth is 1.
  * All items are rendered as part of the drop-down from the root item.
  * Any items that are farther down in the tree will not be rendered.

Templates
---------

All markup should be bootstrap 4 compatible, with fontawesome used for iconography.
Additionally this bundle integrates chosen and list.js for some useability, which can be used in templates.
When creating production bundles use the `layout.html.twig` template, for admin pages use the `layout-admin.html.twig` base template.

Entity Interfaces
-----------------

This bundle provides several entity interfaces (see the `Model/` directory).
These interfaces enforce common conventions and, in one instance, extend the doctrine events system.
These should be used whenever possible to ensure common conventions are used, however, none are mandatory.
The `PublishableInterface` provides additional events when used.

### PublishableInterface

* When using this interface you should _never_ call `setPublished()` on the entity, only set the value being returned from `isActive()`.
* When an entity using this interface is created/updated the event system will set the published value based on the value of `isActive()`.
* When an entity is "published" an additional `EntityPublishedEvent` is dispatched.
  * Useful for notifications, indexing, etc.
* For more information on this see `EventListener/PublishableListener.php`.
