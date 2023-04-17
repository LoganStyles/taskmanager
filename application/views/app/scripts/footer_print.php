<?php
$today = date("d/m/Y", strtotime('now'));
$now = date("H:i:s", strtotime('now'));
?>
<!--footer section start-->
<footer>
    <!--&copy; <?php echo date('Y'); ?>  Powered by <a href="http://webmobiles.com.ng/" target="_blank" >Webmobiles IT Services Ltd</a>-->
</footer>
<!--footer section end-->


</div>
<!-- main content end-->
</section>

<!-- Placed js at the end of the document so the pages load faster -->
<script src="<?php echo base_url(); ?>js_admin/jquery-3.6.4.min.js"></script>
<script src="<?php echo base_url(); ?>js_admin/jquery-ui-1.9.2.custom.min.js"></script>
<script src="<?php echo base_url(); ?>js_admin/jquery-migrate-1.2.1.min.js"></script>
<script src="<?php echo base_url(); ?>js_admin/bootstrap.min.js"></script>
<script src="<?php echo base_url(); ?>js_admin/modernizr.min.js"></script>
<script src="<?php echo base_url(); ?>js_admin/jquery.nicescroll.js"></script>

<script type="text/javascript" src="<?php echo base_url(); ?>js_admin/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js_admin/jquery.validate.min.js"></script>
<script src="<?php echo base_url(); ?>js_admin/validation-init.js"></script>

<!--spinner-->
<script type="text/javascript" src="<?php echo base_url(); ?>js_admin/fuelux/js/spinner.min.js"></script>
<script src="<?php echo base_url(); ?>js_admin/spinner-init.js"></script>
<!--file upload-->
<script type="text/javascript" src="<?php echo base_url(); ?>js_admin/bootstrap-fileupload.min.js"></script>

<!--tags input-->
<script src="<?php echo base_url(); ?>js_admin/jquery-tags-input/jquery.tagsinput.js"></script>
<script src="<?php echo base_url(); ?>js_admin/tagsinput-init.js"></script>

<!--common scripts for all pages-->
<script src="<?php echo base_url(); ?>js_admin/scripts.js"></script>

<script type="text/javascript">
    function printPage() {
        window.print();
    }
</script>

</body>
</html>

