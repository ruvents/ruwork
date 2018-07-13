<?php

declare(strict_types=1);

namespace Ruwork\RuworkBundle\Mailer;

interface MessageBuilderInterface
{
    /**
     * @param MailUserInterface|string $from
     *
     * @return MessageBuilderInterface
     */
    public function setFrom($from): self;

    public function setSubjects(array $subjects): self;

    public function addSubject(string $subject, string $locale = null): self;

    public function setTemplates(array $templates): self;

    public function addTemplate(string $template, string $locale = null): self;

    public function setParameters(array $parameters): self;

    public function addParameter(string $name, $value): self;

    public function setContentType(string $contentType): self;

    /**
     * @param \Swift_Mime_SimpleMimeEntity[] $attachments
     *
     * @return MessageBuilderInterface
     */
    public function setAttachments(array $attachments): self;

    public function addAttachment(\Swift_Mime_SimpleMimeEntity $attachment): self;

    /**
     * @param MailUserInterface|string $to
     */
    public function buildMessage($to): \Swift_Mime_SimpleMessage;

    /**
     * @param MailUserInterface|string $to
     *
     * @return MessageBuilderInterface
     */
    public function sendTo($to): self;
}
