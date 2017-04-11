<?php
$access = $this->session->us_access;

$current = $received[0];
//print_r($received[0]);exit;
extract($current);
//extract($activities);



$disabled = "disabled";
$count = 1;
?>
<!-- page heading start-->
<div class="page-heading">
    <h3>  Activity   </h3>
    <ul class="breadcrumb">
        <li>
            Task Manager
        </li>
        <li>
            <a href="<?php echo base_url() . 'index.php/app/viewComponent/activity/normal/0'; ?>">All Activities</a>            
        </li>
        <li class="active"> <?php echo ucwords($title); ?> </li>
    </ul>
    <ul class="breadcrumb">
        <li>
            <a href="<?php
            if (isset($_SESSION['back_uri'])) {
                echo $this->session->back_uri;
            } else {
                echo base_url();
            }
            ?>">Back</a>            
        </li>        

    </ul>
</div>
<!-- page heading end-->

<!--body wrapper start-->
<div class="wrapper"> 
    <div class="row">
        <div class="col-md-5">
            <div class="well">
                <address>
                    <strong>Title</strong><br>
                    <h4 >
                        <?php
                        echo ucwords($title);
                        ?>
                    </h4>
<!--                    <strong>Reference ID</strong><br>
                    <span style="color: #65CEA7;">
                    <?php
                    if ($ref_id) {
                        echo $ref_id;
                    } else {
                        echo 'NONE';
                    }
                    ?>
                    </span>                    -->
                </address>

                <!--                <address>
                                    <table class="table">
                                        <thead>
                                            <tr>
                                                <th>Active</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <span style="color: #65CEA7;"><?php
                if ($active === "1") {
                    echo 'YES';
                } elseif ($active === "0") {
                    echo 'NO';
                }
                ?></span>
                                                </td>                                
                                                <td><span style="color: #65CEA7;"><?php echo ucwords($status); ?></span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </address>
                                <address>
                                    <strong>Scheduled For</strong><br>
                                    <span style="color: #65CEA7;">
                <?php echo date('F j, Y, g:i a', strtotime($notification)); ?>
                                    </span>                    
                                </address>-->
                <address>
                    <strong>Description</strong><br>
                    <span style="color: #65CEA7;">
                        <?php echo $description; ?> 
                    </span>                    
                </address>
            </div>
        </div>
        <div class="col-md-6">
            <h4> Initiator </h4>
            <?php echo $sender; ?>
            <hr>
            <h4> Action </h4>
            <?php echo getCurrentAction($receiver, $actions_titles); ?>
            <hr>
            <h4> Instruction </h4>
            <?php echo $instruction; ?>
            <hr>

            <h4> Outcome </h4>
            <?php echo $outcome; ?>
            <hr>
            <?php
            $buttons = "<a href=\"" . base_url() . "index.php/app/showActivity/" . $taskid . "/0/" . $type . "/form_" . "\" class=\"btn btn-default  \" type=\"button\"><i class=\"fa fa-plus\"></i>&nbsp;New Activity</a>&nbsp;";
            $buttons.="<a href=\"" . base_url() . "index.php/app/showActivity/" . $taskid . "/" . $ID . "/" . $type . "/form_" . "\" type=\"button\" class=\"btn btn-success \"><i class=\"fa fa-edit\"></i>&nbsp;Edit This Activity</a>&nbsp;";
            $buttons.="<a href=\"" . base_url() . "index.php/app/updatePageItem/" . $type . "/" . $ID . "/trash" . "\" type=\"button\" class=\"btn btn-danger \"><i class=\"fa fa-trash-o\"></i>&nbsp; Trash</a>&nbsp;";
            $buttons.="<a href=\"" . base_url() . "index.php/app/updatePageItem/" . $type . "/" . $ID . "/close" . "\" type=\"button\" class=\"btn btn-primary \"><i class=\"fa fa-times-circle\"></i>&nbsp;Close This Activity</a>&nbsp;";

            echo $buttons;
            ?>
        </div>
    </div>
</div>
<!--body wrapper end-->
