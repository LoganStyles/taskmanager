<?php
if (isset($_SESSION['us_username'])) {
    $redirect = "index.php/app/";
    redirect($redirect);
}

$current = $received[0];
extract($current);

$site_id = $site[0]["ID"];
$site_type = $site[0]["type"];
$site_filename = $site[0]["filename"];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="#" type="image/png">

        <title>Task manager | Login</title>

        <!--common-->
        <link href="<?php echo base_url(); ?>css_admin/style.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>css_admin/style-responsive.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>fonts/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link rel="shortcut icon" href="<?php echo base_url(); ?>images/ico/favicon.ico" type="image/png"/>

        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <script src="js/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="login-body">
        <div class="container">
            <?php
            $attributes = array('class' => 'form-signin', 'id' => 'save_login');
            echo form_open('index.php/app/login/users', $attributes);
            ?>
            <div class="form-signin-heading text-center">
                <h1 class="sign-title">Sign In</h1>
                <?php
                $received_image = base_url() . 'images/UPLOADS/' . $site_filename;
                if (!empty($site_filename)) {
                    $image = "<img style=\"height:40px;max-width:100%;\" src=\"$received_image\" alt=\"task manager logo\" >";
                    echo $image;
                }
                ?>
            </div>
            <div class="login-wrap">
                <?php
                if ($login_error) {
                    $danger_style = "alert alert-danger error";
                } else {
                    $danger_style = "";
                }
                
//                if ($password_change_success) {
//                    $success_style = "alert alert-success";
//                } else {
//                    $success_style = "";
//                }
                echo validation_errors('<span>***</span><span class="error">', '</span><span>***</span><br>');
                echo '<div class="' . $danger_style . '">' . $login_error . '</div>';
//                echo '<div class="' . $success_style . '">' . $password_change_success . '</div>';
                ?>
                <input name="login_signature" type="text" class="form-control" placeholder="Username" value="<?php echo $signature; ?>" autofocus>
                <input name="login_password" type="password" class="form-control" placeholder="Password" value="<?php echo $password; ?>">
                <div class="checkbox_radio">
                    <input name="login_remember" type="checkbox" value="1" checked>
                    <label  style="color: #fff;">Remember Me</label>
                </div>

                <input class="btn btn-lg btn-login btn-block" type="submit" name="submit" value="Login" />

            </div>

        </form>
        <footer>
            <!--&copy; <?php echo date('Y'); ?>  Powered by <a href="http://webmobiles.com.ng/" target="_blank" >Webmobiles IT Services Ltd</a>-->
        </footer>

    </div>
    <!-- Placed js at the end of the document so the pages load faster -->
    <script src="<?php echo base_url(); ?>js_admin/jquery-1.10.2.min.js"></script>
    <script src="<?php echo base_url(); ?>js_admin/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>js_admin/modernizr.min.js"></script>
    
    <!--icheck -->
<script src="<?php echo base_url(); ?>js_admin/iCheck/jquery.icheck.js"></script>
<script src="<?php echo base_url(); ?>js_admin/icheck-init.js"></script>

    <script>
        $(document).ready(function () {
            $('body').on('click', '.checkbox_radio', function () {               
                if ($('input[name="login_remember"]').is(':checked')) {
                    $('input[name="login_remember"]').prop("checked", false);
                    console.log('input val has been unset');
                    $('input[name="login_remember"]').val('0');
                } else {
                    $('input[name="login_remember"]').prop("checked", true);
                    console.log('input val is now set');
                    $('input[name="login_remember"]').val('1');
                }
            });
        });
    </script>

</body>
</html>

