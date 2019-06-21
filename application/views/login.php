<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<head>    
    <title>EyesFood | Login</title>
    <link rel="stylesheet" href="<?php echo base_url();?>dist/css/login.css">
</head>
<div class="login-page">
    <form class="login-form">
        <div class="form">
            <a class="btn btn-light" href="#" role="button">Staff</a>
            <a class="btn btn-primary" href="Login/login2" role="button">Salud</a>
            <a class="btn btn-primary" href="#" role="button">Tiendas</a>
            <?php echo $login;?>
            </div>
    </form>
  </div>
</div>