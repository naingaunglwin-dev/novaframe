<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Mailer Protocol
    |--------------------------------------------------------------------------
    |
    | The protocol to be used by the mailer. Common values include 'smtp',
    | 'sendmail', or 'local' for saving emails locally without sending.
    |
    */
    'protocol' => env('MAILER_PROTOCOL', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer DSN
    |--------------------------------------------------------------------------
    |
    | Optional full DSN string for transport configuration. If provided, this
    | will override individual host, port, user, and pass settings.
    |
    */
    'dsn' => env('MAILER_DSN'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Host
    |--------------------------------------------------------------------------
    |
    | The hostname or IP address of the mail server. This is used when the
    | protocol is SMTP or similar that requires a server connection.
    |
    */
    'host' => env('MAILER_HOST'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Port
    |--------------------------------------------------------------------------
    |
    | The port number on which the mail server is listening. Common SMTP ports
    | are 587 (TLS), 465 (SSL), and 25 (non-secure).
    |
    */
    'port' => env('MAILER_PORT', 587),

    /*
    |--------------------------------------------------------------------------
    | Mailer Username
    |--------------------------------------------------------------------------
    |
    | The username used to authenticate with the mail server. Typically this is
    | the email account or service user.
    |
    */
    'user' => env('MAILER_USER'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Password
    |--------------------------------------------------------------------------
    |
    | The password or secret key used along with the username to authenticate
    | against the mail server.
    |
    */
    'pass' => env('MAILER_PASS'),

    /*
    |--------------------------------------------------------------------------
    | Mail Encryption Protocol
    |--------------------------------------------------------------------------
    |
    | This option defines the encryption method used when sending emails.
    | Common options include:
    |   - 'tls'  : StartTLS (typically used on port 587)
    |   - 'ssl'  : Implicit SSL (typically used on port 465)
    |   - ''     : No encryption (not recommended in production)
    |
    | The encryption setting is appended to the mailer DSN string when sending
    | via SMTP. Make sure your mail server supports the chosen method.
    |
    */
    'encryption' => env('MAILER_ENCRYPTION', 'tls'),

    /*
    |--------------------------------------------------------------------------
    | Default "From" Address
    |--------------------------------------------------------------------------
    |
    | The email address used as the default sender in outgoing emails.
    | This is used if no explicit from address is set on the email.
    |
    */
    'from' => env('MAILER_FROM'),

    /*
    |--------------------------------------------------------------------------
    | Local Mail Storage Path
    |--------------------------------------------------------------------------
    |
    | The filesystem path where emails will be saved if the 'local' protocol
    | is used. This enables saving mails to disk instead of sending.
    |
    */
    'local_path' => env('MAILER_LOCAL_PATH', DIR_STORAGE . 'mailer'),
];
