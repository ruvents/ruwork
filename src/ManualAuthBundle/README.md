# Ruwork Manual Auth Bundle

## Configuration

```yaml
# app/config/security.yml
security:
    main:
        # add manual auth to any firewall
        manual: ~
        anonymous: ~
        provider: your_user_provider
        form_login:
            # ...
        logout:
            # ...
        remember_me:
            # configure remember me if you need
            always_remember_me: true
```

## Authenticating a User in the controller

```php
<?php

namespace AppBundle\Controller;

use Ruwork\ManualAuthBundle\ManualAuthenticator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

class RegistrationController
{
    /**
     * @Route("/registration")
     */
    public function __invoke(ManualAuthenticator $authenticator)
    {
        // registration form handling
        
        /** 
         * @var UserInterface $user
         * @var FormInterface $form
         */
        
        if ($form->isSubmitted() && $form->isValid()) {
            // you have to create relevant Tokens
            // f.e. PostAuthGuardToken for a firewall with guard authentication
            $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
            $authenticator->schedule('main', $token);
            
            // redirect to next page
        }
        
        // not submitted and invalid form logic
    }
}
```
