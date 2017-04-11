<?php
$access = $this->session->us_access;

$current = $received[0];
//print_r($received[0]);exit;
extract($current);


$disabled = "disabled";
$count = 1;
?>
<!-- page heading start-->
<div class="page-heading">
    <h3>  Reminder   </h3>
    <ul class="breadcrumb">
        <li>
            Task Manager
        </li>
        <li>
            Reminders
        </li>
        <li class="active"> <?php echo ucwords($title); ?> </li>
    </ul>
</div>
<!-- page heading end-->

<!--body wrapper start-->
<div class="wrapper"> 
    <div class="row">
        <div class="col-md-5">
            <div class="">
                <address>
                    <strong>Title</strong><br>
                    <h4 >
                        <?php
                        echo ucwords($title);
                        ?>
                    </h4>
<!--                    <strong>Status</strong><br>
                    <span style="color: #65CEA7;">
                        <?php echo ucwords($title); ?>
                    </span>                    -->
                </address>
<!--                <address>
                    <strong>Scheduled For</strong><br>
                    <span style="color: #65CEA7;">
                        <?php echo date('F j, Y, g:i a', strtotime($notification)); ?>
                    </span>                   
                </address>
                <address>
                    <strong>Date Created</strong><br>
                    <span style="color: #65CEA7;">
                        <?php echo date('F j, Y, g:i a', strtotime($date_created)); ?>
                    </span>                    
                </address>-->
            </div>
        </div>
        <div class="col-md-7">
            <strong> Description </strong>
            <?php echo $description; ?>
            <hr>
            <?php
            $buttons = "<a href=\"" . base_url() . "index.php/app/showReminder/0/". $type . "/form_" . "\" class=\"btn btn-default  \" type=\"button\"><i class=\"fa fa-plus\"></i>&nbsp;New Reminder</a>&nbsp;";
            $buttons.="<a href=\"" . base_url() . "index.php/app/showReminder/".$ID ."/". $type . "/form_" . "\" type=\"button\" class=\"btn btn-success \"><i class=\"fa fa-edit\"></i>&nbsp;Edit This Reminder</a>&nbsp;";
            $buttons.="<a href=\"" . base_url() . "index.php/app/updatePageItem/" . $type . "/" . $ID . "/trash" . "\" type=\"button\" class=\"btn btn-danger \"><i class=\"fa fa-trash-o\"></i>&nbsp; Trash</a>&nbsp;";
            $buttons.="<a href=\"" . base_url() . "index.php/app/updatePageItem/" . $type . "/" . $ID . "/close" . "\" type=\"button\" class=\"btn btn-primary \"><i class=\"fa fa-times-circle\"></i>&nbsp;Close This Reminder</a>&nbsp;";

            echo $buttons;
            ?>
        </div>
    </div>
    
</div>
<!--body wrapper end-->
