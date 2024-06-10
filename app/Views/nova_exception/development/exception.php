<?php use Nova\Exception\Helper\ExceptionDisplay; ?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Framework icon -->
    <link rel="icon" href="<?php echo ExceptionDisplay::getBaseUrl('/nova_icon/favicon.png'); ?>" sizes="32x32">

    <!-- Google Font (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>Exception</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #1d2023;
            background-size: cover;
            font-family: "Inter", sans-serif;
            color: white;
        }

        ::-webkit-scrollbar {
            width: 3px;
            height: 8px;
        }

        ::-webkit-scrollbar-track {
            background: azure;
        }

        ::-webkit-scrollbar-thumb {
            background: rgba(3, 102, 214, 0.3);
            border-radius: 10px;
        }

        span.severity {
            background: #e74c4c;
            opacity: 0.8;
            padding: 7px 10px;
            font-size: 13pt;
            border-radius: 2px;
        }

        section div.container {
            padding: 5px 25px;
            max-width: 1400px;
            margin: 40px auto;
            margin-bottom: 0!important;
        }

        div.box {
            border-radius: 6px;
            background: #252525;
            overflow: hidden;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 80px;
            text-align: center;
            margin-bottom: 50px;
        }

        div.box > p {
            display: flex;
            justify-content: center;
            grid-gap: 25px;
            align-items: center;
            width: 100%;
            padding: 0 15px;
        }

        div.backtrace {
            padding-top: 40px;
        }

        div.trace-messages-box {
            margin: 55px 0;
        }

        div.trace-messages-box h3 {
            word-break: break-word;
        }

        div.error-icon {
            position: absolute;
            right: 10px;
            top: 10px;
            background: #000000;
            padding: 2px;
            border-radius: 4px;
            z-index: 99;
        }

        div.error-code {
            position: relative;
            padding: 20px 0;
            border-radius: 6px;
            background: #252525;
            overflow-x: auto;
            white-space: nowrap;
            width: 100%;
            line-height: 15px;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        p.error-title {
            font-size: 12.6pt;
        }

        div.error-code > span {
            width: inherit;
        }

        div.error-code > span.code-line p {
            position: relative;
            margin: 3px 0!important;
            padding: 8px 20px;
            font-size: 12pt;
            font-family: "Inter", sans-serif;
            display: block;
        }

        div.error-code > span.error-line p {
            border-radius: 6px;
            opacity: 1!important;
        }

        div.error-code span.error-line p:before,
        div.error-code span.code-line p:hover:before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #c94040 !important;
            border-radius: 6px;
            z-index: 1;
            opacity: 0.4;
            cursor: pointer;
        }

        span.variable {
            color: #ae32cf;
        }

        span.function {
            color: #56A8F5;
        }

        span.keyword {
            color: tomato;
        }

        span.comment {
            color: grey;
        }

        footer {
            width: 100%;
            background: #e0e0e0;
            display: flex;
            justify-content: center;
            align-items: center;
            padding-top: 10px;
        }

        div.logo-box {
            display: flex;
            align-items: center;
        }

        div.logo-box:hover {
            cursor: pointer;
        }

        div.logo-box img {
            width: 38px;
            height: 38px;
            margin-left: 10px;
            margin-bottom: 10px;
        }

        div.logo-box a {
            text-decoration: none;
            font-size: 11pt!important;
            color: black;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<section>

    <div class="container">
        <div class="box">
            <p><?php echo ExceptionDisplay::getMessage() . ' in ' . ExceptionDisplay::getFile() . ' on line ' . ExceptionDisplay::getLine() ?></p>
        </div>
        <div class="current-error-code">
            <?php ExceptionDisplay::displayCurrentErrorMessage(); ?>
        </div>

        <div class="backtrace">
            <div class="backtrace-title">
                <h1>Backtrace</h1>
            </div>

            <?php ExceptionDisplay::displayTraceMessages(); ?>
        </div>
    </div>
</section>

<footer>
    <div class="logo-box">
        <a href="https://github.com/naingaunglwin-dev/novaframe" target="_blank"><i class="fa-regular fa-copyright"></i> NovaFrame 2024</a>
        <img src="<?php echo ExceptionDisplay::getBaseUrl('nova_icon/icon.png'); ?>" alt="Framework Logo">
    </div>
</footer>
</body>
</html>
