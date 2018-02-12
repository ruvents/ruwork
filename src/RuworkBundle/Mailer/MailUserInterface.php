<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Mailer;

interface MailUserInterface
{
    /**
     * Email для отправки и для получения
     *
     * @return string
     */
    public function getEmail(): string;

    /**
     * Имя пользователя с учетом локали
     * Будет использовано в полях от/кому
     *
     * @param string $locale Язык отправляемого сообщения
     *
     * @return string
     */
    public function getMailName(string $locale): string;

    /**
     * Предпочтительный язык сообщения
     * Учитывается при формировании сообщения для данного пользователя
     *
     * @return string
     */
    public function getMailLocale(): string;
}
