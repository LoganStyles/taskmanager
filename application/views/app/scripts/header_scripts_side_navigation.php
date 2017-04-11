<?php
$active = $curr_identifier = $module_new_type = $module_type = "";

$current = $received[0];
extract($current);


$curr_identifier = strtolower($type) . $ID;
$module_new_type = strtolower($type);
$module_type = strtolower($type);


//site info
if (empty($site[0]["ID"])) {
    $site_id = 0;
    $site_type = "";
    $site_filename = "";
    $site_title = "";
    $site_alt = "";
} else {
    $site_id = $site[0]["ID"];
    $site_type = $site[0]["type"];
    $site_filename = $site[0]["filename"];
    $site_title = $site[0]["title"];
    $site_alt = $site[0]["alt"];
}

$curr_name = ($this->session->us_name) ? ($this->session->us_name) : ("");
$curr_access = ($this->session->us_access) ? ($this->session->us_access) : ("");

function getCurrentAction($actions, $actions_titles) {
    $receiver_actions = explode(",", $actions);
    $diplay_title = "";
    foreach ($actions_titles as $row):
        $curr_id = $row["ID"];
        $curr_title = $row["title"];
        $curr_display = $row["display"];
        if ($curr_display === "1") {
            if (in_array($curr_id, $receiver_actions)) {
                $diplay_title .= $curr_title . ",";
            }
        }
    endforeach;
    return rtrim($diplay_title, ",");
}

function getTaskTitle($task_titles,$taskid) {
    if (count($task_titles) > 0) {
        foreach ($task_titles as $row) {
            $curr_ID = $row["ID"];
            $curr_title = ucwords($row["title"]);
                if ($curr_ID == $taskid) {
                    return $curr_title;
                }
        }
        return "";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="keywords" content="app, dashboard">
        <meta name="description" content="">
        <meta name="author" content="">

        <title><?php echo $site_title; ?></title>

        <!--dashboard calendar-->
        <link href="<?php echo base_url(); ?>css_admin/clndr.css" rel="stylesheet" type="text/css">

        <!--file upload-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css_admin/bootstrap-fileupload.min.css" />

        <!--tags input-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>js_admin/jquery-tags-input/jquery.tagsinput.css" />

        <!--common-->
        <link href="<?php echo base_url(); ?>css_admin/style.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>css_admin/style-responsive.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>fonts/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>js_admin/jqwidgets/styles/jqx.base.css" rel="stylesheet">
        <link rel="shortcut icon" href="<?php echo base_url(); ?>images/ico/favicon.ico" type="image/png"/>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <script src="js/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="sticky-header">
        <section>
            <!-- left side start-->
            <div class="left-side sticky-left-side">

                <!--logo and iconic logo start-->
                <div class="logo">
                    <?php
                    $received_image = base_url() . 'images/UPLOADS/' . $site_filename;
                    if (!empty($site_filename)) {
                        //$received_image = "";
                        $image = "<img style=\"height:40px;max-width:100%;\" src=\"$received_image\" alt=\"$site_alt\" >";
                        $url = base_url() . 'index.php/app';
                        $home_link = "<a href=\"$url\">$image</a>";
                        echo $home_link;
                    }
                    ?>
                </div>

                <div class="left-side-inner">

                    <!-- visible to small devices only -->
                    <div class="visible-xs hidden-sm hidden-md hidden-lg">
                        <div class="media logged-user">
                            <!--<img alt="" src="images/photos/user-avatar.png" class="media-object">-->
                            <div class="media-body">
                                <h4><a href="#"><?php echo $curr_name; ?></a></h4>
                                <!--<span>"Hello There..."</span>-->
                            </div>
                        </div>

                        <!--                        <h5 class="left-nav-title">Account Information</h5>
                                                <ul class="nav nav-pills nav-stacked custom-nav">
                                                    <li><a href="#"><i class="fa fa-user"></i> <span>Profile</span></a></li>
                                                    <li><a href="#"><i class="fa fa-cog"></i> <span>Settings</span></a></li>
                                                    <li><a href="#"><i class="fa fa-sign-out"></i> <span>Sign Out</span></a></li>
                                                </ul>-->
                    </div>

                    <!--sidebar nav start-->
                    <ul class="nav nav-pills nav-stacked custom-nav">
                        <li class="menu-list-altered <?php
                    if ($type == 'home') {
                        echo 'nav-active';
                    }
                    ?>"><a href="<?php echo base_url() . 'index.php/app'; ?>"><i class="fa fa-dashboard"></i><span>Dashboard</span></a>                            
                        </li>

                        <li class="menu-list-altered <?php
                        if ($header_title == 'Task') {
                            echo 'nav-active';
                        }
                    ?>"><a href="<?php echo base_url() . 'index.php/app/viewComponent/task/normal/0'; ?>"><i class="fa fa-briefcase"></i><span>Tasks</span></a>                            
                        </li>

                        <li class="menu-list-altered <?php
                        if ($header_title == 'Activity') {
                            echo 'nav-active';
                        }
                    ?>"><a href="<?php echo base_url() . 'index.php/app/viewComponent/activity/normal/0'; ?>"><i class="fa fa-calendar"></i><span>Activities</span></a>                            
                        </li>

                        <li class="menu-list-altered <?php
                        if ($header_title == 'Reminder') {
                            echo 'nav-active';
                        }
                    ?>"><a href="<?php echo base_url() . 'index.php/app/viewComponent/reminder/normal/0'; ?>"><i class="fa fa-clock-o"></i><span>Reminders</span></a>                            
                        </li>

                        <?php if ($curr_access >= 2) { ?>

                            <li class="menu-list <?php
                            if ($header_title == 'Taskcategory') {
                                echo 'nav-active';
                            }
                            ?>"><a href="#"><i class="fa fa-bars"></i><span>Task Categories</span></a>
                                <!--generate modules dynamically-->
                                <ul class="sub-menu-list">
                                    <?php if (count($taskcategory_titles) > 0) { ?>
                                        <?php foreach ($taskcategory_titles as $taskcategory_items): ?>

                                            <?php
                                            $title = $taskcategory_items['title'];
                                            $module_item_id = $taskcategory_items['ID'];
                                            $module_type = strtolower($taskcategory_items['type']);
                                            $module_new_type = ucfirst($taskcategory_items['type']);
                                            $identifier = $module_type . $module_item_id;

                                            if ($identifier == $curr_identifier) {
                                                $active = "active";
                                            }
                                            ?>

                                            <li class="<?php
                                echo $active;
                                $active = '';
                                            ?>"><a href="<?php echo base_url() . 'index.php/app/showOptions/' . $module_item_id . '/' . $module_type . '/view_'; ?> "><?php echo ucfirst($title); ?></a></li>
                                            <?php endforeach; ?>
                                            <?php $module_item_id = "0"; ?>
                                        <?php } ?>
                                    <li ><a style="color:#e32f30;" href="<?php echo base_url() . 'index.php/app/showOptions/0/taskcategory/form_'; ?>"> Add New Task Category</a></li>
                                </ul>
                            </li>


                            <li class="menu-list <?php
                                    if ($header_title == 'Actions') {
                                        echo 'nav-active';
                                    }
                                        ?>"><a href="#"><i class="fa fa-mail-forward"></i><span>Actions</span></a>
                                <!--generate modules dynamically-->
                                <ul class="sub-menu-list">
                                    <?php if (count($actions_titles) > 0) { ?>
                                        <?php foreach ($actions_titles as $actions_items): ?>

                                            <?php
                                            $title = $actions_items['title'];
                                            $module_item_id = $actions_items['ID'];
                                            $module_type = strtolower($actions_items['type']);
                                            $module_new_type = ucfirst($actions_items['type']);
                                            $identifier = $module_type . $module_item_id;

                                            if ($identifier == $curr_identifier) {
                                                $active = "active";
                                            }
                                            ?>

                                            <li class="<?php
                                echo $active;
                                $active = '';
                                            ?>"><a href="<?php echo base_url() . 'index.php/app/showOptions/' . $module_item_id . '/' . $module_type . '/view_'; ?> "><?php echo ucfirst($title); ?></a></li>
                                            <?php endforeach; ?>
                                            <?php $module_item_id = "0"; ?>
                                        <?php } ?>
                                    <li ><a style="color:#e32f30;" href="<?php echo base_url() . 'index.php/app/showOptions/0/actions/form_'; ?>"> Add New Action</a></li>
                                </ul>
                            </li>
                        <?php } ?>

                        <li class="menu-list <?php
                        if ($header_title == 'Trash') {
                            echo 'nav-active';
                        }
                        ?>"><a href="#"><i class="fa fa-trash-o"></i><span>Trash</span></a>

                            <ul class="sub-menu-list">                                
                                <li class="<?php
                        if ($type == "task") {
                            echo "active";
                        }
                        ?>"><a href="<?php echo base_url() . 'index.php/app/showTrash/task/0'; ?>"> Tasks</a></li>
                                <li class="<?php
                                if ($type == "activity") {
                                    echo "active";
                                }
                        ?>"><a href="<?php echo base_url() . 'index.php/app/showTrash/activity/0'; ?>"> Activities</a></li>
                                <li class="<?php
                                if ($type == "reminder") {
                                    echo "active";
                                }
                        ?>"><a href="<?php echo base_url() . 'index.php/app/showTrash/reminder/0'; ?>"> Reminders</a></li>
                            </ul>
                        </li>

                        <?php if ($curr_access >= 2) { ?>

                            <li class="menu-list <?php
                            if ($header_title == 'Users') {
                                echo 'nav-active';
                            }
                            ?>"><a href="#"><i class="fa fa-users"></i><span>Users</span></a>
                                <!--generate modules dynamically-->
                                <ul class="sub-menu-list">
                                    <?php if (count($users_titles) > 0) { ?>
                                        <?php foreach ($users_titles as $users_items): ?>

                                            <?php
                                            $title = $users_items['title'];
                                            $module_item_id = $users_items['ID'];
                                            $module_item_access = intval($users_items['access']);
                                            if ($module_item_access >= 3) {
                                                continue;
                                            }
                                            $module_type = strtolower($users_items['type']);
                                            $module_new_type = ucfirst($users_items['type']);
                                            $identifier = $module_type . $module_item_id;

                                            if ($identifier == $curr_identifier) {
                                                $active = "active";
                                            }
                                            ?>

                                            <li class="<?php
                                echo $active;
                                $active = '';
                                            ?>"><a href="<?php echo base_url() . 'index.php/app/showUsers/' . $module_item_id . '/' . $module_type . '/view_'; ?> "><?php echo ucfirst($title); ?></a></li>
                                            <?php endforeach; ?>
                                            <?php $module_item_id = "0"; ?>
                                        <?php } ?>
                                    <li ><a style="color:#e32f30;" href="<?php echo base_url() . 'index.php/app/showUsers/0/users/form_'; ?>"> Add New User</a></li>
                                </ul>
                            </li>
                        <?php } ?>  

                        <?php if ($curr_access >= 3) { ?>

                            <li class="menu-list-altered <?php
                            if ($header_title == 'Settings') {
                                echo 'nav-active';
                            }
                            ?>"><a href="<?php echo base_url() . 'index.php/app/showSettings'; ?>"><i class="fa fa-cogs"></i><span>Settings</span></a>                            
                            </li>
                        <?php } ?> 

                        <li class="menu-list-altered <?php
                        if ($header_title == 'Reports') {
                            echo 'nav-active';
                        }
                        ?>"><a href="<?php echo base_url() . 'index.php/app/showReports'; ?>"><i class="fa fa-print"></i><span>Reports</span></a>                            
                        </li>

                    </ul>
                    <!--sidebar nav end-->

                </div>
            </div>
            <!-- left side end-->

            <!-- main content start-->
            <div class="main-content" >

                <!-- header section start-->
                <div class="header-section">

                    <!--toggle button start-->
                    <a class="toggle-btn"><i class="fa fa-bars"></i></a>
                    <!--toggle button end-->

                    <!--search start-->
                    <!--                              <form class="searchform" action="index.php" method="post">
                                                        <input type="text" class="form-control" name="keyword" placeholder="Search here..." />
                                                    </form>-->
                    <!--search end-->


                    <!--notification menu start allow this line for forward compatibility-->
                    <div class="menu-right">
                        <ul class="notification-menu">
                            <li>
                                <a href="#" class="btn btn-default dropdown-toggle info-number" data-toggle="dropdown">
                                    <i class="fa fa-bell-o"></i>
                                    <span class="badge" id="notification_box"></span>
                                </a>
                                <div class="dropdown-menu dropdown-menu-head pull-right scrollbars">
                                    <h5 class="title">Notifications</h5>
                                    <ul class="dropdown-list normal-list" id="notif_dropdown">                                      

                                        <!--<li class="new"><a href="">See All Notifications</a></li>-->
                                    </ul>
                                </div>
                            </li>

                            <li>
                                <a href="#" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                                    <img src="<?php echo base_url(); ?>images/avatars/avatar.png" alt="" />
                                    <?php echo $curr_name; ?>
                                    <span class="caret"></span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-usermenu pull-right">    
                                    <li><a href="<?php echo base_url() . 'index.php/app/showPassword'; ?>"> Change Password</a></li>
                                    <li><a href="<?php echo base_url() . 'index.php/app/logout'; ?>"><i class="fa fa-sign-out"></i> Log Out</a></li>
                                </ul>
                            </li>

                        </ul>
                    </div>

                    <!--notification menu end -->

                </div>
                <!-- header section end-->