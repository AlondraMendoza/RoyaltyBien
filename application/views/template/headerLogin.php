<!DOCTYPE html>
<html>
<head>
    
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
   
    <title>Bienvenido</title>
    <link href="<?= base_url() ?>public/css/metro.css" rel="stylesheet">
    <script src="<?= base_url() ?>public/js/jquery.js"></script>
    <link href="<?= base_url() ?>public/css/metro-icons.min.css" rel="stylesheet">
    <script src="<?= base_url() ?>public/js/metro.js"></script>

    
 
    <style>
        .login-form {
            width: 25rem;
            height: 18.75rem;
            position: fixed;
            top: 50%;
            margin-top: -9.375rem;
            left: 50%;
            margin-left: -12.5rem;
            background-color: #ffffff;
            opacity: 0;
            -webkit-transform: scale(.8);
            transform: scale(.8);
        }
    </style>
    
        <script>

        $(function(){
            var form = $(".login-form");

            form.css({
                opacity: 1,
                "-webkit-transform": "scale(1)",
                "transform": "scale(1)",
                "-webkit-transition": ".5s",
                "transition": ".5s"
            });
        });
    </script>
</head>
<body style="background-color: gray" class="center">