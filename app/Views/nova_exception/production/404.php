<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>404</title>

    <!-- Google Font (Inter) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">

    <style>
        body {
            margin: 0;
            padding: 0;
            background: aliceblue;
            font-family: "Inter", sans-serif;
        }

        body > div {
            display: grid;
            place-content: center;
            height: 100vh;
            font-weight: bold;
            font-size: 18pt;
        }
    </style>
</head>
<body>
    <div>
        <?php echo lang('exception.404') ?>
    </div>
</body>
</html>
