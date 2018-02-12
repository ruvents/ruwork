<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Mailer;

interface MessageBuilderInterface
{
    /**
     * @param string|MailUserInterface $from
     *
     * @return MessageBuilderInterface
     */
    public function setFrom($from): MessageBuilderInterface;

    public function setSubjects(array $subjects): MessageBuilderInterface;

    public function addSubject(string $subject, string $locale = null): MessageBuilderInterface;

    public function setTemplates(array $templates): MessageBuilderInterface;

    public function addTemplate(string $template, string $locale = null): MessageBuilderInterface;

    public function setParameters(array $parameters): MessageBuilderInterface;

    public function addParameter(string $name, $value): MessageBuilderInterface;

    public function setContentType(string $contentType): MessageBuilderInterface;

    /**
     * @param \Swift_Mime_SimpleMimeEntity[] $attachments
     *
     * @return MessageBuilderInterface
     */
    public function setAttachments(array $attachments): MessageBuilderInterface;

    public function addAttachment(\Swift_Mime_SimpleMimeEntity $attachment): MessageBuilderInterface;

    /**
     * @param string|MailUserInterface $to
     *
     * @return \Swift_Mime_SimpleMessage
     */
    public function buildMessage($to): \Swift_Mime_SimpleMessage;

    /**
     * @param string|MailUserInterface $to
     *
     * @return MessageBuilderInterface
     */
    public function sendTo($to): MessageBuilderInterface;
}
