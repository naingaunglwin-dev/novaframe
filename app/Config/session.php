<?php

return [

    /*
     |------------------------------------------------------------------------
     | Session Name
     |------------------------------------------------------------------------
     |
     | This value determines the name of the session used in the application.
     | It is the name of the session cookie that will be sent to the client's browser.
     |
     */
    'name' => env('SESSION_NAME', '_nova_php_session'),

    /*
     |------------------------------------------------------------------------
     | Session Secure
     |------------------------------------------------------------------------
     |
     | This value determines whether the session cookie should only be sent
     | over secure HTTPS connections. It is recommended to keep this enabled
     | in production environments for added security.
     |
     */
    'secure' => (bool) env('SESSION_SECURE', true),

    /*
     |------------------------------------------------------------------------
     | Session Http Only
     |------------------------------------------------------------------------
     |
     | This value determines whether the session cookie should be accessible
     | only through the HTTP protocol. Enabling this helps mitigate the risk
     | of client-side script accessing the protected cookie data.
     |
     */
    'httpOnly' => (bool) env('SESSION_HTTP_ONLY', true),

    /*
     |------------------------------------------------------------------------
     | Session SameSite
     |------------------------------------------------------------------------
     |
     | This value determines the SameSite attribute for the session cookie.
     | It helps prevent CSRF (Cross-Site Request Forgery) attacks by not
     | sending the cookie along with cross-site requests.
     | Possible values are "Lax", "Strict", or "None".
     |
     */
    'sameSite' => env('SESSION_SAME_SITE', 'Strict'),

    /*
     |------------------------------------------------------------------------
     | Session TimeOut (in seconds)
     |------------------------------------------------------------------------
     |
     | This value determines the lifetime of the session in seconds.
     | After this period of inactivity, the session will expire and the user
     | will be required to log in again. This helps in managing the security
     | of the user sessions by limiting the duration of authenticated sessions.
     |
     */
    'timeOut' => (int) env('SESSION_TIME_OUT', 3600),

];
