<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Mailer;

use Twig\Environment;

class Mailer
{
    /**
     * @var Environment
     */
    private $twig;

    /**
     * @var \Swift_Mailer
     */
    private $swift;

    /**
     * @var MailUserInterface[]
     */
    private $users;

    public function __construct(Environment $twig, \Swift_Mailer $swift, array $users = [])
    {
        $this->twig = $twig;
        $this->swift = $swift;
        $this->users = array_map(function ($user) {
            if (is_array($user)) {
                $user = new MailUser($user['email'], $user['name'], $user['locale']);
            } elseif (!$user instanceof MailUserInterface) {
                throw new \InvalidArgumentException('User must be an array or implement MailUserInterface.');
            }

            return $user;
        }, $users);
    }

    public function getUser(string $id): MailUserInterface
    {
        if (!isset($this->users[$id])) {
            throw new \OutOfBoundsException(sprintf('User "%s" is not registered.', $id));
        }

        return $this->users[$id];
    }

    public function createMessageBuilder(): MessageBuilderInterface
    {
        return new MessageBuilder($this, $this->twig);
    }

    public function send(\Swift_Mime_SimpleMessage $message)
    {
        $this->swift->send($message);
    }
}
