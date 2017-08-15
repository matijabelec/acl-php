# acl-php

[![Packagist](https://img.shields.io/packagist/v/matijabelec/acl-php.svg)](https://packagist.org/packages/matijabelec/acl-php)

Custom ACL with role hierarchy (any role can have max. of 1 parent role). Role hierarchy is used to automatically inherit actions allowed for parent(s) roles to selected role. Actions are just strings that describes some action. Roles are, basically, strings that represents types of users (but not limited to).

# Examples

Before anything else, create an object of MatijaBelec\Acl\Acl class.
```php
    use MatijaBelec\Acl\Acl;
    $acl = new Acl();
```

There should be created role hierarchy. It can be created with the following code:

```php
    // create role hierarchy
    $acl->addRole('guest');
    $acl->addRole('user', 'guest');
    $acl->addRole('administrator', 'user');
```
After role hierarchy is created, let's add some actions to roles:

```php
    // create actions tree
    $acl->allow('guest', 'user.canUpdate');
    $acl->allow('guest', 'user.canDelete');
    $acl->allow('user', 'user.canCreate');
    $acl->deny('user', 'user.canDelete');
    $acl->allow('administrator', ['user.canRead', 'user.canUpdate']);
    $acl->allow('administrator', 'user.canDelete');
```

To check if some role has allowed actions, the following code can be used:

```php
    // check actions on user
    $adminIsAllowedToGetUserDetails = $acl->isAllowed('administrator', 'user.canRead');
    $userIsAllowedToCreateNewUser = $acl->isAllowed('user', 'user.canCreate');
```

# License
MIT (Matija Belec 2017)
