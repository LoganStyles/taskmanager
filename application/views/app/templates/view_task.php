<?php
$access = $this->session->us_access;
if (($access < 1)) {
    $redirect = "index.php/app/";
    redirect($redirect);
}

$current = $received[0];
//print_r($received[0]);exit;
extract($current);
extract($task);
//print_r($actions_titles);exit;

$disabled = "disabled";
$count = 1;

function getCurrentCategory($param, $categories) {
    $curr_title = "";
    foreach ($categories as $cats):
        $curr_id = $cats["ID"];
        $curr_title = $cats["title"];
        if ($curr_id == $param)
            return $curr_title;
    endforeach;
    return $curr_title;
}

?>
<!-- page heading start-->
<div class="page-heading">
    <h3> Task   </h3>
    <ul class="breadcrumb">
        <li>
            Task Manager
        </li>
        <li>
            <a href="<?php echo base_url() . 'index.php/app/viewComponent/task/normal/0'; ?>">All Tasks</a>            
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
            ?>" style="color: #65CEA7;">Back</a>            
        </li>        

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
                </address>
<!--                <address>
                    <strong>Reference ID</strong><br>
                    <span style="color: #65CEA7;">
                        <?php
                        if ($ref_id) {
                            echo $ref_id;
                        } else {
                            echo 'NONE';
                        }
                        ?>
                    </span>                    
                </address>

                <address>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Active</th>
                                <th>Priority</th>
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
                                <td><span style="color: #65CEA7;"><?php echo $priority; ?></span></td>
                                <td><span style="color: #65CEA7;"><?php echo ucwords($status); ?></span></td>
                            </tr>
                        </tbody>
                    </table>
                </address>
                <address>
                    <strong>Category</strong><br>
                    <span style="color: #65CEA7;">
                        <?php
                        $category_title = getCurrentCategory($category, $taskcategory_titles);
                        echo ucwords($category_title);
                        ?>
                    </span>                    
                </address>
                <address>
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
            $buttons = "<a href=\"" . base_url() . "index.php/app/showTask/0/" . $type . "/form_" . "\" class=\"btn btn-default  \" type=\"button\"><i class=\"fa fa-plus\"></i>&nbsp;New Task</a>&nbsp;";
            $buttons.="<a href=\"" . base_url() . "index.php/app/showTask/" . $ID . "/" . $type . "/form_" . "\" type=\"button\" class=\"btn btn-success \"><i class=\"fa fa-edit\"></i>&nbsp;Edit This Task</a>&nbsp;";
            $buttons.="<a href=\"" . base_url() . "index.php/app/updatePageItem/" . $type . "/" . $ID . "/trash" . "\" type=\"button\" class=\"btn btn-danger \"><i class=\"fa fa-trash-o\"></i>&nbsp; Move To Trash</a>&nbsp;";
            $buttons.="<a href=\"" . base_url() . "index.php/app/updatePageItem/" . $type . "/" . $ID . "/close" . "\" type=\"button\" class=\"btn btn-primary \"><i class=\"fa fa-times-circle\"></i>&nbsp;Close This Task</a>&nbsp;";

            echo $buttons;
            ?>
        </div>
    </div>

    <div class="row" style="margin-top: 3%;">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    Activities
                    <div>
                        <div class="pull-right">
                            <?php if (($activities) && count($activities) > 0) { ?>
                                <?php
                                $disabled = ""; //make all action buttons active

                                $content = $status_span = $active_span = "";
                                foreach ($activities as $row):
                                    $id = $row["ID"];
                                    $serial = $row["serial"];
                                    $taskid = $row["taskid"];
                                    $title = $row["title"];
                                    $sender = $row["sender"];
                                    $receiver = getCurrentAction($row["receiver"],$actions_titles);
                                    $instruction = $row["instruction"];
                                    $outcome = $row["outcome"];
                                    $status = $row["status"];

                                    if ($status === "pending") {
                                        //scheduled date is in the future
                                        $status_span = "<span class=\"label label-warning label-mini status_span\">Pending</span>";
                                    } elseif ($status === "open") {
                                        //scheduled date is today
                                        $status_span = "<span class=\"label label-success label-mini status_span\">Open</span>";
                                    } elseif ($status === "overdue") {
                                        //scheduled date has past
                                        $status_span = "<span class=\"label label-danger label-mini status_span\">Overdue</span>";
                                    } elseif ($status === "closed") {
                                        $status_span = "<span class=\"label label-primary label-mini status_span\">Closed</span>";
                                    }

                                    if ($count == 1) {
                                        $active = "active";
                                        $checked = "checked";
                                    } else {
                                        $active = "";
                                        $checked = "";
                                    }
                                    
                                    $notification = date('F j, Y, g:i a', strtotime($row["notification"]));

                                    $content.="<tr class=\"booking_radio $active\">";
                                    $content.="<td>"
                                            . "<input class=\"booking_hidden_id\" type=\"hidden\" value=\"$id\">"; //                                  
                                    $content.="$serial</td>";
                                    $content.="<td>$title</td>";
                                    $content.="<td>$sender</td>";
                                    $content.="<td>$receiver</td>";
                                    $content.="<td>$instruction</td>";
                                    $content.="<td>$outcome</td>";
                                    $content.="<td>$notification</td>";
                                    $content.="<td>$status_span</td>";
                                    $content.="</tr>";

                                    $count++;
                                endforeach;
                                ?>
                            <?php } ?>
                            <div class="form-group ">
                                <div class="col-sm-12">
                                    <?php
                                    $curr_task_id = $ID;
                                    $buttons = "<a href=\"" . base_url() . "index.php/app/showActivity/" . $curr_task_id . "/0/activity/form_" . "\"class=\"btn btn-default  \" type=\"button\"><i class=\"fa fa-plus\"></i>&nbsp;New</a>&nbsp;";
                                    if ($count > 1) {
                                        $buttons.="<a onclick=\"activityManager('view_',$curr_task_id);\" type=\"button\" class=\"btn btn-primary \"><i class=\"fa fa-eye\"></i>&nbsp;View</a>&nbsp;";
                                        $buttons.="<a onclick=\"activityManager('form_',$curr_task_id);\" type=\"button\" class=\"btn btn-success \"><i class=\"fa fa-edit\"></i>&nbsp;Edit</a>&nbsp;";
                                    }
//                    
                                    echo $buttons;
                                    ?>

                                </div>
                            </div>


                        </div>
                        <div class="clearfix"></div>

                    </div>
                </header>


                <div class="panel-body"><p style="font-weight: 700;color: #f00;">
                        <?php
                        if (isset($request_response)) {
                            echo $request_response;
                        }
                        ?>
                    </p>
                    <table class="table  table-hover general-table table-bordered table-condensed">
                        <thead>
                            <tr>         
                                <th>S/N</th>
                                <th>Title</th>
                                <th>Initiator</th>
                                <th>Action</th>
                                <th>Instruction</th>
                                <th>Outcome</th>
                                <th>Schedule For</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (($activities) && count($activities) > 0) { ?>
                                <?php echo $content; ?>
                            <?php } ?>
                        </tbody>
                    </table>

                    <?php
                    if (strlen($pagination)) {
                        echo $pagination;
                    }
                    ?>

                </div>

        </div>
        </section>
    </div>
</div>