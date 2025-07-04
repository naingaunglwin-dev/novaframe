<?php

namespace NovaFrame\Facade;

/**
 * @method static \NovaFrame\Mailer\Mailer from(string|array $address)
 * @method static \NovaFrame\Mailer\Mailer to(string|array $address)
 * @method static \NovaFrame\Mailer\Mailer addTo(string|array $address)
 * @method static \NovaFrame\Mailer\Mailer subject(string $subject)
 * @method static \NovaFrame\Mailer\Mailer view(string $view, array $data = [])
 * @method static \NovaFrame\Mailer\Mailer html(string $body)
 * @method static \NovaFrame\Mailer\Mailer text(string $body)
 * @method static \NovaFrame\Mailer\Mailer cc(string|array $address)
 * @method static \NovaFrame\Mailer\Mailer addCc(string|array $address)
 * @method static \NovaFrame\Mailer\Mailer bcc(string|array $address)
 * @method static \NovaFrame\Mailer\Mailer addBcc(string|array $address)
 * @method static \NovaFrame\Mailer\Mailer attachFromPath(string $filePath, ?string $filename = null, ?string $contentType = null)
 * @method static \NovaFrame\Mailer\Mailer attach(string $data, string $filename, ?string $contentType = null)
 * @method static \NovaFrame\Mailer\Mailer header(string $name, string $value)
 * @method static \NovaFrame\Mailer\Mailer headers(array $headers)
 * @method static \NovaFrame\Mailer\Mailer replyTo(string|array $address)
 * @method static \NovaFrame\Mailer\Mailer priority(int $priority)
 * @method static \NovaFrame\Mailer\Mailer setEmail(\Symfony\Component\Mime\Email $email)
 * @method static \Symfony\Component\Mime\Email getEmail()
 * @method static bool send()
 * @method static bool send2local()
 */
class Mailer extends Facade
{
    protected static function accessor(): string
    {
        return \NovaFrame\Mailer\Mailer::class;
    }
}
