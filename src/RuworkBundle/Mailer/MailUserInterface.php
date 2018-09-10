<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Mailer;

/**
 * @deprecated Deprecated since 0.11.1 and will be removed in 0.12.0.
 */
interface MailUserInterface
{
    /**
     * Email для отправки и для получения.
     */
    public function getEmail(): string;

    /**
     * Имя пользователя с учетом локали
     * Будет использовано в полях от/кому.
     *
     * @param string $locale Язык отправляемого сообщения
     */
    public function getMailName(string $locale): string;

    /**
     * Предпочтительный язык сообщения
     * Учитывается при формировании сообщения для данного пользователя.
     */
    public function getMailLocale(): string;
}
