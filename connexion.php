<?php
require_once('./class/User.php');
require_once('function.php');
require_once('bdd.php');

// session_destroy();
if (isset($_SESSION['user'])) {
    header('Location:index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/common.css">
    <title>Document</title>
</head>

<body>
    <?php require_once('header.php'); ?>
    <main>
        <form action="" method="post">
            <label for="login">Login</label>
            <input type="text" id="login" name="login" placeholder="Login" autofocus>
            <label for="password">Password</label>
            <input type="password" id="password" name="password" placeholder="Password">
            <input type="submit" name="submit" class="input">

            <?php

            if (isset($_POST['submit'])) {

                // var_dump($result);
                $user = new User('', $_POST['login'], $_POST['password'], '', '', '');
                $user->connect($bdd);
                $user->isConnected();
            }

            ?>
        </form>
    </main>
</body>
<style>
    form {
        display: flex;
        flex-direction: column;
    }

    .input {
        color: #f1b16a;
        padding: 5px;
        background-color: #121a2e;
        margin-top: 10px;
    }

    label {
        font-size: 1.5rem;

    }
</style>

</html>