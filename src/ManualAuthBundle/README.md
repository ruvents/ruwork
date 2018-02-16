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

declare(strict_types=1);

namespace App\Controller;

use Ruwork\ManualAuthBundle\ManualAuthScheduler;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

final class RegistrationController
{
    private $manualAuthScheduler;
    
    public function __construct(ManualAuthScheduler $manualAuthScheduler) {
        $this->manualAuthScheduler = $manualAuthScheduler;
    }
    
    /**
     * @Route("/registration")
     */
    public function __invoke()
    {
        // registration form handling
        
        /** 
         * @var UserInterface $user
         * @var FormInterface $form
         */
        
        if ($form->isSubmitted() && $form->isValid()) {
            // save user
            
            // you have to create relevant Tokens
            // f.e. PostAuthGuardToken for a firewall with guard authentication
            $token = new UsernamePasswordToken($user, $user->getPassword(), 'main', $user->getRoles());
            $this->manualAuthScheduler->schedule('main', $token);
            
            // redirect to next page
        }
        
        // not submitted and invalid form logic
    }
}
```
