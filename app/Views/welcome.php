<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>NOVAFRAME</title>

    <link rel="icon" href="<?php echo baseUrl('nova_icon/favicon.png') ?>">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <link rel="stylesheet" href="<?php echo baseUrl('welcome/css/material.css') ?>">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter&display=swap');

        @import url('https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,100..800;1,100..800&display=swap');

        :root {
            --txt-blue-color: #1F51FF;
            --txt-white-color: #ffffff;
            --txt-secondary-color: #949494;
        }

        i {
            font-size: .75rem;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: #111111;
            color: white;
        }

        ::-webkit-scrollbar {
            width: 2px;
            height: 2px;
        }

        ::-webkit-scrollbar-track {
            background-color: var(--txt-secondary-color);
        }

        ::-webkit-scrollbar-thumb {
            background: #1163d2;
            border-radius: 10px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--txt-blue-color);
        }

        section {
            max-width: 1200px;
            height: 90vh;
            margin: 80px auto 0 auto;
            display: grid;
            grid-template-columns: 1fr 4fr;
            padding: 20px 30px;
            box-sizing: border-box;
        }

        div.menu-bar-icon {
            position: absolute;
            top: 10px;
            right: 10px;
            display: none;
        }

        div.menu-bar-icon > i {
            font-size: 2rem;
        }

        @media screen and (max-width: 1130px) {
            section {
                grid-template-columns: 2fr 6fr;
            }
        }

        section > div.left-side {
            grid-column: 1;
            width: 250px;
            position: relative;
        }

        section > div.left-side div.mobile-menu-close {
            display: none;
        }

        @media screen and (max-width: 780px) {
            section {
                display: block;
                padding: 20px 0;
            }

            section > div.left-side {
                display: none;
            }

            section > div.left-side > nav > div.category {
                border-left: none!important;
            }

            section > div.right-side {
                padding: 0 15px!important;
            }

            div.menu-bar-icon {
                display: block;
            }
        }

        section > div.left-side.mobile {
            display: block;
            position: fixed;
            border-right: 1px solid rgba(255, 255, 255, 0.1);
            padding: 20px;
            top: 0;
            z-index: 999;
            width: 0;
            background: #111111;
            height: 100%;
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: width 0.5s ease, opacity 0.5s ease, visibility 0.5s ease;
        }

        section > div.left-side.mobile-active {
            width: 250px;
            opacity: 1;
            visibility: visible;
            pointer-events: auto;
        }

        section > div.left-side.mobile div.mobile-menu-close {
            display: block;
            position: absolute;
            right: 15px;
            top: 15px;
        }

        section > div.left-side div.mobile-menu-close i {
            font-size: 20pt;
        }

        section > div.left-side > nav > p.category-heading {
            font-size: 14pt;
            margin: 20px 0;
        }

        section > div.left-side > nav > p.category-heading:hover {
            cursor: pointer;
        }

        section > div.left-side > nav > div.category {
            padding: 0 30px;
            border-left: 2px solid var(--txt-secondary-color);
        }

        section > div.left-side > nav > div.category[a.active] {
            border-left: 2px solid var(--txt-blue-color);
        }

        section > div.left-side > nav > div.category a {
            text-decoration: none;
            color: var(--txt-secondary-color);
            font-size: .875rem;
            padding: 13px 0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        section > div.left-side > nav > div.category a.sub-category-holder {
            color: var(--txt-white-color);
        }

        section > div.left-side > nav > div.category a.active {
            color: var(--txt-blue-color);
        }

        section > div.left-side > nav > div.category a:hover {
            color: var(--txt-white-color);
            cursor: pointer;
        }

        section > div.left-side > nav > div.category a > i {
            transition: transform 0.3s;
        }

        section > div.left-side > nav > div.category a > i.rotate {
            transform: rotate(180deg);
        }

        section > div.left-side > nav > div.category > a.open > i.fa-chevron-right {
            transform: rotate(90deg);
        }

        section > div.left-side > nav > div.category > div.sub-category {
            opacity: 0;
            max-height: 0;
            padding: 0 0 0 12px;
            pointer-events: none;
            transition: all 0.5s;
        }

        section > div.left-side > nav > div.category > div.sub-category.active {
            opacity: 1;
            max-height: 400px;
            pointer-events: auto;
        }

        section > div.left-side > nav > div.category > div.sub-category > div.sub-category-content {
            border-left: 2px solid var(--txt-secondary-color);
            padding: 0 11px;
        }

        section > div.right-side {
            grid-column: 2 / span 1;
            padding: 0 40px;
            overflow-y: auto;
            font-size: .900rem;
        }

        section > div.right-side > div {
            display: none;
            opacity: 0;
        }

        @-webkit-keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        section > div.right-side > div.active {
            display: block;
            -webkit-animation: fadeIn 0.8s;
            animation: fadeIn 0.8s;
            opacity: 1;
        }

        section > div.right-side h1 {
            text-align: center;
            margin-bottom: 3.5rem;
        }

        section > div.right-side div.content-group {
            padding: 0 5px;
        }

        section > div.right-side div.content-group > .content-title {
            font-size: 1.5rem;
            padding-bottom: 10px;
        }

        section > div.right-side  ul {
            padding-inline-start: 30px!important;
        }

        section > div.right-side ul > li {
            margin: 15px 0;
        }
    </style>

    <!-- Predefined css class -->
    <style>
        .sub-heading-1 {
            font-size: 4rem;
        }

        .sub-heading-2 {
            font-size: 2rem;
        }

        .sub-heading-3 {
            font-size: 1.5rem;
        }

        .sub-heading-4 {
            font-size: 1rem;
        }

        .separate-content {
            width: 96%;
            margin: 40px auto;
            border: none;
            height: 1px;
            background-color: var(--txt-secondary-color);
            opacity: 0.5;
        }

        .link {
            color: var(--txt-blue-color);
            text-decoration: none;
        }

        .link:hover {
            cursor: pointer;
        }

        .content {
            line-height: 1.5rem;
        }

        .text-center {
            text-align: center;
        }

        .text-bold {
            font-weight: bold;
        }

        .border-blue {
            border-left: 2px solid var(--txt-blue-color)!important;
        }

        pre {
            background: #1e2123;
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 10px!important;
        }

        .code-box {
            background: transparent;
            font-family: "JetBrains Mono", monospace;
            font-size: 13px;
        }

        .code-box::-webkit-scrollbar {
            display: none;
        }

        .table {
            border-collapse: separate;
            border-spacing: 0;
            width: 100%;
            margin: 20px 0;
            border-radius: 7px!important;
            overflow: hidden;
        }

        .table, .table th, .table td {
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .table th {
            background-color: #48484d;
            padding: 10px 0;
            border-bottom: none;
            font-weight: normal;
            font-size: 1rem;
        }

        .table td {
            padding: 10px 15px;
        }

        .table th:first-child,
        .table td:first-child {
            border-left: none;
        }

        .table th:last-child,
        .table td:last-child {
            border-right: none;
        }

        .table tr:first-child th {
            border-top: none;
        }

        .table tr:last-child td {
            border-bottom: none;
        }

        .table tr td:nth-child(1) {
            white-space: nowrap;
        }

        @media screen and (max-width: 1150px) {
            .table.request tr td:nth-child(1) {
                white-space: normal;
            }
        }

        .table tr td:nth-child(2) {
            line-height: 30px;
        }

        .method {
            background-color: rgba(255, 255, 255, 0.2);
            padding: 5px;
            border-radius: 8px;
            font-size: 0.8rem;
            line-height: 30px;
            margin: 5px 2px;
        }

        .return-type {
            background-color: var(--txt-blue-color);
            border-radius: 6px;
            padding: 5px;
            margin: 5px 2px;
            line-height: 30px;
        }

        .language-identify {
            padding: 20px !important;
            background: #111111;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            font-family: "Inter", sans-serif;
        }

        .language-identify > div {
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .language-identify i {
            font-size: 15pt;
        }

        .language-identify i.fa-terminal {
            font-size: 9pt;
        }
    </style>
</head>

<body>
    <section>
        <div class="menu-bar-icon">
            <i class="fa-solid fa-bars" id="menu"></i>
        </div>
        <div class="left-side" id="left-side">
            <div class="mobile-menu-close">
                <i class="fa-solid fa-chevron-left" id="menu-close"></i>
            </div>
            <nav class="desktop">
                <p class="category-heading">Category</p>

                <div class="category border-blue">
                    <a href="#home" class="active">
                        <span>Home</span>
                        <i class="fa-solid fa-chevron-right rotate"></i>
                    </a>
                </div>

                <div class="category">
                    <a href="#bootstrap">
                        <span>Bootstrap</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>

                <div class="category">
                    <a href="javascript:void(0);" class="sub-category-holder">
                        <span>HTTP</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>

                    <div class="sub-category">
                        <div class="sub-category-content">
                            <a href="#http-request">
                                <span>Request</span>
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>

                        <div class="sub-category-content">
                            <a href="#http-response">
                                <span>Response</span>
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="category">
                    <a href="#middleware">
                        <span>Middleware</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>

                <div class="category">
                    <a href="javascript:void(0);" class="sub-category-holder">
                        <span>Route</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>

                    <div class="sub-category">
                        <div class="sub-category-content">
                            <a href="#route-basic">
                                <span>Basic</span>
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>

                        <div class="sub-category-content">
                            <a href="#route-advance">
                                <span>Advance</span>
                                <i class="fa-solid fa-chevron-right"></i>
                            </a>
                        </div>
                    </div>
                </div>

                <div class="category">
                    <a href="#contact-us">
                        <span>Contact Us</span>
                        <i class="fa-solid fa-chevron-right"></i>
                    </a>
                </div>
            </nav>
        </div>

        <div class="right-side">
            <div id="home" class="active">
                <h1 class="sub-heading-2">Welcome to NOVAFRAME</h1>

                <p class="sub-heading-3">What is NOVAFRAME ?</p>

                <p class="content">
                    NOVAFRAME is an application development basic framework with MVC pattern that come with a simple routing system.
                    It aims to simplify the development  process by providing a structured approach for handling <b>routes, middlewares, events, locales, sessions, cookies, dotenv, controllers, models and views</b>.
                    It is inspired by various PHP Frameworks, especially from <a href="https://laravel.com" target="_blank" class="link text-bold">Laravel</a>, <a href="https://codeigniter.com" target="_blank" class="link text-bold">Codeigniter</a> and <a href="https://phplucidframe.com" target="_blank" class="link text-bold">PHPLucidFrame</a>.
                </p>

                <hr class="separate-content">

                <p class="sub-heading-4 text-center">This page is rendered from <span class="method">app/Routes/web.php</span> and action is <span class="method">app/Views/welcome.php</span></p>

                <hr class="separate-content">
            </div>

            <div id="bootstrap">
                <h1 class="sub-heading-2">Framework Bootstrapping</h1>

                <p class="sub-heading-3">What is bootstrapping ?</p>

                <p class="content">
                    Bootstrapping is the process of setting up the initial configuration and environment for your framework. This document outlines the steps involved in bootstrapping your framework and provides guidance on customizing the bootstrapping process for different environments.
                </p>

                <hr class="separate-content">

                <p class="sub-heading-3">Overview</p>

                <p class="content">
                    The bootstrapping process is divided into several stages, each responsible for a specific aspect of framework initialization. These stages ensure that the framework is properly configured and ready for use in different environments.
                </p>

                <hr class="separate-content">

                <p class="sub-heading-3">Bootstrap File</p>

                <p class="content">
                    The primary bootstrapping file of our framework is <b>bootstrap.php</b>, located in the app/Bootstrap directory. This file serves as the entry point for initializing the framework and executing pre-launch tasks.
                </p>

<pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i>app/Bootstrap/bootstrap.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

use Nova\Facade\Bootstrap;

Bootstrap::before(
    fn () => Bootstrap::web(function () {
        // any bootstrapping process before application is ready to launch on web
    })->cli(function () {
        // any bootstrapping process before application is ready to launch on cli
    })->autoload(
        [
            APP_PATH . 'Routes/web.php',
            // Do not remove above files
            // You can start register your autoload files here
        ]
    )
);

</code>
</pre>

                <hr class="separate-content">

                <p class="sub-heading-4 text-center">This page is rendered from <span class="method">app/Routes/web.php</span> and action is <span class="method">app/Views/welcome.php</span></p>

                <hr class="separate-content">

            </div>

            <div id="http-request">
                <h1 class="sub-heading-2">HTTP Request</h1>

                <p class="sub-heading-3">What is Request ?</p>

                <p class="content">
                    In web development, the term "Request" refers to the data and context that a client (such as a web browser or API consumer) sends to a server. This is part of the HTTP (Hypertext Transfer Protocol) communication process, where clients make requests for resources and servers respond to those requests. Understanding how requests work is fundamental to developing web applications, as it involves handling various aspects such as input data, headers, cookies, and more.
                </p>

                <hr class="separate-content">

                <p class="sub-heading-3">Overview</p>

                <p class="content">
                    The `IncomingRequest` class represents an incoming HTTP request and provides methods for accessing various request attributes such as URI, method, headers, and data. The `IncomingRequest` class extends the `Request` class and implements the IncomingRequestInterface. It encapsulates the details of an incoming HTTP request and provides a structured way to access request attributes.
                </p>

                <hr class="separate-content">

                <p class="sub-heading-3">Usage</p>

<pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i>Example.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

use Nova\HTTP\IncomingRequest;

// Create a new instance of IncomingRequest
$request     = new IncomingRequest(); //or IncomingRequest::createFromGlobals();

// Access request attributes
$scheme      = $request->getScheme();

$host        = $request->getHost();

$method      = $request->getMethod();

$uri         = $request->getRequestUri();

$queryString = $request->getQueryString();

// and more...

</code>
</pre>

                <hr class="separate-content">

                <p class="sub-heading-3">Class Methods List</p>

                <table class="table request">
                    <tr>
                        <th>Method</th>
                        <th>Return Type</th>
                        <th>Definition</th>
                    </tr>

                    <tr>
                        <td><span class="method">getScheme()</span></td>
                        <td><span class="return-type">string</span> or <span class="return-type">null</span></td>
                        <td>Returns the scheme (HTTP or HTTPS) of the request.</td>
                    </tr>

                    <tr>
                        <td><span class="method">getProtocolVersion()</span></td>
                        <td><span class="return-type">string</span> or <span class="return-type">null</span></td>
                        <td>Returns the HTTP protocol version of the request.</td>
                    </tr>

                    <tr>
                        <td><span class="method">getHost()</span></td>
                        <td><span class="return-type">string</span> or <span class="return-type">null</span></td>
                        <td>Returns the host name of the request.</td>
                    </tr>

                    <tr>
                        <td><span class="method">getPort()</span></td>
                        <td><span class="return-type">integer</span> or <span class="return-type">null</span></td>
                        <td>Returns the port number of the request.</td>
                    </tr>

                    <tr>
                        <td><span class="method">getServerAddress()</span></td>
                        <td><span class="return-type">string</span> or <span class="return-type">null</span></td>
                        <td>Returns the server IP address of the request.</td>
                    </tr>

                    <tr>
                        <td><span class="method">getBaseUrl(string $url = '')</span></td>
                        <td><span class="return-type">string</span> or <span class="return-type">null</span></td>
                        <td>Returns the base URL of the request.</td>
                    </tr>

                    <tr>
                        <td><span class="method">getFullUrl()</span></td>
                        <td><span class="return-type">string</span> or <span class="return-type">null</span></td>
                        <td>Returns the full URL of the request.</td>
                    </tr>

                    <tr>
                        <td><span class="method">getRequestUri(bool $query = false)</span></td>
                        <td><span class="return-type">string</span> or <span class="return-type">null</span></td>
                        <td>Returns the request URI of the request.</td>
                    </tr>

                    <tr>
                        <td><span class="method">getMethod(bool $lowercase = false)</span></td>
                        <td><span class="return-type">string</span> or <span class="return-type">null</span></td>
                        <td>Returns the HTTP method of the request.</td>
                    </tr>

                    <tr>
                        <td><span class="method">getQueryString()</span></td>
                        <td><span class="return-type">string</span> or <span class="return-type">null</span></td>
                        <td>Returns the query string of the request.</td>
                    </tr>

                    <tr>
                        <td><span class="method">getData(string $name, string $method = null, mixed $default = null, bool $sanitize = true)</span></td>
                        <td><span class="return-type">string</span> or <span class="return-type"<span class="method">null</span></td>
                        <td>Returns request data (e.g., GET, POST, PUT, DELETE) based on the method specified.</td>
                    </tr>

                    <tr>
                        <td><span class="method">file(string $name, bool $sanitize = true)</span></td>
                        <td><span class="return-type">string</span> or <span class="return-type">null</span> </td>
                        <td>Returns file data from the request.</td>
                    </tr>

                    <tr>
                        <td><span class="method">getRouteParam(string $key)</span></td>
                        <td><span class="return-type">string</span> or <span class="return-type">null</span> </td>
                        <td>Returns a route parameter from the request.</td>
                    </tr>
                </table>

                <hr class="separate-content">

                <p class="sub-heading-4 text-center">This page is rendered from <span class="method">app/Routes/web.php</span> and action is <span class="method">app/Views/welcome.php</span></p>

                <hr class="separate-content">

            </div>

            <div id="http-response">
                <h1 class="sub-heading-2">Response Class</h1>

                <p class="sub-heading-3">What is Response ?</p>

                <p class="content">
                    In web development, a response is the server's reply to a client's request. The response includes a status code, headers, and a body. The status code indicates the result of the request (e.g., 200 for success, 404 for not found). Headers provide additional information (e.g., content type, caching policies), and the body contains the actual content (e.g., HTML, JSON).
                </p>

                <hr class="separate-content">

                <p class="sub-heading-3">Overview</p>

                <p class="content">
                    The Response class in the NOVA FRAME framework is responsible for representing and managing HTTP responses. It provides methods for setting the response body, headers, status codes, and content types, as well as for sending the response to the client. This class is an implementation of the ResponseInterface, ensuring consistency and interoperability within the framework.
                </p>

                <hr class="separate-content">

                <p class="sub-heading-3">Usage</p>

                <p class="content">Here is an example of how to use the Response class in your application:</p>

<pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i> app/HTTP/Middlewares/Tester.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

use Nova\HTTP\Response;

$response = new Response('Hello, World!', 200, ['Content-Type' => 'text/plain']);
$response->send();

</code>
</pre>

                <p class="content">In this example, a new Response object is created with the body content "Hello, World!", a status code of 200, and a content type of text/plain. The response is then sent to the client.</p>

                <hr class="separate-content">

                <p class="sub-heading-3">Class Methods List</p>

                <table class="table">
                    <tr>
                        <th>Method</th>
                        <th>Return Type</th>
                        <th>Definition</th>
                    </tr>

                    <tr>
                        <td><span class="method">setContentType(string $contentType)</span></td>
                        <td><span class="return-type">Response</span></td>
                        <td>Sets the content type of the response.</td>
                    </tr>
                    <tr>
                        <td><span class="method">setBody(string $content)</span></td>
                        <td><span class="return-type">Response</span></td>
                        <td>Sets the body content of the response.</td>
                    </tr>
                    <tr>
                        <td><span class="method">setHeader(string $name, string $value)</span></td>
                        <td><span class="return-type">Response</span></td>
                        <td>Sets a single header for the response.</td>
                    </tr>
                    <tr>
                        <td><span class="method">setHeaders(array $headers)</span></td>
                        <td><span class="return-type">Response</span></td>
                        <td>Sets multiple headers for the response.</td>
                    </tr>
                    <tr>
                        <td><span class="method">setStatus(int $status)</span></td>
                        <td><span class="return-type">Response</span></td>
                        <td>Sets the HTTP status code of the response.</td>
                    </tr>
                    <tr>
                        <td><span class="method">sendHeaders()</span></td>
                        <td><span class="return-type">Response</span></td>
                        <td>Sends the headers of the response to the client.</td>
                    </tr>
                    <tr>
                        <td><span class="method">sendBody()</span></td>
                        <td><span class="return-type">Response</span></td>
                        <td>Sends the body content of the response to the client.</td>
                    </tr>
                    <tr>
                        <td><span class="method">send()</span></td>
                        <td><span class="return-type">Response</span></td>
                        <td>Sends the headers and body content of the response to the client.</td>
                    </tr>
                    <tr>
                        <td><span class="method">redirect(string $url, int $status = 302)</span></td>
                        <td><span class="return-type">Response</span></td>
                        <td>Redirects the client to a different URL.</td>
                    </tr>
                </table>

            </div>

            <div id="middleware">
                <h1 class="sub-heading-2">Middleware</h1>

                <p class="sub-heading-3">What is Middleware ?</p>

                <p class="content">
                    Middleware is a type of software that acts as a bridge between an operating system or database and applications, especially on a network. In the context of a web framework, middleware functions as a pipeline where each middleware component can inspect, modify, or terminate HTTP requests before they reach your application’s route handlers.<br>
                    Middleware provides a structured way to handle common tasks such as:
                </p>

                <ul>
                    <li>Authentication</li>
                    <li>Logging</li>
                    <li>Request Validation</li>
                    <li>Modifying Request data</li>
                </ul>

                <p class="content">Each middleware has access to the request and response objects and can pass control to the next middleware in the stack.</p>

                <hr class="separate-content">

                <p class="sub-heading-3">Overview</p>

                <p class="content">
                    Middleware in NOVAFRAME allows you to filter HTTP requests entering your application. Middleware is executed before your route handlers, allowing you to perform operations such as authentication, logging, and request modification.
                </p>

                <hr class="separate-content">

                <p class="sub-heading-3">Creating Middleware</p>

                <p class="content">You can create a new middleware using the command-line interface (CLI). To create a middleware named Tester, run the following command:</p>

<pre>
<div class="language-identify">
    <div><i class="fa-solid fa-terminal"></i>Terminal</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-bash code-box">
    php novaframe make:middleware Tester

</code>
</pre>

                <p class="content">This command will generate a new middleware class in the app/HTTP/Middlewares directory. The generated file will look like this:</p>

<pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i> app/HTTP/Middlewares/Tester.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

namespace App\HTTP\Middlewares;

use Nova\Middleware\Middleware;

class Tester extends Middleware
{
    /**
     * Handle the middleware.
     *
     * This method should be implemented by concrete middleware classes to process HTTP requests.
     *
     * @param \Nova\HTTP\IncomingRequest $request The HTTP request object.
     * @param \Closure $next The next middleware closure.
     * @return mixed The result of processing the middleware.
     */
    public function handle(\Nova\HTTP\IncomingRequest $request, \Closure $next): mixed
    {
        return $next($request);
    }
}

</code>
</pre>

                <hr class="separate-content">

                <p class="sub-heading-3">Global Middleware</p>

                <p class="content">
                    If you want a middleware to be applied to all routes, you can register it globally in the application's bootstrapping process. This is done in the Kernel class, located at app/HTTP/Kernel.php. You can add middleware to the $middlewares array to ensure it is executed on every incoming HTTP request.
                </p>

<pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i>app/HTTP/Kernel.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

namespace App\HTTP;

class Kernel
{
    /**
     * The array of middleware classes registered in the HTTP kernel.
     * These middlewares are executed on every incoming HTTP request.
     *
     * @var array $middlewares
     */
    public array $middlewares = [
        // List your global middleware here
        \App\HTTP\Middlewares\Tester::class,
    ];
}

</code>
</pre>

                <hr class="separate-content">

                <p class="sub-heading-3">Middleware in Route Dispatch</p>

                <p class="content">
                    Middleware is integrated into the route dispatch process at the top level. When a request is dispatched, all middleware assigned to the route is executed in sequence before the request reaches the route handler.

                    By using middleware, you can keep your route handlers clean and focus on the core logic of your application while offloading repetitive tasks and checks to middleware.
                </p>

                <hr class="separate-content">

                <p class="sub-heading-4 text-center">This page is rendered from <span class="method">app/Routes/web.php</span> and action is <span class="method">app/Views/welcome.php</span></p>

                <hr class="separate-content">

            </div>

            <div id="route-basic">
                <h1 class="sub-heading-2">Route (Basic)</h1>

                <p class="sub-heading-3">What is Route ?</p>

                <p class="content">
                    A route in a web application is a mapping between an HTTP request (defined by a URL and an HTTP method) and a specific piece of code that should handle that request. Routes define how your application responds to client requests for specific endpoints. These endpoints can be URLs, which users access through their web browsers.
                </p>

                <hr class="separate-content">

                <p class="sub-heading-3">Overview</p>

                <p class="content">
                    The routing system in your framework allows you to define routes that map URLs to specific actions in your application. It uses a facade pattern to provide a simple, fluent API for defining routes. This document explains how to create routes, apply middleware, and group routes with a common URL prefix.
                </p>

                <hr class="separate-content">

                <p class="sub-heading-3">Key Concepts</p>

                <ul>
                    <li>URL Pattern: The URL that the route listens to. It can include dynamic segments to capture variable parts of the URL.</li>
                    <li>HTTP Method: The type of HTTP request (e.g., GET, POST, PUT, DELETE) that the route responds to.</li>
                    <li>Handler: The code that executes when the route is matched. This can be a controller action, a closure, or a view.</li>
                    <li>Middleware: Optional components that can process requests before they reach the handler, allowing for tasks such as authentication or logging.</li>
                </ul>

                <hr class="separate-content">

                <p class="sub-heading-3">Usage</p>

                <p class="content">For example, a route might map the URL /home with a GET request to the HomeController's index method:</p>

<pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i>app/Routes/web.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

Route::create('home', [\App\HTTP\Controllers\Home::class, 'index'], 'get', 'home');

// or

Route::get('home', [\App\HTTP\Controllers\Home::class, 'index'], 'home');
</code>
</pre>

                <p class="content">In this example:</p>
                <ul>
                    <li>'/home' is the URL pattern.</li>
                    <li>[\App\HTTP\Controllers\Home::class, 'index'] is the handler.</li>
                    <li>'GET' is the HTTP method.</li>
                    <li>'home' is an optional name for the route.</li>
                </ul>
                <p class="content">By defining routes, you can create a clear and organized structure for your application's endpoints, making it easier to manage and scale your web application.</p>

                <hr class="separate-content">

                <p class="sub-heading-3">Class Methods List</p>

                <table class="table">
                    <tr>
                        <th>Method</th>
                        <th>Parameter</th>
                        <th>Definition</th>
                    </tr>

                    <tr>
                        <td><span class="method">create()</span></td>
                        <td>
                            <span class="return-type">string</span>:$from,<br>
                            <span class="return-type">string</span><span class="return-type">array</span><span class="return-type">callable</span>:$to,<br>
                            <span class="return-type">string</span><span class="return-type">array</span>:$method,<br>
                            <span class="return-type">string</span><span class="return-type">null</span>:$name
                        </td>
                        <td>Create a route with given values</td>
                    </tr>

                    <tr>
                        <td><span class="method">get()</span></td>
                        <td>
                            <span class="return-type">string</span>:$from,<br>
                            <span class="return-type">string</span><span class="return-type">array</span><span class="return-type">callable</span>:$to,<br>
                            <span class="return-type">string</span><span class="return-type">null</span>:$name
                        </td>
                        <td>Create a route with HTTP GET method</td>
                    </tr>

                    <tr>
                        <td><span class="method">post()</span></td>
                        <td>
                            <span class="return-type">string</span>:$from,<br>
                            <span class="return-type">string</span><span class="return-type">array</span><span class="return-type">callable</span>:$to,<br>
                            <span class="return-type">string</span><span class="return-type">null</span>:$name
                        </td>
                        <td>Create a route with HTTP POST method</td>
                    </tr>

                    <tr>
                        <td><span class="method">delete()</span></td>
                        <td>
                            <span class="return-type">string</span>:$from,<br>
                            <span class="return-type">string</span><span class="return-type">array</span><span class="return-type">callable</span>:$to,<br>
                            <span class="return-type">string</span><span class="return-type">null</span>:$name
                        </td>
                        <td>Create a route with HTTP DELETE method</td>
                    </tr>

                    <tr>
                        <td><span class="method">put()</span></td>
                        <td>
                            <span class="return-type">string</span>:$from,<br>
                            <span class="return-type">string</span><span class="return-type">array</span><span class="return-type">callable</span>:$to,<br>
                            <span class="return-type">string</span><span class="return-type">null</span>:$name
                        </td>
                        <td>Create a route with HTTP PUT method</td>
                    </tr>

                    <tr>
                        <td><span class="method">patch()</span></td>
                        <td>
                            <span class="return-type">string</span>:$from,<br>
                            <span class="return-type">string</span><span class="return-type">array</span><span class="return-type">callable</span>:$to,<br>
                            <span class="return-type">string</span><span class="return-type">null</span>:$name
                        </td>
                        <td>Create a route with HTTP PATCH method</td>
                    </tr>

                    <tr>
                        <td><span class="method">head()</span></td>
                        <td>
                            <span class="return-type">string</span>:$from,<br>
                            <span class="return-type">string</span><span class="return-type">array</span><span class="return-type">callable</span>:$to,<br>
                            <span class="return-type">string</span><span class="return-type">null</span>:$name
                        </td>
                        <td>Create a route with HTTP HEAD method</td>
                    </tr>

                    <tr>
                        <td><span class="method">options()</span></td>
                        <td>
                            <span class="return-type">string</span>:$from,<br>
                            <span class="return-type">string</span><span class="return-type">array</span><span class="return-type">callable</span>:$to,<br>
                            <span class="return-type">string</span><span class="return-type">null</span>:$name
                        </td>
                        <td>Create a route with HTTP OPTIONS method</td>
                    </tr>

                    <tr>
                        <td><span class="method">any()</span></td>
                        <td>
                            <span class="return-type">string</span>:$from,<br>
                            <span class="return-type">string</span><span class="return-type">array</span><span class="return-type">callable</span>:$to,<br>
                            <span class="return-type">string</span><span class="return-type">null</span>:$name
                        </td>
                        <td>Create a route with all HTTP methods</td>
                    </tr>

                    <tr>
                        <td><span class="method">group()</span></td>
                        <td>
                            <span class="return-type">string</span>:$prefix,<br>
                            <span class="return-type">callable</span>:action
                        </td>
                        <td>Create a route group with given url prefix</td>
                    </tr>

                    <tr>
                        <td><span class="method">middleware()</span></td>
                        <td>
                            <span class="return-type">string</span><span class="return-type">array</span><span class="return-type">\Nova\Middleware\Middleware</span>:$middleware
                        </td>
                        <td>Add middleware to current route</td>
                    </tr>
                </table>

                <hr class="separate-content">

                <p class="sub-heading-4 text-center">This page is rendered from <span class="method">app/Routes/web.php</span> and action is <span class="method">app/Views/welcome.php</span></p>

                <hr class="separate-content">
            </div>

            <div id="route-advance">
                <h1 class="sub-heading-2">Route (Advance)</h1>

                <p class="sub-heading-3">Route to View</p>

                <p class="content">You can route to a view by specifying the view file name from the <span class="method">app/Views</span> path:</p>

<pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i>app/Routes/web.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

// This will go to app/Views/rule.php if you call `/rules` from browser
Route::get('rules', 'rule');

</code>
</pre>

                <p class="content">You can also render multiple views using a callback function with the helper method <span class="method">view()</span>:</p>

<pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i>app/Routes/web.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

// This will return the pages (rule_one.php, rule_two.php & rule_three.php)
// if you call `/rules` from browser
Route::get('rules', function () {
    return view(['rule_one', 'rule_two', 'rule_three']);
});

</code>
</pre>

                <hr class="separate-content">

                <p class="sub-heading-3">Route to Controller</p>

                <p class="content">You can route to a controller by specifying the controller class and its method:</p>

                <pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i>app/Routes/web.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

// This will goes to app/HTTP/Controller/Rule.php class and its method `display`
// if you call `/rules` from browser
Route::get('rules', [\App\HTTP\Controllers\Rule::class, 'display']);

</code>
</pre>

                <hr class="separate-content">

                <p class="sub-heading-3">Route With Callback</p>

                <p class="content">You can define a route with a callback function:</p>

                <pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i>app/Routes/web.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

// This will display "Rule: DRY"
// if you call `/rules` from browser
Route::get('rules', function () {
    echo "Rule: DRY";
});

</code>
</pre>

                <hr class="separate-content">

                <p class="sub-heading-3">Route Group</p>

                <p class="content">If you have multiple routes with the same URL prefix, you can group those routes using <span class="method">group()</span>:</p>

<pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i>app/Routes/web.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

Route::group('auth', function () {
    Route::create('login', [\App\HTTP\Controllers\Auth::class, 'login'], ['get', 'post']); // auth/login with HTTP GET & POST methods
    Route::create('setup', [\App\HTTP\Controllers\Auth::class, 'setup'], ['get', 'post']); // auth/setup with HTTP GET & POST methods
});

</code>
</pre>

                <hr class="separate-content">

                <p class="sub-heading-3">URLs With Dynamic Segments</p>

                <p class="content">You can include dynamic segments in your route URL to capture parameters:</p>

<pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i>app/Routes/web.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

Route::get('user/{id}', [\App\HTTP\Controllers\User::class, 'index']);

</code>
</pre>

                <p class="content">If you visit <span class="method">user/12</span>, you can retrieve the id value from your controller using <span class="method">\Nova\HTTP\IncomingRequest</span>:</p>

<pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i>app/HTTP/Controllers/User.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

namespace App\HTTP\Controllers;

class User
{
    public function index(\Nova\HTTP\IncomingRequest $request)
    {
        $dynamicSegment = $request->getRouteParam('id'); // 12
    }
}

</code>
</pre>

                <p class="content">You can also add rules to dynamic segments:</p>

<pre>
<div class="language-identify">
    <div><i class="fa-brands fa-php"></i>app/Routes/web.php</div>
    <div><i class="fa-regular fa-copy"></i></div>
</div>
<code class="language-php code-box">
&lt;?php

// This accepts all types for the credential segment,
// Url will match "user/123" and "user/david"
Route::get("user/{credential, :any}", [\App\HTTP\Controllers\User::class, "index"]);

// This accepts only number for id segment,
// Url will not match "user/david"
// Url will match "user/123"
Route::get("user/{id, :num}", [\App\HTTP\Controllers\User::class, "index"]);

// This accepts only text for name segment,
// Url will not match "user/123"
// Url will match "user/david"
Route::get("user/{name, :text}", [\App\HTTP\Controllers\User::class, "index"]);

// This accepts only types that match the regex pattern for the credential segment,
// Url will not match "user/david"
// Url will match "user/10"
Route::get("user/{credential, :regex(^\d+$)}", [\App\HTTP\Controllers\User::class, "index"]);

</code>
</pre>

                <hr class="separate-content">

                <p class="sub-heading-3">Rules List</p>

                <table class="table text-center">
                    <tr>
                        <th>Rule</th>
                        <th>Parameter</th>
                        <th>Definition</th>
                    </tr>

                    <tr>
                        <td><span class="method">:any</span></td>
                        <td> - </td>
                        <td>Allows any type for the dynamic segment</td>
                    </tr>

                    <tr>
                        <td><span class="method">:num</span></td>
                        <td> - </td>
                        <td>Allows only numbers for the dynamic segment</td>
                    </tr>

                    <tr>
                        <td><span class="method">:text</span></td>
                        <td> - </td>
                        <td>Allows only text for the dynamic segment</td>
                    </tr>

                    <tr>
                        <td><span class="method">:regex</span></td>
                        <td><span class="return-type">regex pattern</span></td>
                        <td>Allows only types that match the defined regex pattern for the dynamic segment</td>
                    </tr>
                </table>

                <hr class="separate-content">

                <p class="sub-heading-4 text-center">This page is rendered from <span class="method">app/Routes/web.php</span> and action is <span class="method">app/Views/welcome.php</span></p>

                <hr class="separate-content">

            </div>

            <div id="contact-us">
                <h1 class="sub-heading-2">Contact Us</h1>

                <div class="content-group">
                    <p class="content-title">Contacting for Framework Issues</p>
                    <p class="content-body">If you encounter any problems with the framework or need assistance, don't hesitate to reach out to us. You can contact us by sending an email to <span class="link">naingaunglwin.wd@gmail.com</span>. We are here to help you troubleshoot and resolve any issue you may face.</p>
                </div>

                <hr class="separate-content">

                <div class="content-group">
                    <p class="content-title">Reporting Issues or Bugs</p>
                    <p class="content-body">We highly encourage our users to actively participate in improving the framework. If you come across any bugs, issues, or problems, we would appreciate it if you could report them to us.</p>
                </div>

                <hr class="separate-content">

                <div class="content-group">
                    <p class="content-title">Feedback and Suggestions</p>
                    <p class="content-body">We value your feedback and suggestions to enhance our framework. If you have ideas for new features, improvements, or changes, free feel to share them with us. Your input plays a significant role in shaping the future of the framework.</p>
                </div>

                <hr class="separate-content">

                <p class="sub-heading-3 text-center">Thanks for choosing our framework and helping us make it better!</p>
            </div>
        </div>
    </section>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/languages/go.min.js"></script>

    <script>
        const holders = document.querySelectorAll(".sub-category-holder");
        const links = document.querySelectorAll(".category a");
        const blocks = document.querySelectorAll(".right-side div");
        const menu = document.getElementById("menu");
        const leftSide = document.getElementById("left-side");
        const menuClose = document.getElementById("menu-close");

        document.addEventListener("DOMContentLoaded", () => {

            hljs.highlightAll();

            const DetectScreenSize = () => {
                let nav = document.querySelector("nav");

                nav.classList.remove("mobile");
                nav.classList.remove("desktop");

                console.log(window.innerWidth)
                if (window.innerWidth < 780) {
                    leftSide.classList.add("mobile");
                    nav.classList.add("mobile");
                } else {
                    leftSide.classList.remove("mobile");
                    nav.classList.add("desktop");
                }
            };

            DetectScreenSize();

            window.addEventListener("resize", DetectScreenSize);

            menu.addEventListener("click", () => {
                leftSide.classList.toggle("mobile-active")
            });

            menuClose.addEventListener("click", () => {
                leftSide.classList.remove("mobile-active");
            });

            blocks.forEach(block => {
                block.classList.remove("active");

                if (window.location.hash === '' || window.location.hash === '#home') {
                    document.getElementById("home").classList.add("active");
                }

                if (window.location.hash !== '') {
                    document.getElementById(window.location.hash.substring(1)).classList.add("active");
                }
            });

            if (holders.length > 0) {
                holders.forEach(holder => {
                    holder.addEventListener("click", (event) => {
                        event.preventDefault();

                        holder.classList.toggle("open");

                        const subCategory = holder.nextElementSibling;

                        if (subCategory && subCategory.classList.contains("sub-category")) {
                            subCategory.classList.toggle("active");
                        }
                    });
                });
            }

            const setActiveLink = (activeLink) => {
                links.forEach(link => {
                    link.classList.remove("active");

                    const parent = link.closest(".category");

                    if (parent) {
                        parent.classList.remove("border-blue");
                    }


                    const subParent = link.closest(".sub-category-content");

                    if (subParent) {
                        subParent.classList.remove("border-blue");
                    }

                    const icon = link.querySelector("i");
                    if (icon) {
                        icon.classList.remove("rotate");
                    }
                });

                if (activeLink) {
                    activeLink.classList.add("active");

                    const parent = activeLink.closest(".category");

                    if (parent) {
                        parent.classList.add("border-blue");
                    }

                    const subParent = activeLink.closest(".sub-category-content");

                    if (subParent) {
                        subParent.classList.add("border-blue");
                    }

                    const icon = activeLink.querySelector("i");
                    if (icon) {
                        icon.classList.add("rotate");
                    }
                }
            };

            const setActiveBlock = (link) => {
                if (link) {
                    const url  = new URL(link);
                    const hash = url.hash.substring(1);

                    const block = document.getElementById(hash) ?? null;

                    if (block !== null) {
                        block.classList.add("active");
                    }
                }
            }

            if (links.length > 0) {
                links.forEach(link => {
                    link.addEventListener("click", () => {
                        if (!link.classList.contains("sub-category-holder")) {
                            blocks.forEach(block => {
                                block.classList.remove("active");
                            });

                            setActiveLink(link);
                            setActiveBlock(link.href);
                        }
                    });

                    if (window.location.href === link.href && !link.classList.contains("active")) {
                        setActiveLink(link);
                    }
                });
            }
        });

    </script>
</body>
</html>
