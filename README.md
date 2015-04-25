ApiTokenBundle
=============

Integrates token authentication mechanism into Symfony2.

## Installation

Requires composer, install as follows

```sh
composer require bukatov/api-token-bundle dev-master
```

#### Enable Bundle

Place in your `AppKernel.php` to enable the bundle

```php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new \Bukatov\ApiTokenBundle\BukatovApiTokenBundle()
    );
}
```
#### Configuration

```yaml
bukatov_api_token: ~
```

#### Routing

To use the refresh (login) and invalidate (logout) functionality, add the routing file to you application:

```yaml
bukatov_api_token:
    resource: "@BukatovApiTokenBundle/Resources/config/routing.yml"
    prefix:   /
```

#### Entities

TODO

#### Set up security

In your security.yml:

```yml
security:
    role_hierarchy:
        ROLE_ADMIN:       [ROLE_USER]

    encoders:
        Bukatov\TestBundle\Entity\User:
            algorithm: sha512
            iterations: 10
            encode_as_base64: true

    providers:
        get_api_token_provider:
            entity: { class: BukatovTestBundle:User, property: username }
        api_token_provider:
            api_token_user_entity: { class: BukatovTestBundle:User }

    firewalls:
        dev:
            pattern:  ^/(_(profiler|wdt)|css|images|js)/
            security: false

        get_api_token:
            pattern:    ^/api_token/get
            methods:    [POST]
            anonymous:  true
            stateless:  true
            provider:   get_api_token_provider
            get_api_token:
                delivery:
                    type: json_post_body
                    username_parameter: username
                    password_parameter: password

        invalidate_api_token:
            pattern:    ^/api_token/invalidate
            anonymous:  false
            stateless:  true
            provider:   api_token_provider
            api_token:
                delivery:
                    type: headers
                    parameter: X-Api-Token

        secured_area:
            pattern:    ^/api
            anonymous:  false
            stateless:  true
            provider:   api_token_provider
            api_token:
                delivery:
                    type: headers
                    parameter: X-Api-Token
                lifetime: ~
                idle_time: ~
```