<?php
$access = $this->session->us_access;
if (($access < 1)) {
    $redirect = "index.php/app/";
    redirect($redirect);
}

$current = $received[0];
//print_r($printreport);exit;

$disabled = "disabled";
$count = 1;
?>

<!--body wrapper start-->
<div class="wrapper">

    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    <?php echo "Reports From ". $date_from." - ".$date_to;?>
                    <div>
                        <div class="pull-right">
                            <?php if (isset($printreport) && count($printreport) > 0) { ?>
                                <?php
                                $disabled = ""; //make all action buttons active

                                $content = $status_span = $active_span = "";
                                foreach ($printreport as $row):
                                    $id = $row["ID"];
                                    $task_title = $row["task_title"];
                                    $taskid = $row["taskid"];
                                    $title = $row["title"];
                                    $sender = $row["sender"];
                                    $receiver = getCurrentAction($row["receiver"],$actions_titles);
                                    $instruction = $row["instruction"];
                                    $outcome = $row["outcome"];
                                    $status = $row["status"];
                                    $notification = date('F j, Y, g:i a', strtotime($row["notification"]));

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

                                    $content.="<tr class=\"booking_radio $active\">";
                                    $content.="<td>"
                                            . "<input class=\"booking_hidden_id\" type=\"hidden\" value=\"$id\">"; //                                  
                                    $content.="$task_title</td>";
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
                                <th>Task</th>
                                <th>Activity</th>
                                <th>Initiator</th>
                                <th>Action</th>
                                <th>Instruction</th>
                                <th>Outcome</th>
                                <th>Scheduled For</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (($printreport) && count($printreport) > 0) { ?>
                                <?php echo $content; ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

        </div>
        </section>
    </div>

    <div class="text-center ">
        <?php
        $back_uri = base_url() . "index.php/app/showReports";
        ?>
        <a href="<?php echo $back_uri; ?>" class="btn btn-default btn-sm noprint_header" >Back</a>
        <button class="btn btn-success btn-sm noprint_header" type="button" onClick="printPage();">Print</button>
    </div>
</div>