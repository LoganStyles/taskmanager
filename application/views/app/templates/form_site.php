<?php
//echo $this->session->us_username;
//echo '<br>'.$this->session->us_access;
//exit;
if (!isset($this->session->us_username)) {
    $redirect = "admin/";
    redirect($redirect);
}

$access = $this->session->us_access;
if ($access < 3) {
    $redirect = "admin/";
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
    <h3>Admin</h3>
    <ul class="breadcrumb">
        <li>Site Information</li>
        <li class="active"> <?php echo $header_title; ?> </li>
    </ul>
</div>
<!-- page heading end-->

<!--body wrapper start-->
<div class="wrapper">    
    <div class="row">
        <div class="col-lg-8">
            <section class="panel">
                <header class="panel-heading">

                </header>

                <?php
                $attributes = array('class' => 'cmxform form-horizontal adminex-form',
                    'id' => 'save_site');
                $hidden = array('site_id' => $ID, 'site_type' => $type);
                echo validation_errors('<span>***</span><span class="error">', '</span><span>***</span><br>');
                echo form_open_multipart('admin/saveSite', $attributes, $hidden);
                ?>
                <input type="hidden" name="site_imageid" id="site_imageid" value="<?php echo $imageid; ?>">

                <div class="panel-body">
                    <div class="form">                        
                        <div class="form-group ">
                            <label for="site_title" class="col-sm-2 control-label">Title</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_title" value="<?php echo $title; ?>" name="site_title" type="text" />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="site_description" class="control-label col-sm-2">Description</label>
                            <div class="col-sm-10">
                                <textarea class="form-control ckeditor" name="site_description" rows="6">
                                    <?php echo $description; ?>
                                </textarea>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="site_address1" class="col-sm-2 control-label">Address1</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_address1" value="<?php echo $address1; ?>" name="site_address1" type="text" />
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="site_address2" class="col-sm-2 control-label">Address2</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_address2" value="<?php echo $address2; ?>" name="site_address2" type="text" />
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="site_email" class="col-sm-2 control-label">Email</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_email" value="<?php echo $email; ?>" name="site_email" type="text" />
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="site_phone" class="col-sm-2 control-label">Phone</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_phone" value="<?php echo $phone; ?>" name="site_phone" type="text" />
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="site_state" class="col-sm-2 control-label">State</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_state" value="<?php echo $state; ?>" name="site_state" type="text" />
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="site_country" class="col-sm-2 control-label">Country</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_country" value="<?php echo $country; ?>" name="site_country" type="text" />
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="site_facebook_url" class="col-sm-2 control-label">Facebook</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_facebook_url" value="<?php echo $facebook_url; ?>" name="site_facebook_url" type="text" />
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="site_twitter_url" class="col-sm-2 control-label">Twitter</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_twitter_url" value="<?php echo $twitter_url; ?>" name="site_twitter_url" type="text" />
                            </div>
                        </div>
                        
                        <div class="form-group ">
                            <label for="site_instagram" class="col-sm-2 control-label">Instagram</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_instagram" value="<?php echo $instagram; ?>" name="site_instagram" type="text" />
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="site_googleplus" class="col-sm-2 control-label">Google Plus</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_googleplus" value="<?php echo $googleplus; ?>" name="site_googleplus" type="text" />
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="site_youtube" class="col-sm-2 control-label">Youtube</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_youtube" value="<?php echo $youtube; ?>" name="site_youtube" type="text" />
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="site_other_socialmedia" class="col-sm-2 control-label">Other Social Media</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_other_socialmedia" value="<?php echo $other_socialmedia; ?>" name="site_other_socialmedia" type="text" />
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="site_site_url" class="col-sm-2 control-label">Site Url</label>
                            <div class="col-sm-10">
                                <input class=" form-control" id="site_site_url" value="<?php echo $site_url; ?>" name="site_site_url" type="text" />
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-md-2">Position</label>
                            <div class="col-md-4">
                                <div id="spinner1">
                                    <div class="input-group input-small">
                                        <input type="text" class="spinner-input form-control" value="<?php echo $position; ?>" maxlength="3" name="site_position" readonly>
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
                        </div>
                        
                        <div class="form-group">
                            <?php foreach ($received as $received_items): ?>

                                <?php
                                $filename = $received_items['filename'];
                                $alt = $received_items['alt'];
                                $imageid = $received_items['imageid'];
                                $image_content = "";
                                if (!empty($filename)) {
                                    $received_image = base_url() . 'images/UPLOADS/' . $filename;
                                    $image_content.="<div class=\"col-md-4\">";
                                    $image_content.="<div class=\"fileupload fileupload-new\" data-provides=\"fileupload\">";
                                    $image_content.="<div class=\"fileupload-new thumbnail\" style=\"width: 200px; height: 150px;\">";
                                    $image_content.="<img src=\"$received_image\" alt=\"\" >";
                                    $delete_button = "<span id=\"$imageid\" class=\"delete_image_only btn btn-danger\"><i class=\"fa fa-trash\"></i> Delete</span>";
                                    $image_content.="</div></div>$delete_button<br/></div>";
                                    echo $image_content;
                                }
                                ?>
                            <?php endforeach; ?>

                        </div>

                        <div class="form-group">
                            <label class="control-label col-md-6">Image Upload( MAX 2MB, [1024 x 1024])</label>

                            <div class="col-md-6">
                                <div class="fileupload fileupload-new" data-provides="fileupload">
                                    <div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
                                    <div>
                                        <span class="btn btn-default btn-file">
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> Add an image</span>
                                            <span class="fileupload-new"><i class="fa fa-paper-clip"></i> </span>
                                            <span class="fileupload-exists"><i class="fa fa-undo"></i> Change</span>
                                            <input type="file" class="default" name="site_filename" />
                                        </span>
                                        <a href="#" class="btn btn-danger fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash"></i> Remove</a>
                                    </div>
                                </div>
                                <br/>
                            </div> 

                            <label class="col-sm-4 control-label col-lg-4" for="site_main">Set this as the main image</label>
                            <div class="col-lg-2 col-sm-2">
                                <select class="form-control " name="site_main">                                    
                                    <option value="0" <?php
                                    if ($main == "0") {
                                        echo 'selected';
                                    }
                                    ?>>NO</option>
                                    <option value="1" <?php
                                    if ($main == "1") {
                                        echo 'selected';
                                    }
                                    ?>>YES</option>
                                </select>
                            </div>
                            <div class="col-lg-2 col-sm-2">                                
                                <label for="site_caption" class="control-label">Caption</label>
                            </div>

                            <div class="col-sm-4">
                                <input name="site_caption" type="text" value="<?php echo $caption; ?>" />
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