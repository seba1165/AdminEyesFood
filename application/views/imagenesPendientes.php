<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//echo $alimento;
?>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
* {
  box-sizing: border-box;
}

/* Create two equal columns that floats next to each other */
.column {
  float: left;
  width: 40%;
  padding: 10px;
  height: 300px; /* Should be removed. Only for demonstration */
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}
.fit-image{
display: block;
object-fit: cover;
height:100%;/* only if you want fixed height */
}
</style>
</head>
<div id="exTab3" class="container" style="width: 100%; height: 100%">	
    <ul  class="nav nav-pills">
        <li class="active">
            <a  href="#1b" data-toggle="tab">Aprobados</a>
        </li>
<!--        <li>
            <a href="#2b" data-toggle="tab">Pendientes</a>
        </li>-->
    </ul>

    <div class="tab-content clearfix">
        <div class="tab-pane active responsive" id="1b">
            <?php
            echo $alimentos2->render();
            ?>
        </div>
        <div class="tab-pane" id="2b">
            <?php
            echo $alimentos->render();
            ?>
        </div>
    </div>
</div>

<!-- Bootstrap core JavaScript
================================================== -->
     <!--Placed at the end of the document so the pages load faster--> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.2.0/js/bootstrap.min.js"></script>