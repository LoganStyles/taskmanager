<?php
if (!isset($this->session->us_username)) {
    $redirect = "index.php/app/";
    redirect($redirect);
}

$access = $this->session->us_access;
if ($access <2) {
    $redirect = "index.php/app/";
    redirect($redirect);
}
$current = $received[0];
extract($current);


if (!empty($ID)) {
//if page reload,get current data
    $header_title = ucfirst($title);
} else {
    $header_title = "New Item";
}
?>
<!-- page heading start-->
<div class="page-heading">
    <h3></h3>
    <ul class="breadcrumb">
        <li><?php echo ucfirst($type); ?></li>
        <li class="active"> <?php echo $header_title; ?> </li>
    </ul>
</div>
<!-- page heading end-->

<!--body wrapper start-->
<div class="wrapper">    
    <div class="row">
        <div class="col-lg-10">
            <section class="panel">
                <header class="panel-heading">

                </header>

                <?php
                if($form_error){
                    $danger_style="alert alert-danger error";
                }else{
                  $danger_style="";  
                }
                $attributes = array('class' => 'cmxform form-horizontal adminex-form','id' => 'save_users');
                $hidden = array('users_id' => $ID, 'users_type' => $type);
                echo '<div class="'.$danger_style.'">'.$form_error.'</div>';
                echo form_open_multipart('index.php/app/saveUsers/' . $type, $attributes, $hidden);
                ?>
                <!--<input type="hidden" name="users_imageid" id="users_imageid" value="<?php echo $imageid; ?>">-->

                <div class="panel-body">
                    <div class="form">                        
                        <div class="form-group ">
                            <label for="users_title" class="col-sm-2 control-label">Name</label>
                            <div class="col-sm-8">
                                <input class=" form-control" id="users_title" value="<?php echo $title; ?>" name="users_title" type="text" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="users_signature" class="control-label col-sm-2">Username</label>
                            <div class="col-sm-6">
                                <input class=" form-control" id="users_signature" value="<?php echo $signature; ?>" name="users_signature" type="text" />                                
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="users_hashed_p" class="control-label col-sm-2">Password</label>
                            <div class="col-sm-6">
                                <input class=" form-control" id="users_hashed_p" value="<?php echo $hashed_p; ?>" name="users_hashed_p" type="password" />                                
                            </div>
                        </div>

                        <div class="form-group">                           
                            <label class="col-sm-2 control-label col-lg-2" for="users_access">Access</label>
                            <div class="col-lg-4 col-sm-4">
                                <select class="form-control " name="users_access">
                                    <option value="1" <?php
                                    if ($access =="1") {
                                        echo 'selected';
                                    }
                                    ?>>Basic User</option>
                                    <option value="2" <?php
                                    if ($access =="2") {
                                        echo 'selected';
                                    }
                                    ?>>Administrator</option>
<!--                                    <option value="3" <?php
                                    if ($access =="3") {
                                        echo 'selected';
                                    }
                                    ?>>Super User</option>-->
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="control-label col-md-2">Position</label>
                            <div class="col-md-4">
                                <div id="spinner1">
                                    <div class="input-group input-small">
                                        <input type="text" class="spinner-input form-control" value="<?php echo $position; ?>" maxlength="3" name="users_position" readonly>
                                        <div class="spinner-buttons input-group-btn btn-group-vertical">
                                            <button type="button" class="btn spinner-up btn-xs btn-default">
                                                <i class="fa fa-angle-up"></i>
                                            </button>
                                            <button type="button" class="btn spinner-down btn-xs btn-default">
                                                <i class="fa fa-angle-down"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <span class="help-block">
                                    <!--basic example-->
                                </span>
                            </div>

                            <label class="col-sm-3 control-label col-lg-3" for="users_display">Display this item</label>
                            <div class="col-lg-2 col-sm-2">
                                <select class="form-control " name="users_display">
                                    <option value="1" <?php
                                    if ($display === "1") {
                                        echo 'selected';
                                    }
                                    ?>>YES</option>
                                    <option value="0" <?php
                                    if ($display === "0") {
                                        echo 'selected';
                                    }
                                    ?>>NO</option>
                                </select>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="panel-footer">
                    <input class="btn btn-success btn-sm" type="submit" name="submit" value="Save" />
                </div>
                </form>
            </section>
        </div>
    </div>
</div>
<!--body wrapper end-->