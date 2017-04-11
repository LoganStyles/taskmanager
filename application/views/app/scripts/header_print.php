<?php
$active = $curr_identifier = $module_new_type = $module_type = "";

$current = $received[0];
extract($current);


$curr_identifier = strtolower($type) . $ID;
$module_new_type = strtolower($type);
$module_type = strtolower($type);


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
                $diplay_title .= $curr_title.",";
            }
        }
    endforeach;
    return rtrim($diplay_title,",");
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
        <link rel="shortcut icon" href="#" type="image/png">

        <title><?php echo $header_title;?></title>

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
        <link rel="shortcut icon" href="<?php echo base_url(); ?>images/ico/favicon.ico" />

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <script src="js/respond.min.js"></script>
        <![endif]-->
        <style>
            @media print {
            .noprint_header, .hide { visibility: hidden }
            }
        </style>
    </head>

    <body class="sticky-header">
        
            <!-- left side start-->
            
            <!-- left side end-->

            <!-- main content start-->
            <div class="main-content" style="margin-left: -10px;" >

                <!-- header section start-->
                
                <!-- header section end-->