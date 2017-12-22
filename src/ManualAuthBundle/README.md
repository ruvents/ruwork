# Ruwork Manual Auth Bundle

## Configuration

```yaml
# app/config/security.yml
security:
  firewalls:
    dev:
      pattern:  ^/(_(profiler|wdt)|css|images|js)/
      security: false

    main:
      # add manual auth to any firewall (it has no options)
      manual: ~
      anonymous: ~
      provider: user_entity_provider
      form_login:
        # ...
      logout:
        # ...
      remember_me:
        secret: "%secret%"
        always_remember_me: true
```

## Authenticating a User in the controller

```php
<?php

namespace AppBundle\Controller;

use Ruwork\ManualAuthBundle\Security\AuthList;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/registration")
 */
class RegistrationController
{
    /**
     * @Route("")
     * @Template()
     */
    public function indexAction(AuthList $authList)
    {
        // registration form and etc
        
        /** @var UserInterface $user */
        
        // on form success
        
        // you have to create relevant Tokens
        // f.e. PostAuthGuardToken for Guard auth
        $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
        $authList->setToken('main', $token);
        
        // redirect to next page
    }
}
```
