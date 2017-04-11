<?php
//$access = $this->session->us_access;
//if (!isset($this->session->us_username) || ($access < 3)) {
//    $redirect = "index.php/app/";
//    redirect($redirect);
//}

$current = $received[0];
extract($current);

$site_id = $site[0]["ID"];
$site_type = $site[0]["type"];
$site_filename = $site[0]["filename"];
$site_alt = $site[0]["alt"];
$site_title = $site[0]["title"];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="shortcut icon" href="#" type="image/png">

        <title><?php echo $site_title; ?>  | Change Password</title>

        <!--common-->
        <link href="<?php echo base_url(); ?>css_admin/style.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>css_admin/style-responsive.css" rel="stylesheet" type="text/css">
        <link href="<?php echo base_url(); ?>fonts/css/font-awesome.min.css" rel="stylesheet" type="text/css">


        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="js/html5shiv.js"></script>
        <script src="js/respond.min.js"></script>
        <![endif]-->
    </head>

    <body class="login-body">

        <div class="container">

            <?php
            if($password_error){
                    $danger_style="alert alert-danger error";
                }else{
                  $danger_style="";  
                }
            $attributes = array('class' => 'form-signin',
                'id' => 'save_password');
            echo form_open('index.php/app/changePassword', $attributes);
            
            ?>
            <div class="form-signin-heading text-center">
                <h1 class="sign-title">Change Password</h1>
                <?php
                $received_image = base_url() . 'images/' . $site_filename;
                if (!empty($site_filename)) {
                    $image = "<img style=\"height:40px;max-width:100%;\" src=\"$received_image\" alt=\"$site_alt\" >";
                    echo $image;
                }
                ?>
            </div>


            <div class="login-wrap">
                <?php
                echo '<div class="'.$danger_style.'">'.$password_error.'</div>';
                ?>
                
                <input name="users_oldpassword" type="password" placeholder="Old Password  *" class="form-control">
                <input name="users_hashed_p" type="password" placeholder="New Password *" class="form-control">
                <input name="users_cpassword" type="password" placeholder="Re-type New Password *" class="form-control">

                <input class="btn btn-lg btn-login btn-block" type="submit" name="submit" value="go" />
                <div class="registration">

                    <a class="" href="<?php echo base_url() . 'index.php/app/'; ?>">
                        Back to Dashboard
                    </a>
                </div>



            </div>

        </form>

    </div>



    <!-- Placed js at the end of the document so the pages load faster -->

    <!-- Placed js at the end of the document so the pages load faster -->
    <script src="<?php echo base_url(); ?>js_admin/jquery-1.10.2.min.js"></script>
    <script src="<?php echo base_url(); ?>js_admin/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>js_admin/modernizr.min.js"></script>

</body>
</html>
