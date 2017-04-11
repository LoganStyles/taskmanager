<?php
$access = $this->session->us_access;

$current = $received[0];
extract($current);
if (!empty($ID)) {
//if page reload,get current data   
    $header_title = ucfirst($title);
} else {
    show_404();
}
?>
<!-- page heading start-->
<div class="page-heading">
    <h3>   Admin  </h3>
    <ul class="breadcrumb">
        <li>
            <a href="#">Site Information</a>
        </li>
        <li class="active"> <?php echo $header_title; ?> </li>
    </ul>
</div>
<!-- page heading end-->

<!--body wrapper start-->
<div class="wrapper"> 
    <div class="row">
        <div class="col-lg-8">            
            <div class="panel-footer">
                <?php
                if ($access == "admin" || $access == "super") {                    
                    $buttons = "<a href=\"" . base_url() . "admin/showSite/" . $ID . "/" . $type . "/form_" . "\"class=\"btn btn-success  btn-sm\">Edit</a>";
                    
                    echo $buttons;
                }
                ?>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $title; ?></h3>
                </div>

                <div class="panel-body">

                    <h4> Description </h4>
                    <div class="well">
                        <?php echo $description; ?>
                    </div>

                    <h4> Address1 </h4>
                    <div class="well">
                        <?php echo $address1; ?>
                    </div>

                    <h4> Address2 </h4>
                    <div class="well">
                        <?php echo $address2; ?>
                    </div>

                    <h4> State </h4>
                    <div class="well">
                        <?php echo $state; ?>
                    </div>

                    <h4> Country </h4>
                    <div class="well">
                        <?php echo $country; ?>
                    </div>

                    <h4> Email </h4>
                    <div class="well">
                        <?php echo $email; ?>
                    </div>

                    <h4> Phone </h4>
                    <div class="well">
                        <?php echo $phone; ?>
                    </div>

                    <h4> Facebook </h4>
                    <div class="well">
                        <?php echo $facebook_url; ?>
                    </div>

                    <h4> Twitter </h4>
                    <div class="well">
                        <?php echo $twitter_url; ?>
                    </div>
                    
                    <h4> Instagram </h4>
                    <div class="well">
                        <?php echo $instagram; ?>
                    </div>

                    <h4> Google Plus </h4>
                    <div class="well">
                        <?php echo $googleplus; ?>
                    </div>

                    <h4> Youtube </h4>
                    <div class="well">
                        <?php echo $youtube; ?>
                    </div>

                    <h4> Other Social Media </h4>
                    <div class="well">
                        <?php echo $other_socialmedia; ?>
                    </div>

                    <h4> Site url </h4>
                    <div class="well">
                        <?php echo $site_url; ?>
                    </div>

                    <div class="form-group">

                        <?php foreach ($received as $received_items): ?>

                            <?php
                            $filename = $received_items['filename'];
                            $alt = $received_items['alt'];
                            $image_content = "";
                            if (!empty($filename)) {
                                $received_image = base_url() . 'images/UPLOADS/' . $filename;
                                $image_content.="<div class=\"col-md-4\">";
                                $image_content.="<div class=\"fileupload fileupload-new\" data-provides=\"fileupload\">";
                                $image_content.="<div class=\"fileupload-new thumbnail\" style=\"width: 200px; height: 150px;\">";
                                $image_content.="<img src=\"$received_image\" alt=\"\" >";
                                $image_content.="</div></div><br/></div>";
                                echo $image_content;
                            }
                            ?>
                        <?php endforeach; ?>
                    </div>
                </div>
                <div class="panel-footer">
                <?php
                if ($access == "admin" || $access == "super") {                    
                    $buttons = "<a href=\"" . base_url() . "admin/showSite/" . $ID . "/" . $type . "/form_" . "\"class=\"btn btn-success  btn-sm\">Edit</a>";
                    
                    echo $buttons;
                }
                ?>
            </div>
            </div>
        </div>
    </div>
</div>
<!--body wrapper end-->