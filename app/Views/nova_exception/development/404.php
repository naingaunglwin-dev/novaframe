<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <!-- Framework icon -->
    <link rel="icon" href="<?php echo baseUrl('nova_icon/novaframe.header.svg') ?>">

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
            background: aliceblue;
            font-family: "Inter", sans-serif;
        }

        div.container {
            display: grid;
            place-content: center;
            height: 100vh;
        }

        div.container div.text-box {
            background: white;
            border-radius: 9px;
            padding: 10px 25px;
            text-align: center;
        }

        div.container div.text-box h2 {
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
            color: black;
            margin-bottom: 10px;
        }
    </style>
</head>
<body>
<section>
    <div class="container">
        <div class="text-box">
            <?php if ($type === 'url'): ?>
                <h2><?php echo lang('exception.UrlNotFound', "<br><span>$resource</span><br>") ?></h2>
            <?php elseif ($type === 'view'): ?>
                <h2><?php echo lang('exception.PageNotFound', "<br><span> $resource </span>") ?></h2>
            <?php elseif ($type === 'controller'): ?>
                <h2><?php echo lang('exception.ControllerNotFound') ?></h2>
                <h2 class="color-red"><?php echo $resource ?></h2>
            <?php elseif ($type === 'method'): ?>
                <h2><?php echo lang('exception.MethodNotFound') ?></h2>
                <h2 class="color-red"><?php echo $resource ?></h2>
            <?php endif; ?>
        </div>
    </div>

    <div class="logo-box">
        <a href="https://github.com/naingaunglwin-dev/novaframe" target="_blank"><i class="fa-regular fa-copyright"></i> NovaFrame 2024</a>
        <img src="<?php echo baseUrl('nova_icon/novaframe.svg') ?>" alt="Framework Logo">
    </div>
</section>
</body>
</html>
