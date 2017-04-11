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

<!--body wrapper start-->
<div class="wrapper"> 
    <div class="row">
        <div class="col-md-12">
            <div>
                <span style="font-weight: 700">TASK</span>
                <address>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Reference ID</th>
                                 <th>Title</th>
                                <th>Active</th>
                                <th>Priority</th>
                                <th>Status</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Scheduled For</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <?php
                                    if ($ref_id) {
                                        echo $ref_id;
                                    } else {
                                        echo 'NONE';
                                    }
                                    ?> 
                                </td>
                                <td><?php echo $title; ?></td>
                                <td>
                                    <?php
                                    if ($active) {
                                        echo "YES";
                                    } else {
                                        echo 'NO';
                                    }
                                    ?> 
                                </td>
                                <td><?php echo $priority; ?></td>
                                <td><?php echo ucwords($status); ?></td>
                                <td><?php
                                    $category_title = getCurrentCategory($category, $taskcategory_titles);
                                    echo ucwords($category_title);
                                    ?></td>
                                <td><?php echo $description; ?></td>
                                <td><?php echo date('F j, Y, g:i a', strtotime($notification)); ?></td>
                                <!--<td><?php echo date('F j, Y, g:i a', strtotime($date_created)); ?></td>-->
                            </tr>
                        </tbody>
                    </table>
                </address>
            </div>
        </div>

    </div>

    <div class="row">
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
                                    
                                    $notif=date('F j, Y, g:i a', strtotime($row["notification"]));

                                    $content.="<tr class=\"booking_radio $active\">";
                                    $content.="<td>"
                                            . "<input class=\"booking_hidden_id\" type=\"hidden\" value=\"$id\">"; //                                  
                                    $content.="$count</td>";
                                    $content.="<td>$title</td>";
                                    $content.="<td>$sender</td>";
                                    $content.="<td>$receiver</td>";
                                    $content.="<td>$instruction</td>";
                                    $content.="<td>$outcome</td>";
                                    $content.="<td>$status_span</td>";
                                    $content.="<td>$notif</td>";
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
                                <th></th>
                                <th>Title</th>
                                <th>Initiator</th>
                                <th>Action</th>
                                <th>Instruction</th>
                                <th>Outcome</th>
                                <th>Status</th>
                                <th>Scheduled For</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (($activities) && count($activities) > 0) { ?>
                                <?php echo $content; ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

        </div>
        </section>
    </div>

</div>
<!--body wrapper end-->

<!--body wrapper start-->
<div class="wrapper">   

    <div class="text-center ">
        <?php
        $back_uri = "index.php/app";
        if (isset($_SESSION["back_uri"])) {
            $back_uri = $this->session->back_uri;
        }
        ?>
        <a href="<?php echo $back_uri; ?>" class="btn btn-default btn-sm noprint_header" >Back</a>
        <button class="btn btn-success btn-sm noprint_header" type="button" onClick="printPage();">Print</button>
    </div>
</div>