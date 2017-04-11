<?php
$access = $this->session->us_access;
if (!isset($this->session->us_username) || ($access < 1)) {
    $redirect = "index.php/app/";
    redirect($redirect);
}

$current = $received[0];
extract($current);

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
    <h3> Task Manager   </h3>
    <ul class="breadcrumb">
        <li>
            Task Manager
        </li>
        <li class="active"> Summary </li>
    </ul>
</div>
<!-- page heading end-->

<!--body wrapper start-->
<div class="wrapper">
    <div class="row">
        <div class="col-md-8">
            <!--statistics start-->
            <div class="row">
                <div class="col-md-12">
                    <!--breadcrumbs start -->
                    <ul class="breadcrumb panel">
                        <li class="active" style="font-weight: 700;">Tasks</li>
                    </ul>
                    <!--breadcrumbs end -->
                </div>
            </div>
            <div class="row state-overview">                
                <div class="col-md-4 col-xs-12 col-sm-4">
                    <a href="<?php
                    if ($tasks_today_count) {
                        echo base_url() . 'index.php/app/processDashboardFilter/task/due';
                    } else {
                        echo '#';
                    }
                    ?>">
                        <div class="panel deep-purple-box notif">
                            <div class="symbol">
                                <i class="fa fa-briefcase"></i>
                            </div>
                            <div class="state-value">
                                <div class="value"><?php echo $tasks_today_count; ?></div>
                                <div class="title">Due</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-xs-12 col-sm-4">
                    <a href="<?php
                    if ($tasks_pending_count) {
                        echo base_url() . 'index.php/app/processDashboardFilter/task/pending';
                    } else {
                        echo '#';
                    }
                    ?>">                    
                        <div class="panel blue notif">                        
                            <div class="symbol">
                                <i class="fa fa-briefcase"></i>
                            </div>
                            <div class="state-value">
                                <div class="value"><?php echo $tasks_pending_count; ?></div>
                                <div class="title"> Pending</div>
                            </div>                        
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-xs-12 col-sm-4">
                    <a href="<?php
                    if ($tasks_overdue_count) {
                        echo base_url() . 'index.php/app/processDashboardFilter/task/overdue';
                    } else {
                        echo '#';
                    }
                    ?>">                    
                        <div class="panel red notif">
                            <div class="symbol">
                                <i class="fa fa-briefcase"></i>
                            </div>
                            <div class="state-value">
                                <div class="value"><?php echo $tasks_overdue_count; ?></div>
                                <div class="title"> Overdue</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <!--breadcrumbs start -->
                    <ul class="breadcrumb panel">
                        <li class="active" style="font-weight: 700;">Activities</li>
                    </ul>
                    <!--breadcrumbs end -->
                </div>
            </div>
            <div class="row state-overview">                
                <div class="col-md-4 col-xs-12 col-sm-4">
                    <a href="<?php
                    if ($activity_today_count) {
                        echo base_url() . 'index.php/app/processDashboardFilter/activity/due';
                    } else {
                        echo '#';
                    }
                    ?>">                    
                        <div class="panel deep-purple-box notif">
                            <div class="symbol">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <div class="state-value">
                                <div class="value"><?php echo $activity_today_count; ?></div>
                                <div class="title">  Due</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-xs-12 col-sm-4">
                    <a href="<?php
                    if ($activity_pending_count) {
                        echo base_url() . 'index.php/app/processDashboardFilter/activity/pending';
                    } else {
                        echo '#';
                    }
                    ?>">
                        <div class="panel blue notif">
                            <div class="symbol">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <div class="state-value">
                                <div class="value"><?php echo $activity_pending_count; ?></div>
                                <div class="title">  Pending</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-xs-12 col-sm-4">
                    <a href="<?php
                    if ($activity_overdue_count) {
                        echo base_url() . 'index.php/app/processDashboardFilter/activity/overdue';
                    } else {
                        echo '#';
                    }
                    ?>">                    
                        <div class="panel red notif">
                            <div class="symbol">
                                <i class="fa fa-calendar"></i>
                            </div>
                            <div class="state-value">
                                <div class="value"><?php echo $activity_overdue_count; ?></div>
                                <div class="title">  Overdue</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <!--breadcrumbs start -->
                    <ul class="breadcrumb panel">
                        <li class="active" style="font-weight: 700;">Reminders</li>
                    </ul>
                    <!--breadcrumbs end -->
                </div>
            </div>

            <div class="row state-overview">
                <div class="col-md-4 col-xs-12 col-sm-4">
                    <a href="<?php
                    if ($reminder_today_count) {
                        echo base_url() . 'index.php/app/processDashboardFilter/reminder/due';
                    } else {
                        echo '#';
                    }
                    ?>">                     
                        <div class="panel deep-purple-box notif">
                            <div class="symbol">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <div class="state-value">
                                <div class="value"><?php echo $reminder_today_count; ?></div>
                                <div class="title"> Due</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-xs-12 col-sm-4">
                    <a href="<?php
                    if ($reminder_pending_count) {
                        echo base_url() . 'index.php/app/processDashboardFilter/reminder/pending';
                    } else {
                        echo '#';
                    }
                    ?>">                    
                        <div class="panel blue notif">
                            <div class="symbol">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <div class="state-value">
                                <div class="value"><?php echo $reminder_pending_count; ?></div>
                                <div class="title"> Pending</div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-md-4 col-xs-12 col-sm-4">
                    <a href="<?php
                    if ($reminder_overdue_count) {
                        echo base_url() . 'index.php/app/processDashboardFilter/reminder/overdue';
                    } else {
                        echo '#';
                    }
                    ?>">                    
                        <div class="panel red notif">
                            <div class="symbol">
                                <i class="fa fa-clock-o"></i>
                            </div>
                            <div class="state-value">
                                <div class="value"><?php echo $reminder_overdue_count; ?></div>
                                <div class="title"> Overdue</div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            <!--statistics end-->
        </div>

    </div>

    <div class="row">
        <div class="col-md-12">
            <!--breadcrumbs start -->
            <ul class="breadcrumb panel">
                <li class="active" style="font-weight: 700;">Today</li>
            </ul>
            <!--breadcrumbs end -->
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    Tasks
                    <div>
                        <div class="pull-right">
                            <?php if (isset($tasksummary) && count($tasksummary) > 0) { ?>
                                <?php
//                                $disabled = "";//make all action buttons active                                
                                $content = $status_span = $active_span = "";
                                foreach ($tasksummary as $row):
                                    $id = $row["ID"];
                                    $serial = $row["serial"];
                                    $title = $row["title"];
                                    $active = $row["active"];
                                    $status = $row["status"];
                                    $priority = $row["priority"];
                                    $category = getCurrentCategory($row["category"], $taskcategory_titles);

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

                                    if ($active === "0") {
                                        //inactive
                                        $active_span = "<span class=\"label label-default label-mini status_span\">Inactive</span>";
                                    } elseif ($active === "1") {
                                        //active
                                        $active_span = "<span class=\"label label-success label-mini status_span\">Active</span>";
                                    }

                                    if ($count == 1) {
                                        $active = "active";
                                        $checked = "checked";
                                    } else {
                                        $active = "";
                                        $checked = "";
                                    }

//                                    $date_created = date('F j, Y', strtotime($row["date_created"]));
                                    $notification = date('F j, Y, g:i a', strtotime($row["notification"]));

                                    $content.="<tr class=\"booking_radio $active\">";
                                    $content.="<td>"
                                            . "<input class=\"booking_hidden_id\" type=\"hidden\" value=\"$id\">"; //                                  
                                    $content.="$serial</td>";
                                    $content.="<td>$title</td>";
                                    $content.="<td>$category</td>";
                                    $content.="<td>$active_span</td>";
                                    $content.="<td>$notification</td>";
                                    $content.="<td>$priority</td>";
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
                                        $buttons.="<a onclick=\"taskmanager('view_');\" type=\"button\" class=\"btn btn-primary \"><i class=\"fa fa-eye\"></i>&nbsp;View</a>&nbsp;";
                                    }
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
                                <th>Category</th>
                                <th>Active/Inactive</th>
                                <th>Schedule For</th>
                                <th>Priority</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($tasksummary) > 0) { ?>
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
<!--body wrapper end-->



