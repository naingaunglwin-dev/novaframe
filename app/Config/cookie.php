<?php

return [

    /*
     |------------------------------------------------------------------------
     | Cookie Path
     |------------------------------------------------------------------------
     |
     | This value determines the path on the server where the cookie will be
     | available. A cookie with a path of '/' will be available within the
     | entire domain.
     |
     */
    'path' => env('COOKIE_PATH', '/'),

    /*
     |------------------------------------------------------------------------
     | Cookie Expiration Time
     |------------------------------------------------------------------------
     |
     | This value determines the expiration time of the cookie in seconds.
     | By default, it is set to one week (604800 seconds). After this time,
     | the cookie will expire and will be removed from the client's browser.
     |
     */
    'expire' => env('COOKIE_EXPIRE', 604800),

    /*
     |------------------------------------------------------------------------
     | Cookie Domain
     |------------------------------------------------------------------------
     |
     | This value determines the domain that the cookie is available to.
     | If not specified, it defaults to the current domain. You can set it to
     | a specific domain to make the cookie accessible to subdomains.
     |
     */
    'domain' => env('COOKIE_DOMAIN', ''),

    /*
     |------------------------------------------------------------------------
     | Cookie Secure
     |------------------------------------------------------------------------
     |
     | This value determines whether the cookie should only be sent over
     | secure HTTPS connections. It is recommended to enable this in
     | production environments to enhance security.
     |
     */
    'secure' => env('COOKIE_SECURE', false),

    /*
     |------------------------------------------------------------------------
     | Cookie HttpOnly
     |------------------------------------------------------------------------
     |
     | This value determines whether the cookie should be accessible only
     | through the HTTP protocol. Enabling this helps mitigate the risk of
     | client-side script accessing the cookie data, which can help protect
     | against XSS (Cross-Site Scripting) attacks.
     |
     */
    'httponly' => env('COOKIE_HTTPONLY', true),

];
