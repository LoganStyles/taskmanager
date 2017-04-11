<?php
if (!isset($this->session->us_username)) {
    $redirect = "index.php/app/";
    redirect($redirect);
}

$access = $this->session->us_access;
if ($access < 1) {
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
    <h3>Reminders</h3>
    <ul class="breadcrumb">
        <li><a class="active" href="">All <?php echo ucfirst(str_replace("_", " ", $type)); ?></a></li>
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
                if ($form_error) {
                    $danger_style = "alert alert-danger error";
                } else {
                    $danger_style = "";
                }
                $attributes = array('class' => 'cmxform form-horizontal adminex-form', 'id' => 'reminder_form');
                $hidden = array('reminder_id' => $ID, 'reminder_type' => $type);
                echo '<div class="' . $danger_style . '">' . $form_error . '</div>';
                echo form_open_multipart('index.php/app/saveReminder/' . $type, $attributes, $hidden);
                ?>

                <div class="panel-body">
                    <div class="form">                        
                        <div class="form-group ">
                            <label for="reminder_title" class="col-sm-3 control-label">Title</label>
                            <div class="col-sm-9">
                                <input class=" form-control" id="reminder_title" value="<?php echo $title; ?>" name="reminder_title" type="text" />
                            </div>
                        </div>

                        <div class="form-group">
<!--                            <label class="col-sm-3 control-label col-lg-3" for="reminder_category">Task Category</label>
                            <div class="col-lg-4 col-sm-4">
                                <select class="form-control " name="reminder_category">
                                    <?php echo $categories; ?>
                                </select>
                            </div>-->

                            <label class="col-sm-3 control-label col-lg-3" for="reminder_active">Activate</label>
                            <div class="col-lg-2 col-sm-2">
                                <select class="form-control " name="reminder_active">
                                    <option value="1" <?php
                                    if ($active === "1") {
                                        echo 'selected';
                                    }
                                    ?>>YES</option>
                                    <option value="0" <?php
                                    if ($active === "0") {
                                        echo 'selected';
                                    }
                                    ?>>NO</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="reminder_date" class="col-sm-3 control-label">Scheduled Date</label>
                            <div class="col-sm-3" name="reminder_date" id="reminder_date">
                            </div>

                            <label style="margin-top: 5px;" for="reminder_time" class="col-sm-3 control-label">Scheduled Time</label>
                            <div style="margin-top: 5px;" class="col-sm-3" name="reminder_time" id="reminder_time">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="reminder_description" class="control-label col-sm-3">Short Description (Optional)</label>
                            <div class="col-sm-9">
                                <textarea class="form-control ckeditor" name="reminder_description" rows="4">
                                    <?php echo $description; ?>
                                </textarea>
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



