<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<head>    
    <title>EyesFood | Login</title>
    <link rel="stylesheet" href="<?php echo base_url();?>dist/css/login.css">
</head>
<div class="login-page">
  <div class="form">    
    <form class="login-form">
        <?php
            echo $login->render("selectform");
        ?>
    </form>
  </div>
</div>