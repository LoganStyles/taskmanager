<?php
$access = $this->session->us_access;
if (!isset($this->session->us_username) || ($access < 1)) {
    $redirect = "index.php/app/";
    redirect($redirect);
}


$current = $received[0];
extract($current);
$count = 1;
?>

<!-- page heading start-->
<div class="page-heading">
    <h3> </h3>
    <ul class="breadcrumb">
        <li>
            Task Manager
        </li>
        <li>
            Trashed Activities
        </li>
    </ul>
</div>
<!-- page heading end-->

<!--body wrapper start-->
<div class="wrapper">

    <div class="row">
        <div class="col-sm-10">
            <section class="panel">
                <header class="panel-heading">
                    <!--Activities-->
                    <div>
                        <div class="pull-right">
                            <?php if (($activity) && count($activity) > 0) { ?>
                                <?php
                                $disabled = ""; //make all action buttons active

                                $content = $status_span = $active_span = "";
                                foreach ($activity as $row):
                                    $id = $row["ID"];
                                    $serial = $row["serial"];
                                    $taskid = $row["taskid"];
                                    $title = $row["title"];
                                    $sender = $row["sender"];
                                    $receiver = getCurrentAction($row["receiver"],$actions_titles);
                                    $instruction = $row["instruction"];
                                    $outcome = $row["outcome"];
//                                    $active = $row["active"];
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

//                                    if ($active === "0") {
//                                        //inactive
//                                        $active_span = "<span class=\"label label-default label-mini status_span\">Inactive</span>";
//                                    } elseif ($active === "1") {
//                                        //active
//                                        $active_span = "<span class=\"label label-success label-mini status_span\">Active</span>";
//                                    }

                                    if ($count == 1) {
                                        $active = "active";
                                        $checked = "checked";
                                    } else {
                                        $active = "";
                                        $checked = "";
                                    }

                                    $content.="<tr class=\"booking_radio $active\">";
                                    $content.="<td>"
                                            . "<input class=\"booking_hidden_id\" type=\"hidden\" value=\"$id\">"; //                                  
                                    $content.="$serial</td>";
                                    $content.="<td>$title</td>";
                                    $content.="<td>$sender</td>";
                                    $content.="<td>$receiver</td>";
                                    $content.="<td>$instruction</td>";
                                    $content.="<td>$outcome</td>";
                                    $content.="<td>$status_span</td>";
                                    $content.="</tr>";

                                    $count++;
                                endforeach;
                                ?>
                            <?php } ?>
                            <div class="form-group ">
                                <div class="col-sm-12">
                                    <?php
                                    $buttons = "";
                                    if ($count > 1) {
                                        
                                        $buttons.="<a onclick=\"trashManager('activity');\" type=\"button\" class=\"btn btn-primary \"><i class=\"fa fa-trash-o\"></i>&nbsp;Delete</a>&nbsp;";
                                        $buttons.="<a onclick=\"restoreManager('activity');\" type=\"button\" class=\"btn btn-success \"><i class=\"fa fa-reply\"></i>&nbsp;Restore Item</a>&nbsp;";                                       
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
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (($activity) && count($activity) > 0) { ?>
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
