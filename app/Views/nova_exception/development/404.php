<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Framework icon -->
    <link rel="icon" href="<?php echo baseUrl('nova_icon/favicon.png') ?>">

    <!-- Google Font (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <!-- Fontawesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    <title>404</title>

    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #1d2023;
            font-family: "Inter", sans-serif;
        }

        div.container {
            display: grid;
            place-content: center;
            height: 100vh;
        }

        div.container div.text-box {
            border-radius: 6px;
            background: #22262b;
            border: 1px solid rgba(255, 255, 255, 0.1);
            text-align: center;
            margin-bottom: 50px;
            color: #b1b1b1;
            padding: 0 20px;
            font-size: 14pt;
        }
        
        @media screen and (max-width: 420px) {
            div.container div.text-box {
                font-size: 11pt;
                padding: 0 17px;
            }
        }

        div.container div.text-box p {
            line-height: 35px;
        }

        div.container div.text-box span {
            background: #e74c4c;
            padding: 0 4px;
            border-radius: 4px;
            color: white;
            font-weight: normal!important;
        }

        .color-red {
            color: #e74c4c;
        }

        div.logo-box {
            position: absolute;
            right: 10px;
            bottom: 0;
            display: flex;
            align-items: center;
        }

        div.logo-box:hover {
            cursor: pointer;
        }

        div.logo-box img {
            width: 40px;
            height: 40px;
            margin-left: 10px;
            margin-bottom: 10px;
        }

        div.logo-box a {
            text-decoration: none;
            font-size: 11pt!important;
            color: white;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<section>
    <div class="container">
        <div class="text-box">
            <?php if ($type === 'url'): ?>
                <p><?php echo lang('exception.UrlNotFound', "<br><span>$resource</span><br>") ?></p>
            <?php elseif ($type === 'view'): ?>
                <p><?php echo lang('exception.PageNotFound', "<br><span> $resource </span>") ?></p>
            <?php elseif ($type === 'controller'): ?>
                <p><?php echo lang('exception.ControllerNotFound') ?></p>
                <p class="color-red"><?php echo $resource ?></p>
            <?php elseif ($type === 'method'): ?>
                <p><?php echo lang('exception.MethodNotFound') ?></p>
                <p class="color-red"><?php echo $resource ?></p>
            <?php endif; ?>
        </div>
    </div>

    <div class="logo-box">
        <a href="https://github.com/naingaunglwin-dev/novaframe" target="_blank"><i class="fa-regular fa-copyright"></i> NovaFrame 2024</a>
        <img src="<?php echo baseUrl('nova_icon/icon.png') ?>" alt="Framework Logo">
    </div>
</section>
</body>
</html>
