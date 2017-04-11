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
$receiver_actions=explode(",",$receiver);

$options = "<option value=\"0\">None</option>";
if (count($actions_titles) > 0) {
    $content = "";
    foreach ($actions_titles as $row) {
        $curr_ID = $row["ID"];
        $curr_display = $row["display"];
        $curr_title = ucwords($row["title"]);
        if($curr_display==="1"){
           if (in_array($curr_ID, $receiver_actions)) {
            $selected = "selected";
        } else {
            $selected = "";
        }
        $content.="<option value=\"$curr_ID\" $selected>$curr_title</option>";
        }
        
    }
    $options = $content;
}



if (!empty($ID)) {
//if page reload,get current data
    $header_title = ucfirst($title);
} else {
    $header_title = "New Item";
}
?>
<!-- page heading start-->
<div class="page-heading">
    <h3>Activity</h3>
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
                $attributes = array('class' => 'cmxform form-horizontal adminex-form', 'id' => 'activity_form');
                $hidden = array('task_id'=>$taskid, 'activity_id' => $ID, 'activity_type' => $type);
                echo '<div class="' . $danger_style . '">' . $form_error . '</div>';
                echo form_open_multipart('index.php/app/saveActivity/' . $type, $attributes, $hidden);
                ?>

                <div class="panel-body">
                    <div class="form">                        
                        <div class="form-group ">
                            <label for="activity_title" class="col-sm-3 control-label">Title</label>
                            <div class="col-sm-9">
                                <input class=" form-control" id="activity_title" value="<?php echo $title; ?>" name="activity_title" type="text" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-2 control-label col-lg-2">Position</label>
                            <div class="col-md-2">
                                <div id="spinner1">
                                    <div class="input-group input-small">
                                        <input type="text" class="spinner-input form-control" value="<?php echo $position; ?>" maxlength="3" name="activity_position" readonly>
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

                            <label class="col-sm-2 control-label col-lg-2" for="activity_active">Activate</label>
                            <div class="col-lg-2 col-sm-2">
                                <select class="form-control " name="activity_active">
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
                            <label for="activity_sender" class="col-sm-3 control-label">Initiator</label>
                            <div class="col-sm-9">
                                <input class=" form-control" id="activity_sender" value="<?php echo $sender; ?>" name="activity_sender" type="text" />
                            </div>
                        </div>
                        
                        <div class="form-group ">
                            <label for="activity_receiver" class="col-sm-3 control-label">Action</label>
                            <div class="col-sm-9">
                                <select multiple="multiple" class="form-control " name="activity_receiver[]">
                                    <?php echo $options; ?>
                                </select>                                
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="activity_date" class="col-sm-3 control-label">Scheduled Date</label>
                            <div class="col-sm-3" name="activity_date" id="activity_date">
                            </div>

                            <label style="margin-top: 5px;" for="activity_time" class="col-sm-3 control-label">Scheduled Time</label>
                            <div style="margin-top: 5px;" class="col-sm-3" name="activity_time" id="activity_time">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="activity_instruction" class="control-label col-sm-3">Instruction</label>
                            <div class="col-sm-9">
                                <textarea class="form-control ckeditor" name="activity_instruction" rows="4">
                                    <?php echo $instruction; ?>
                                </textarea>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="activity_outcome" class="control-label col-sm-3">Outcome</label>
                            <div class="col-sm-9">
                                <textarea class="form-control ckeditor" name="activity_outcome" rows="4">
                                    <?php echo $outcome; ?>
                                </textarea>
                            </div>
                        </div>
                        
<!--                        <div class="form-group">
                            <label for="activity_description" class="control-label col-sm-3">Short Description (Optional)</label>
                            <div class="col-sm-9">
                                <textarea class="form-control ckeditor" name="activity_description" rows="4">
                                    <?php echo $description; ?>
                                </textarea>
                            </div>
                        </div>-->

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



