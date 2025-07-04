<?php

namespace NovaFrame\Mailer;

use NovaFrame\Helpers\FileSystem\FileSystem;
use NovaFrame\Helpers\Path\Path;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Mailer\Mailer as SymfonyMailer;
use Symfony\Component\Mailer\Transport;
use Symfony\Component\Mime\Email;

class Mailer
{
    /**
     * The Symfony Mailer instance, lazy-loaded on first send
     *
     * @var SymfonyMailer|null
     */
    protected ?SymfonyMailer $mailer;

    /**
     * The email message instance
     *
     * @var Email
     */
    protected Email $email;

    /**
     * Protocol used, e.g. 'smtp' or 'local'
     *
     * @var string
     */
    protected string $protocol = 'smtp';

    /**
     * Path to store local email files
     *
     * @var string
     */
    protected string $localPath = DIR_STORAGE . 'mailer';

    /**
     * Mailer configuration loaded from config files/env
     *
     * @var array
     */
    private array $config;

    /**
     * Mailer constructor.
     *
     * Loads mailer configuration and initializes Email instance.
     * Sets local storage path if configured.
     */
    public function __construct()
    {
        $this->config = config('mailer');

        $this->email = new Email();

        if (!empty($this->config['local_path'])) {
            $this->localPath = $this->config['local_path'];
        }
    }

    /**
     * Set the "From" address(es) for the email.
     *
     * @param array|string $address Email address or array of addresses
     *
     * @return $this
     */
    public function from(array|string $address)
    {
        $this->email->from(...(array)$address);

        return $this;
    }

    /**
     * Set the "To" address(es) for the email.
     *
     * @param array|string $address Email address or array of addresses
     *
     * @return $this
     */
    public function to(array|string $address)
    {
        $this->email->to(...(array)$address);

        return $this;
    }

    /**
     * Add additional "To" recipients.
     *
     * @param array|string $address Email address or array of addresses
     *
     * @return $this
     */
    public function addTo(array|string $address)
    {
        $this->email->addTo(...(array)$address);

        return $this;
    }

    /**
     * Set the email subject.
     *
     * @param string $subject
     *
     * @return $this
     */
    public function subject(string $subject)
    {
        $this->email->subject($subject);

        return $this;
    }

    /**
     * Set HTML content from a view template.
     *
     * @param string $view View template name
     * @param array $data Data to pass to the view
     *
     * @return $this
     */
    public function view(string $view, array $data = [])
    {
        $this->email->html(view($view, $data));

        return $this;
    }

    /**
     * Set raw HTML body content.
     *
     * @param string $body
     *
     * @return $this
     */
    public function html(string $body)
    {
        $this->email->html($body);

        return $this;
    }

    /**
     * Set plain text body content.
     *
     * @param string $body
     *
     * @return $this
     */
    public function text(string $body)
    {
        $this->email->text($body);

        return $this;
    }

    /**
     * Set CC (carbon copy) recipients.
     *
     * @param array|string $address Email address or array of addresses
     *
     * @return $this
     */
    public function cc(array|string $address)
    {
        $this->email->cc(...(array)$address);

        return $this;
    }

    /**
     * Add CC recipients.
     *
     * @param array|string $address Email address or array of addresses
     *
     * @return $this
     */
    public function addCc(array|string $address)
    {
        $this->email->addCc(...(array)$address);

        return $this;
    }

    /**
     * Set BCC (blind carbon copy) recipients.
     *
     * @param array|string $address Email address or array of addresses
     *
     * @return $this
     */
    public function bcc(array|string $address)
    {
        $this->email->bcc(...(array)$address);

        return $this;
    }

    /**
     * Add BCC recipients.
     *
     * @param array|string $address Email address or array of addresses
     *
     * @return $this
     */
    public function addBcc(array|string $address)
    {
        $this->email->addBcc(...(array)$address);

        return $this;
    }

    /**
     * Attach a file from path.
     *
     * @param string $filePath Path to the file to attach
     * @param string|null $filename Optional filename to send
     * @param string|null $contentType Optional content type of the attachment
     *
     * @return $this
     */
    public function attachFromPath(string $filePath, ?string $filename = null, ?string $contentType = null)
    {
        $this->email->attachFromPath($filePath, $filename, $contentType);

        return $this;
    }

    /**
     * Attach raw data as a file.
     *
     * @param string $data Raw data to attach
     * @param string $filename Filename for the attachment
     * @param string|null $contentType Optional content type
     *
     * @return $this
     */
    public function attach(string $data, string $filename, string $contentType = null)
    {
        $this->email->attach($data, $filename, $contentType);

        return $this;
    }

    /**
     * Add a single header to the email.
     *
     * @param string $name Header name
     * @param string $value Header value
     *
     * @return $this
     */
    public function header(string $name, string $value)
    {
        $this->email->getHeaders()->addTextHeader($name, $value);
        return $this;
    }

    /**
     * Add multiple headers to the email.
     *
     * @param array $headers Associative array of headers name => value
     *
     * @return $this
     */
    public function headers(array $headers)
    {
        foreach ($headers as $name => $value) {
            $this->header($name, $value);
        }

        return $this;
    }

    /**
     * Set reply-to address(es).
     *
     * @param array|string $address Email address or array of addresses
     *
     * @return $this
     */
    public function replyTo(array|string $address)
    {
        $this->email->replyTo(...(array)$address);

        return $this;
    }

    /**
     * Set the email priority.
     *
     * @param int $priority Priority level (1 = Highest, 2 = High, 3 = Normal, 4 = Low, 5 = Lowest)
     *
     * @return $this
     */
    public function priority(int $priority)
    {
        $this->email->priority($priority);

        return $this;
    }

    /**
     * Replace the underlying Email object.
     *
     * @param Email $email
     *
     * @return $this
     */
    public function setEmail(Email $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the underlying Email object.
     *
     * @return Email
     */
    public function getEmail(): Email
    {
        return $this->email;
    }

    /**
     * Send the email.
     *
     * Sends via SMTP or writes to local file based on protocol.
     * Throws RuntimeException on failure in non-production environments.
     *
     * @return bool True on success, false on failure (in production)
     *
     * @throws \RuntimeException
     */
    public function send(): bool
    {
        $this->setUpProtocol();

        if ($this->isLocal()) {
            // Send email to local file if protocol is 'local' to avoid errors with invalid DSN
            return $this->send2local();
        }

        try {
            if (!isset($this->mailer)) {
                // Lazy-load SymfonyMailer to avoid DSN errors when using 'local' protocol
                $transport = Transport::fromDsn($this->buildDsn());
                $this->mailer = new SymfonyMailer($transport);
            }

            $this->validateBeforeSend();
            $this->mailer->send($this->email);
            return true;
        } catch (\Throwable $e) {
            if (config('app.env', 'production') === 'production') {
                return false;
            }

            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Save the email message to a local file.
     *
     * Filename format: {uuid_without_dashes}_{Y-m-d-H-i-s}_mail.local.txt
     *
     * @return bool True if file write succeeded, false otherwise
     */
    public function send2local(): bool
    {
        $this->validateBeforeSend();

        $message = $this->email->toString();

        $file = str_replace('-', '', Uuid::uuid4()) . '_' . date('Y-m-d-H-i-s') . '_' . 'mail.local.txt';

        return FileSystem::fwrite(Path::join($this->localPath, $file), $message);
    }

    /**
     * Validate and fill missing required fields before sending.
     *
     * Sets from and to addresses from config if not already set.
     *
     * @return void
     */
    private function validateBeforeSend(): void
    {
        if (!$this->email->getFrom()) {
            $this->email->from($this->config['from']);
        }

        if (!$this->email->getTo()) {
            $this->email->to($this->config['to']);
        }
    }

    /**
     * Check if protocol is 'local'.
     *
     * @return bool
     */
    private function isLocal(): bool
    {
        return $this->protocol === 'local';
    }

    /**
     * Initialize the protocol from configuration.
     *
     * If a protocol is defined in the configuration, it is used to overwrite the default 'smtp'.
     * Otherwise, the default protocol value is stored back into the config array.
     *
     * This ensures that `$this->protocol` is always in sync with configuration
     * before building the DSN or performing protocol-specific checks.
     *
     * @return void
     */
    private function setUpProtocol(): void
    {
        if (!empty($this->config['protocol'])) {
            $this->protocol = $this->config['protocol'];
        } else {
            $this->config['protocol'] = $this->protocol;
        }
    }

    /**
     * Build the DSN string for the mailer transport.
     *
     * @throws \InvalidArgumentException If required config keys are missing.
     *
     * @return string
     */
    private function buildDsn(): string
    {
        $this->setUpProtocol();

        $requiredKeys = ['user', 'pass', 'host', 'port'];

        foreach ($requiredKeys as $key) {
            if (empty($this->config[$key])) {
                throw new \InvalidArgumentException("Mailer config missing required key: {$key}");
            }
        }

        $dsn = sprintf('%s://%s:%s@%s:%s', $this->config['protocol'], $this->config['user'], $this->config['pass'], $this->config['host'], $this->config['port']);

        if (!empty($this->config['encryption'])) {
            $dsn .= '?encryption=' . $this->config['encryption'];
        }

        return $dsn;
    }
}
