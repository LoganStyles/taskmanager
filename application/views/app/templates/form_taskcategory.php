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
//    print_r($current);
    $header_title = ucfirst($title);
} else {
    $header_title = "New Item";
}
?>
<!-- page heading start-->
<div class="page-heading">
    <h3></h3>
    <ul class="breadcrumb">
        <li><?php echo ucfirst(str_replace("_", " ", $type)); ?></li>
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
                
                $attributes = array('class' => 'cmxform form-horizontal adminex-form','id' => 'save_taskcategory');
                $hidden = array('taskcategory_id' => $ID, 'taskcategory_type' => $type);
                echo '<div class="'.$danger_style.'">'.$form_error.'</div>';
                echo form_open_multipart('index.php/app/saveTaskcategory/' . $type, $attributes, $hidden);
                ?>
                
                <div class="panel-body">
                    <div class="form">                        
                        <div class="form-group ">
                            <label for="taskcategory_title" class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="taskcategory_title" value="<?php echo $title; ?>" name="taskcategory_title" type="text" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-2">Position</label>
                            <div class="col-md-2">
                                <div id="spinner1">
                                    <div class="input-group input-small">
                                        <input type="text" class="spinner-input form-control" value="<?php echo $position; ?>" maxlength="3" name="taskcategory_position" readonly>
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

                            <label class="col-sm-3 control-label col-lg-3" for="taskcategory_display">Display this item</label>
                            <div class="col-lg-2 col-sm-2">
                                <select class="form-control " name="taskcategory_display">
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

                        

                        <div class="form-group">
                            <label for="taskcategory_description" class="control-label col-sm-2">Short Description (Optional)</label>
                            <div class="col-sm-10">
                                <textarea class="form-control ckeditor" name="taskcategory_description" rows="4">
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