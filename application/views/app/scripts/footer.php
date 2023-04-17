<?php
$today = date("d/m/Y", strtotime('now'));
$now = date("H:i:s", strtotime('now'));
?>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="delete_modal" class="modal fade">
    <div class="modal-dialog" style="width: 600px;">
        <div class="modal-content">
            <div class="modal-header panel-heading dark" >                
                <h4 class="modal-title">Delete Dialog</h4>
            </div>
            <div class="modal-body">

                <?php
                $attributes = array('class' => 'cmxform form-horizontal adminex-form', 'id' => 'delete_form');
                echo form_open_multipart('index.php/app/processDelete', $attributes);
                ?>
                <div class="panel-body">
                    <div class="row">
                        <div class="form">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <h4>Are You Sure You Want To Delete This Item?</h4>
                                    <input type="hidden" value="" name="delete_id" id="delete_id">
                                    <input type="hidden" value="" name="delete_type" id="delete_type">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <input class="btn btn-success btn-sm" type="submit" name="submit" value="YES" />
                <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                </form> 
            </div>
        </div>
    </div>
</div>

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="restore_modal" class="modal fade">
    <div class="modal-dialog" style="width: 600px;">
        <div class="modal-content">
            <div class="modal-header panel-heading dark" >                
                <h4 class="modal-title">Restore Dialog</h4>
            </div>
            <div class="modal-body">

                <?php
                $attributes = array('class' => 'cmxform form-horizontal adminex-form', 'id' => 'restore_form');
                echo form_open_multipart('index.php/app/processRestore', $attributes);
                ?>
                <div class="panel-body">
                    <div class="row">
                        <div class="form">
                            <div class="col-md-12">
                                <div class="form-group ">
                                    <h4>Are You Sure You Want To Restore This Item?</h4>
                                    <input type="hidden" value="" name="restore_id" id="restore_id">
                                    <input type="hidden" value="" name="restore_type" id="restore_type">
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <input class="btn btn-success btn-sm" type="submit" name="submit" value="YES" />
                <button type="button" class="btn btn-default" data-dismiss="modal">NO</button>
                </form> 
            </div>
        </div>
    </div>
</div>
<!--footer section start-->
<div>
    <audio id="audio_id">
        <source src='<?php echo base_url(); ?>images/notif/notify.mp3' type='audio/mpeg'>
        <source src='<?php echo base_url(); ?>images/notif/notify.wav' type='audio/wav'>
    </audio>
</div>
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

<!--icheck -->
<script src="<?php echo base_url(); ?>js_admin/iCheck/jquery.icheck.js"></script>
<script src="<?php echo base_url(); ?>js_admin/icheck-init.js"></script>

<!--datetime picker-->
<script type="text/javascript" src="<?php echo base_url(); ?>js_admin/jqwidgets/jqxcore.js"></script> 
<script type="text/javascript" src="<?php echo base_url(); ?>js_admin/jqwidgets/demos.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js_admin/jqwidgets/jqxdatetimeinput.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js_admin/jqwidgets/jqxcalendar.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js_admin/jqwidgets/jqxtooltip.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js_admin/jqwidgets/globalization/globalize.js"></script>


<!--common scripts for all pages-->
<script src="<?php echo base_url(); ?>js_admin/scripts.js"></script>

<script type="text/javascript">
    var base_url = "<?php echo base_url(); ?>";

    var server_url = base_url + "index.php/app/serverUpdate";

    if (typeof (EventSource) !== "undefined") {
        var source = new EventSource(server_url);

        source.addEventListener('message', function (event) {
            var json_object = eval('(' + event.data + ')');
            updateNotifications(json_object, "partial");
        }, false);
    } else {
        console.log("No SSE support");
    }


    function updateNotifications(obj, loadtype) {
        console.log('typeof obj: ' + typeof obj);
        if ($.isEmptyObject(obj) === false) {
            var count = 0;
            var current_notifications = 0;
            var notif_count = obj.length;
            console.log('received notif count: ' + notif_count);

            var id = 0;
            var title = "";
            var type = "";
            var taskid = 0;
            var notification = "";
            var notifid = "";
            var view_redirect = "";
            var content = "";
            var notif_identifier = "";
            var view_redirect = "";//view link
            var close = "close";
            var ignore = "ignore";
            var trash = "trash";
            var icon = "";

            var notif_heading = "";

            $.each(obj, function (key, value) {
                id = value.ID;
                title = value.title;
                type = value.type;
                taskid = value.taskid;
                notification = value.notification;
                notif_identifier = "#notif_" + type + "_" + id;
                if ($(notif_identifier).length === 0) {//not previously existing


                    switch (type) {
                        case 'task':
                            notif_heading = "Task";
                            icon = "fa-briefcase";
                            view_redirect = base_url + "index.php/app/viewTaskActivities/" + id + "/task/";
                            break;
                        case 'activity':
                            notif_heading = "Activity";
                            icon = "fa-calendar";
                            view_redirect = base_url + "index.php/app/showActivity/" + taskid + "/" + id + "/activity/view_";
                            break;
                        case 'reminder':
                            notif_heading = "Reminder";
                            icon = "fa-clock-o";
                            view_redirect = base_url + "index.php/app/showReminder/" + id + "/reminder/view_";
                            break;
                    }

                    console.log('new id: ' + id);
                    notifid = "notif_" + type + "_" + id;
                    content += '<li class="new notif_msg" id="' + notifid + '">';
                    content += '<div style="width: 100%; margin-bottom: 2%">';
                    content += '<div class="notif_icon label label-danger"><i class="fa ' + icon + '"></i></div>';
                    content += '<div class="notif_text">' + notif_heading + '</div>';
                    content += '<div class="notif_text_desc">' + title + '</div>';
                    content += '<div style="clear: both;"></div>';
                    content += '</div>';
                    content += '<div style="width: 100%;" class="notif_buttons">';
                    content += "<input class='notif_id' type='hidden' value='" + id + "'>";
                    content += "<input class='notif_type' type='hidden' value='" + type + "'>";
                    content += '<div class="notifactions"><a href="' + view_redirect + '">View</a></div>';
                    content += '<div class="notifactions"><a class="close_notif">Close</a></div>';
                    content += '<div class="notifactions"><a class="ignore_notif">Ignore</a></div>';
                    content += '<div class="notifactions"><a class="trash_notif");">Trash</a></div>';
                    content += '<div style="clear: both;"></div></div></li>';
                    count++;
                }

            });
            if (count) {
                if (loadtype == "full") {
                    $('#notif_dropdown').html(content);
                } else if (loadtype == "partial") {
                    $('#notif_dropdown').prepend(content);
                }

                current_notifications = $('#notif_dropdown').children('li');
                $('#notification_box').text(current_notifications.length);
                if (loadtype == "partial") {
                    $('#audio_id')[0].play();
                }

            }
        }

    }

    function updateNotifAction(type, id, action) {
        var new_val = 0;
        var removed_identifier = "#notif_" + type + "_" + id;
        /*performs close/ignore/trash on items*/
        var url = base_url + "index.php/app/updateItem/" + type + "/" + id + "/" + action;
        $.ajax({
            type: "POST",
            url: url,
            dataType: "text",
            success: function (data) {
                console.log('result data: ' + data);
                //pop off from notification list                
                $(removed_identifier).remove();
                new_val = parseInt($('#notification_box').text()) - 1;
                $('#notification_box').text(new_val);
            },
            error: function () {
                console.log('update item failed');
            }
        });
    }

    function taskmanager(formtype) {
        var task_id = $('.booking_radio.active .booking_hidden_id').val();
        console.log('task_id is ' + task_id);
        if (formtype == "view_") {
            var redirect = base_url + "index.php/app/viewTaskActivities/" + task_id + "/task/";
        } else if (formtype == "form_") {
            var redirect = base_url + "index.php/app/showTask/" + task_id + "/task/" + formtype;
        }
        window.location = redirect;
    }

    function activityManager(formtype, task_id) {
        var id = $('.booking_radio.active .booking_hidden_id').val();
        console.log('task_id is ' + task_id);
        console.log('id is ' + id);
        console.log('formtype is ' + formtype);
        var redirect = base_url + "index.php/app/showActivity/" + task_id + "/" + id + "/activity/" + formtype;
        window.location = redirect;
    }

    function remindermanager(formtype) {
        var id = $('.booking_radio.active .booking_hidden_id').val();
        console.log('id is ' + id);
        var redirect = base_url + "index.php/app/showReminder/" + id + "/reminder/" + formtype;
        window.location = redirect;
    }

    function printmanager(type) {
        if (type == "tasks") {
            var task_id = $('.booking_radio.active .booking_hidden_id').val();
            $("#printfilter_task_id").val(task_id);
            $("#print_preview_modal h4").text("PRINT A TASK WITH ACTIVITIES");
            $("#print_preview_modal").modal({backdrop: false, keyboard: false});
        } else if (type == "tasks_last_actions") {
            $("#print_reports_actions_modal h4").text("PRINT TASKS WITH LATEST INSTRUCTIONS");
            $("#print_reports_actions_modal").modal({backdrop: false, keyboard: false});
        }

    }

    function trashManager(type) {
        var task_id = $('.booking_radio.active .booking_hidden_id').val();
        $("#delete_id").val(task_id);
        $("#delete_type").val(type);
        
        $("#delete_modal").modal({backdrop: false, keyboard: false});
        console.log('delete id: ' + task_id);
        console.log('delete_type: ' + type);
    }
    
    function restoreManager(type) {
        var task_id = $('.booking_radio.active .booking_hidden_id').val();
        $("#restore_id").val(task_id);
        $("#restore_type").val(type);
        
        $("#restore_modal").modal({backdrop: false, keyboard: false});
        console.log('restore id: ' + task_id);
        console.log('restore_type: ' + type);
    }

    $(document).ready(function () {
//every page load, update notifications 
        var notif_obj = <?php echo $this->session->notif_obj; ?>;
        updateNotifications(notif_obj, "full");

        var allow_date = '<?php echo $allow_date; ?>';
        //initialise datepicker
        var current_date = '<?php echo $today; ?>';
        var current_time = '<?php echo $now; ?>';
        if (allow_date === "Task") {//new task
            $('#task_date').jqxDateTimeInput({width: '20%', height: 25});
            $('#task_date').jqxDateTimeInput('setDate', current_date);

            $('#task_time').jqxDateTimeInput({formatString: "HH:mm:ss", showTimeButton: true, showCalendarButton: false, width: '20%', height: '25px'});
            $('#task_time').jqxDateTimeInput('setDate', current_time);

            var notification = "<?php echo $notification; ?>";
            if (notification) {//data from db
                var notification_date = "<?php echo date('d/m/Y', strtotime($notification)); ?>";
                console.log('notification_date: ' + notification_date);
                $('#task_date').jqxDateTimeInput({width: '20%', height: 25});
                $('#task_date').jqxDateTimeInput('setDate', notification_date);

                var notification_time = "<?php echo date('H:i:s a', strtotime($notification)); ?>";
                console.log('notification_time: ' + notification_time);
                $('#task_time').jqxDateTimeInput({formatString: "HH:mm:ss", showTimeButton: true, showCalendarButton: false, width: '20%', height: '25px'});
                $('#task_time').jqxDateTimeInput('setDate', notification_time);
            }

            var notification_date = "<?php echo $notification_date; ?>";
            var notification_time = "<?php echo $notification_time; ?>";
            if (notification_date) {//errors exist
                console.log('notification_date1: ' + notification_date);
                $('#task_date').jqxDateTimeInput({width: '20%', height: 25});
                $('#task_date').jqxDateTimeInput('setDate', notification_date);
            }
            if (notification_time) {//errors exist
                console.log('notification_time1: ' + notification_time);
                $('#task_time').jqxDateTimeInput({formatString: "HH:mm:ss", showTimeButton: true, showCalendarButton: false, width: '20%', height: '25px'});
                $('#task_time').jqxDateTimeInput('setDate', notification_time);
            }
        }

        if (allow_date === "Activity") {//new activity
            $('#activity_date').jqxDateTimeInput({width: '20%', height: 25});
            $('#activity_date').jqxDateTimeInput('setDate', current_date);

            $('#activity_time').jqxDateTimeInput({formatString: "HH:mm:ss", showTimeButton: true, showCalendarButton: false, width: '20%', height: '25px'});
            $('#activity_time').jqxDateTimeInput('setDate', current_time);

            var notification = "<?php echo $notification; ?>";
            if (notification) {//data from db
                var notification_date = "<?php echo date('d/m/Y', strtotime($notification)); ?>";
                console.log('notification_date: ' + notification_date);
                $('#activity_date').jqxDateTimeInput({width: '20%', height: 25});
                $('#activity_date').jqxDateTimeInput('setDate', notification_date);

                var notification_time = "<?php echo date('H:i:s a', strtotime($notification)); ?>";
                console.log('notification_time: ' + notification_time);
                $('#activity_time').jqxDateTimeInput({formatString: "HH:mm:ss", showTimeButton: true, showCalendarButton: false, width: '20%', height: '25px'});
                $('#activity_time').jqxDateTimeInput('setDate', notification_time);
            }

            var notification_date = "<?php echo $notification_date; ?>";
            var notification_time = "<?php echo $notification_time; ?>";
            if (notification_date) {//errors exist
                console.log('notification_date1: ' + notification_date);
                $('#activity_date').jqxDateTimeInput({width: '20%', height: 25});
                $('#activity_date').jqxDateTimeInput('setDate', notification_date);
            }
            if (notification_time) {//errors exist
                console.log('notification_time1: ' + notification_time);
                $('#activity_time').jqxDateTimeInput({formatString: "HH:mm:ss", showTimeButton: true, showCalendarButton: false, width: '20%', height: '25px'});
                $('#activity_time').jqxDateTimeInput('setDate', notification_time);
            }
        }

        if (allow_date === "Reminder") {//new reminder
            $('#reminder_date').jqxDateTimeInput({width: '20%', height: 25});
            $('#reminder_date').jqxDateTimeInput('setDate', current_date);

            $('#reminder_time').jqxDateTimeInput({formatString: "HH:mm:ss", showTimeButton: true, showCalendarButton: false, width: '20%', height: '25px'});
            $('#reminder_time').jqxDateTimeInput('setDate', current_time);

            var notification = "<?php echo $notification; ?>";
            if (notification) {//data from db
                var notification_date = "<?php echo date('d/m/Y', strtotime($notification)); ?>";
                console.log('notification_date: ' + notification_date);
                $('#reminder_date').jqxDateTimeInput({width: '20%', height: 25});
                $('#reminder_date').jqxDateTimeInput('setDate', notification_date);

                var notification_time = "<?php echo date('H:i:s a', strtotime($notification)); ?>";
                console.log('notification_time: ' + notification_time);
                $('#reminder_time').jqxDateTimeInput({formatString: "HH:mm:ss", showTimeButton: true, showCalendarButton: false, width: '20%', height: '25px'});
                $('#reminder_time').jqxDateTimeInput('setDate', notification_time);
            }

            var notification_date = "<?php echo $notification_date; ?>";
            var notification_time = "<?php echo $notification_time; ?>";
            if (notification_date) {//errors exist
                console.log('notification_date1: ' + notification_date);
                $('#reminder_date').jqxDateTimeInput({width: '20%', height: 25});
                $('#reminder_date').jqxDateTimeInput('setDate', notification_date);
            }
            if (notification_time) {//errors exist
                console.log('notification_time1: ' + notification_time);
                $('#reminder_time').jqxDateTimeInput({formatString: "HH:mm:ss", showTimeButton: true, showCalendarButton: false, width: '20%', height: '25px'});
                $('#reminder_time').jqxDateTimeInput('setDate', notification_time);
            }
        }
        
        if (allow_date === "Reports") {//new report
            $('#printreport_from').jqxDateTimeInput({width: '20%', height: 25});
            $('#printreport_from').jqxDateTimeInput('setDate', current_date);
            
            $('#printreport_to').jqxDateTimeInput({width: '20%', height: 25});
            $('#printreport_to').jqxDateTimeInput('setDate', current_date);
        }



        $('body').on('click', '.booking_radio', function () {//select or deselect a row
            console.log('a radio was clicked');
            var $this = $(this);
            $('.booking_radio').removeClass('active');
//            var allinput = $('.booking_radio').find('input[type="radio"]');
//            allinput.prop("checked", false);
//            var input = $(this).find('input[type="radio"]');
//            if (input.is(':checked')) {
//                input.prop("checked", false);
//                //$this.val('1');
//            } else {
//                input.prop("checked", true);
//                //$this.val('0');
//            }
            $this.addClass('active');
        });
                
        $('body').on('click', '.checkbox_radio', function () {
            //select or deselect a row
            console.log('a status checkbox was clicked');
            var $this = $(this).children("input");
            if ($this.is(':checked')) {
                $this.val('1');
                console.log('checkbox is 1');
            } else {
                $this.val('0');
                console.log('checkbox is 0');
            }
        });

        $('body').on('click', '.close_notif', function () {
            console.log('a close_notif was clicked');
            var $notif_id = $(this).parents("div.notif_buttons").children('input[class="notif_id"]').val();
            var $notif_type = $(this).parents("div.notif_buttons").children('input[class="notif_type"]').val();
            updateNotifAction($notif_type, $notif_id, 'close');
        });

        $('body').on('click', '.ignore_notif', function () {
            console.log('an ignore_notif was clicked');
            var $notif_id = $(this).parents("div.notif_buttons").children('input[class="notif_id"]').val();
            var $notif_type = $(this).parents("div.notif_buttons").children('input[class="notif_type"]').val();
            updateNotifAction($notif_type, $notif_id, 'ignore');
        });

        $('body').on('click', '.trash_notif', function () {
            console.log('a trash_notif was clicked');
            var $notif_id = $(this).parents("div.notif_buttons").children('input[class="notif_id"]').val();
            var $notif_type = $(this).parents("div.notif_buttons").children('input[class="notif_type"]').val();
            updateNotifAction($notif_type, $notif_id, 'trash');
        });
    });


</script>

</body>
</html>

