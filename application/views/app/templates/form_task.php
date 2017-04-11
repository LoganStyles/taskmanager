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

$categories = "<option value=\"0\">None</option>";
if (count($taskcategory_titles) > 0) {
    $content = "";
    foreach ($taskcategory_titles as $row) {
        $curr_ID = $row["ID"];
        $curr_display = $row["display"];
        $curr_title = ucwords($row["title"]);
        if ($curr_display === "1") {
            if ($curr_ID === $category) {
                $selected = "selected";
            } else {
                $selected = "";
            }
            $content.="<option value=\"$curr_ID\" $selected>$curr_title</option>";
        }
    }
    $categories = $content;
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
    <h3>Tasks</h3>
    <ul class="breadcrumb">
        <li><a class="active" href="">All <?php echo ucfirst(str_replace("_", " ", $type)); ?></a></li>
        <li class="active"> <?php echo $header_title; ?> </li>
    </ul>
</div>
<!-- page heading end-->

<ul class="breadcrumb">
    <li>
        <a href="<?php
if (isset($_SESSION['back_uri'])) {
    echo $this->session->back_uri;
} else {
    echo base_url();
}
?>">Go Back</a>            
    </li>        

</ul>

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
                $attributes = array('class' => 'cmxform form-horizontal adminex-form', 'id' => 'task_form');
                $hidden = array('task_id' => $ID, 'task_type' => $type);
                echo '<div class="' . $danger_style . '">' . $form_error . '</div>';
                echo form_open_multipart('index.php/app/saveTask/' . $type, $attributes, $hidden);
                ?>

                <div class="panel-body">
                    <div class="form">                        
                        <div class="form-group ">
                            <label for="task_title" class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-8">
                                <input class=" form-control" id="task_title" value="<?php echo $title; ?>" name="task_title" type="text" />
                            </div>

                        </div>

                        <div class="form-group ">
                            <label for="task_ref_id" class="col-sm-2 control-label">Reference ID</label>
                            <div class="col-sm-6">
                                <input class=" form-control" id="task_ref_id" value="<?php echo $ref_id; ?>" name="task_ref_id" type="text" />
                            </div>

                            <label class="col-sm-2 control-label col-lg-2" for="task_priority">Priority</label>
                            <div class="col-lg-2 col-sm-2">
                                <select class="form-control " name="task_priority">
                                    <option value="1" <?php
                if ($priority === "1") {
                    echo 'selected';
                }
                ?>>1</option>
                                    <option value="2" <?php
                                    if ($priority === "2") {
                                        echo 'selected';
                                    }
                ?>>2</option>
                                    <option value="3" <?php
                                    if ($priority === "3") {
                                        echo 'selected';
                                    }
                ?>>3</option>
                                    <option value="4" <?php
                                    if ($priority === "4") {
                                        echo 'selected';
                                    }
                ?>>4</option>
                                    <option value="5" <?php
                                    if ($priority === "5") {
                                        echo 'selected';
                                    }
                ?>>5</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label col-lg-3" for="task_category">Category</label>
                            <div class="col-lg-4 col-sm-4">
                                <select class="form-control " name="task_category">
                                    <?php echo $categories; ?>
                                </select>
                            </div>

                            <label class="col-sm-3 control-label col-lg-3" for="task_active">Activate</label>
                            <div class="col-lg-2 col-sm-2">
                                <select class="form-control " name="task_active">
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
                            <label for="task_date" class="col-sm-3 control-label">Scheduled Date</label>
                            <div class="col-sm-3" name="task_date" id="task_date">
                            </div>

                            <label style="margin-top: 5px;" for="task_time" class="col-sm-3 control-label">Scheduled Time</label>
                            <div style="margin-top: 5px;" class="col-sm-3" name="task_time" id="task_time">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="task_description" class="control-label col-sm-3">Short Description (Optional)</label>
                            <div class="col-sm-9">
                                <textarea class="form-control ckeditor" name="task_description" rows="4">
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



