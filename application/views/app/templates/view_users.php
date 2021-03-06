<?php
$current = $received[0];
//print_r($received[0]);exit;
extract($current);
if (!empty($ID)) {
//if page reload,get current data   
    $header_title = ucfirst($title);
} else {
    show_404();
}

$curr_access = $this->session->us_access;
?>
<!-- page heading start-->
<div class="page-heading">
    <h3>    </h3>
    <ul class="breadcrumb">
        <li>
            <?php echo ucfirst($type); ?>
        </li>
        <li class="active"> <?php echo $header_title; ?> </li>
    </ul>
</div>
<!-- page heading end-->

<!--body wrapper start-->
<div class="wrapper"> 
    <div class="row">
        <div class="col-lg-8">
            
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $title; ?></h3>
                </div>

                <div class="panel-body">

                    <h4> Username </h4>
                    <div class="well">
                        <?php echo $signature; ?>
                    </div>

                    <h4> Access Type </h4>
                    <div class="well">
                        <?php If($access=="1"){
                            echo 'Basic User';
                        } elseif($access=="2"){
                            echo 'Administrator';
                        }elseif($access=="3"){
                            echo 'Super User';
                        }else{
                            echo 'Invalid User';}?>
                    </div>

<!--                    <div class="form-group">

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
                    </div>-->
                </div>
                <div class="panel-footer">
                    <?php
                    if ($curr_access >=2) {
                        $buttons = "<a href=\"" . base_url() . "index.php/app/showUsers/" . $ID . "/" . $type . "/form_" . "\"class=\"btn btn-success  btn-sm\">Edit</a>";
                        $buttons.="&nbsp;<a href=\"" . base_url() . "index.php/app/delete/" . $ID . "/" . $type . "\"class=\"btn btn-primary  btn-sm\">Delete</a>";
                        echo $buttons;
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<!--body wrapper end-->