<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Mailer;

/**
 * @deprecated Deprecated since 0.11.1 and will be removed in 0.12.0.
 */
class MailUser implements MailUserInterface
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var string[]
     */
    private $names;

    /**
     * @var string
     */
    private $locale;

    /**
     * @param string               $email  Email
     * @param null|string|string[] $name   Имя строкой (будет использовано для любой локали) или массивом (ключ - локаль)
     * @param string               $locale Предпочтительный язык сообщения
     */
    public function __construct($email, $name = null, $locale = 'ru')
    {
        $this->email = $email;
        $this->names = (array) $name;
        $this->locale = $locale;
    }

    public function __toString(): string
    {
        $name = \reset($this->names);

        return (string) $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * {@inheritdoc}
     */
    public function getMailName(string $locale): string
    {
        $name = $this->names[$locale] ?? \reset($this->names);

        return $name;
    }

    /**
     * {@inheritdoc}
     */
    public function getMailLocale(): string
    {
        return $this->locale;
    }
}
