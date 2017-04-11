<?php
$access = $this->session->us_access;
?>
<!-- page heading start-->
<div class="page-heading">
    <h3>  Settings   </h3>
    <ul class="breadcrumb">
        <li>
            Task Manager
        </li>
        <li>
            Settings
        </li>
    </ul>
</div>
<!-- page heading end-->

<!--body wrapper start-->
<div class="wrapper"> 
    <div class="row">
        <div class="col-md-7">
            <span style="color: #65CEA7;">
                <?php if(isset($_SESSION['reset_message'])){
                echo $this->session->reset_message;
            } ?>
            </span>
            
            <hr>
            <?php
            $buttons = "<a href=\"" . base_url() . "index.php/app/resetApp" . "\" class=\"btn btn-default  \" type=\"button\"><i class=\"fa fa-sun-o\"></i>&nbsp;Reset To Factory Settings</a>&nbsp;";
            echo $buttons;
            ?>
        </div>
    </div>
    
</div>
<!--body wrapper end-->
