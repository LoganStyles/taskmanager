<?php
$access = $this->session->us_access;
?>
<!-- page heading start-->
<div class="page-heading">
    <h3>  Reports   </h3>
    <ul class="breadcrumb">
        <li>
            Task Manager
        </li>
        <li>
            Reports
        </li>
    </ul>
</div>
<!-- page heading end-->

<!--body wrapper start-->
<div class="wrapper"> 
    <div class="row">
        <div class="col-md-7">
            <span style="color: #65CEA7;">
                <?php
                if (isset($_SESSION['report_message'])) {
                    echo $this->session->report_message;
                }
                ?>
            </span>

            <hr>
            <?php
            $buttons = "<a onclick=\"printmanager('tasks_last_actions');\" class=\"btn btn-default  \" type=\"button\"><i class=\"fa fa-book\"></i>&nbsp;Tasks With Latest Actions</a>&nbsp;";
            echo $buttons;
            ?>
        </div>
    </div>

</div>
<!--body wrapper end-->

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="print_reports_actions_modal" class="modal fade">
    <div class="modal-dialog" style="width: 650px;">
        <div class="modal-content">
            <div class="modal-header panel-heading dark" >                
                <h4 class="modal-title">Print Dialog</h4>
            </div>
            <div class="modal-body">

                <?php
                $attributes = array('class' => 'cmxform form-horizontal adminex-form', 'id' => 'print_report_form');
                echo form_open_multipart('index.php/app/processReports/printreport', $attributes);
                ?>
                <div class="panel-body">
                    <div class="form">
                        <div class="col-md-7">
                            <div class="form-group ">
                                <label class="col-sm-3 control-label col-lg-3" for="printreport_sort">Sort By </label>
                                <div class="col-lg-9 col-sm-9">
                                    <select class="form-control " name="printreport_sort">
                                        <option value="act_asc">Action Ascending</option>
                                        <option value="act_desc">Action Descending</option>                                        
                                        <option value="sch_desc">Schedule Descending</option>
                                        <option value="sch_asc">Schedule Ascending</option> 
                                        <option value="serial_desc">Serial Number Descending</option>
                                        <option value="serial_asc">Serial Number Ascending</option>                                            
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="col-md-5">
                            <div class="form-group ">
                                <label class="col-sm-7 control-label col-lg-7">No. of Activities</label>
                                <div class="col-lg-5 col-sm-5">
                                    <select class="form-control " name="printreport_number">
                                        <option value="all">All</option>
                                        <option value="1">1</option>
                                        <option value="5">5</option>
                                        <option value="10">10</option>
                                        <option value="20">20</option> 
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>
                                </div>
                            </div>
                        </div>    
                        <div class="form-group">
                            <label class="col-sm-2 control-label col-lg-2" style="margin-top: 5%;font-weight: 700;">Status</label>
                            <div class="col-sm-9 icheck" style="margin-top: 5%;">
                                <div class="flat_row" style="margin-top: 6px;">                                            
                                    <div class="checkbox_radio">
                                        <input type="checkbox" name="printreport_status_open" value="">
                                        <label for="printreport_status_open" >Open</label>
                                    </div>
                                    <div class="checkbox_radio">
                                        <input type="checkbox" name="printreport_status_pending" value="">
                                        <label for="printreport_status_pending" >Pending</label>
                                    </div>
                                    <div class="checkbox_radio">
                                        <input type="checkbox" name="printreport_status_closed" value="">
                                        <label for="printreport_status_closed">Closed</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="form-group ">
                            <label for="printreport_from" class="col-sm-3 control-label">From</label>
                            <div class="col-sm-3" name="printreport_from" id="printreport_from">
                            </div>

                            <label for="printreport_to" class="col-sm-3 control-label">To</label>
                            <div class="col-sm-3" name="printreport_to" id="printreport_to">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <input class="btn btn-success btn-sm" type="submit" name="submit" value="GO" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>            
            </div>
        </div>

        </form> 
    </div>
</div>
</div>