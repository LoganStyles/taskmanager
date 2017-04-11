<?php
$access = $this->session->us_access;
if (!isset($this->session->us_username) || ($access < 1)) {
    $redirect = "index.php/app/";
    redirect($redirect);
}


$current = $received[0];
extract($current);

$count = 1;
$collapse = (isset($_SESSION['sent_filters']) && !isset($_SESSION['dashboard'])) ? ("in") : ("");

$filter_categories = "<option value=\"all\">All</option>";
if (count($taskcategory_titles) > 0) {
    $filter_content = "";
    foreach ($taskcategory_titles as $row) {
        $curr_ID = $row["ID"];
        $curr_title = ucwords($row["title"]);
        if ($curr_ID === $filter_category) {
            $selected = "selected";
        } else {
            $selected = "";
        }
        $filter_content.="<option value=\"$curr_ID\" $selected>$curr_title</option>";
    }
    $filter_categories .= $filter_content;
}

function getCurrentCategory($param, $categories) {
    $curr_title = "";
    foreach ($categories as $cats):
        $curr_id = $cats["ID"];
        $curr_title = $cats["title"];
        if ($curr_id == $param)
            return $curr_title;
    endforeach;
    return $curr_title;
}
?>

<!-- page heading start-->
<div class="page-heading">
    <h3> </h3>
    <ul class="breadcrumb">
        <li>
            Task Manager
        </li>
        <li>
            <a href="<?php echo base_url() . 'index.php/app/viewComponent/task/normal/0/'; ?>">All Tasks</a>            
        </li>

    </ul>
</div>
<!-- page heading end-->

<!--body wrapper start-->
<div class="wrapper">

    <div class="row">
        <div class="col-md-12">
            <div class="panel-group " id="accordion2">
                <div class="panel">
                    <div class="panel-heading dark">
                        <h4 class="panel-title">
                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne2">
                                Filters
                            </a>
                        </h4>
                    </div>
                    <div id="collapseOne2" class="panel-collapse collapse <?php echo $collapse; ?>">
                        <?php
                        if ($form_error) {
                            $danger_style = "alert alert-danger error";
                        } else {
                            $danger_style = "";
                        }
                        $attributes = array('class' => 'cmxform form-horizontal adminex-form', 'id' => 'filter_form');
                        echo '<div class="' . $danger_style . '">' . $form_error . '</div>';
                        echo form_open_multipart('index.php/app/processTaskFilter', $attributes);
                        ?>
                        <div class="panel-body">
                            <div class="row">
                                <div class="form">
                                    <div class="col-md-4">
                                        <div class="form-group ">
                                            <label for="filter_ref_id" class="col-sm-2 control-label">Search</label>
                                            <div class="col-sm-10">
                                                <input class=" form-control" id="filter_ref_id" placeholder="...Reference ID" value="<?php echo $filter_ref_id; ?>" name="filter_ref_id" type="text" />
                                            </div>
                                        </div>

                                        <div class="form-group ">
                                            <label class="col-sm-3 control-label col-lg-3" for="filter_sort">Sort By </label>
                                            <div class="col-lg-9 col-sm-9">
                                                <select class="form-control " name="filter_sort" >
                                                    <option value="serial_asc" <?php
                                                    if ($filter_sort === "serial_asc") {
                                                        echo 'selected';
                                                    }
                                                    ?>>Serial Number Ascending</option>
                                                    <option value="serial_desc" <?php
                                                    if ($filter_sort === "serial_desc") {
                                                        echo 'selected';
                                                    }
                                                    ?>>Serial Number Descending</option>
                                                    <option value="priority_asc" <?php
                                                    if ($filter_sort === "priority_asc") {
                                                        echo 'selected';
                                                    }
                                                    ?>>Priority Ascending</option>
                                                    <option value="priority_desc" <?php
                                                    if ($filter_sort === "priority_desc") {
                                                        echo 'selected';
                                                    }
                                                    ?>>Priority Descending</option>
                                                    <option value="sch_asc" <?php
                                                    if ($filter_sort === "sch_asc") {
                                                        echo 'selected';
                                                    }
                                                    ?>>Schedule Ascending</option>
                                                    <option value="sch_desc" <?php
                                                    if ($filter_sort === "sch_desc") {
                                                        echo 'selected';
                                                    }
                                                    ?>>Schedule Descending</option>                                                    
                                                    <option value="status_asc" <?php
                                                    if ($filter_sort === "status_asc") {
                                                        echo 'selected';
                                                    }
                                                    ?>>Status Ascending</option>
                                                    <option value="status_desc" <?php
                                                    if ($filter_sort === "status_desc") {
                                                        echo 'selected';
                                                    }
                                                    ?>>Status Descending</option>

                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group ">
                                            <label class="col-sm-2 control-label col-lg-2" for="filter_priority">Priority</label>
                                            <div class="col-lg-2 col-sm-2">
                                                <select class="form-control " name="filter_priority">
                                                    <option value="all" <?php
                                                    if ($filter_priority === "all") {
                                                        echo 'selected';
                                                    }
                                                    ?>>All</option>
                                                    <option value="1" <?php
                                                    if ($filter_priority === "1") {
                                                        echo 'selected';
                                                    }
                                                    ?>>1</option>
                                                    <option value="2" <?php
                                                    if ($filter_priority === "2") {
                                                        echo 'selected';
                                                    }
                                                    ?>>2</option>
                                                    <option value="3" <?php
                                                    if ($filter_priority === "3") {
                                                        echo 'selected';
                                                    }
                                                    ?>>3</option>
                                                    <option value="4" <?php
                                                    if ($filter_priority === "4") {
                                                        echo 'selected';
                                                    }
                                                    ?>>4</option>
                                                    <option value="5" <?php
                                                    if ($filter_priority === "5") {
                                                        echo 'selected';
                                                    }
                                                    ?>>5</option>                                                                                                        
                                                </select>
                                            </div>

                                            <label class="col-sm-3 control-label col-lg-3" for="filter_category">Category</label>
                                            <div class="col-lg-5 col-sm-5">
                                                <select class="form-control " name="filter_category">
                                                    <?php echo $filter_categories; ?>                                                                                                        
                                                </select>
                                            </div>
                                        </div>
                                        <div class="form-group ">
                                            <div class="form-group">
                                                <label class="col-sm-2 control-label col-lg-2" style="font-weight: 700;">Status</label>
                                                <div class="col-sm-9 icheck" style="">
                                                    <div class="flat_row" style="margin-top: 6px;">                                            
                                                        <div class="checkbox_radio">
                                                            <input type="checkbox" name="filter_status_open" value="<?php echo $filter_status_open; ?>" <?php if($filter_status_open=='1'){echo 'checked';} ?>>
                                                            <label for="filter_status_open" >Open</label>
                                                        </div>
                                                        <div class="checkbox_radio">
                                                            <input type="checkbox" name="filter_status_pending" value="<?php echo $filter_status_pending; ?>" <?php if($filter_status_pending=='1'){echo 'checked';} ?>>
                                                            <label for="filter_status_pending" >Pending</label>
                                                        </div>
                                                        <div class="checkbox_radio">
                                                            <input type="checkbox" name="filter_status_closed" value="<?php echo $filter_status_closed; ?>" <?php if($filter_status_closed=='1'){echo 'checked';} ?>>
                                                            <label for="filter_status_closed">Closed</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="panel-footer pull-right">
                            <input class="btn btn-success btn-sm" type="submit" name="submit" value="GO" />
                        </div>
                        </form>
                    </div>
                </div> 
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <section class="panel">
                <header class="panel-heading">
                    Tasks
                    <div>
                        <div class="pull-right">
                            <?php if (count($task) > 0) { ?>
                                <?php
                                $content = $status_span = $active_span = "";
                                foreach ($task as $row):
                                    $id = $row["ID"];
                                    $serial = $row["serial"];
                                    $ref_id = $row["ref_id"];
                                    $title = $row["title"];
                                    $active = $row["active"];
                                    $status = $row["status"];
                                    $priority = $row["priority"];
                                    $category = getCurrentCategory($row["category"], $taskcategory_titles);

                                    if ($status === "pending") {
                                        //scheduled date is in the future
                                        $status_span = "<span class=\"label label-warning label-mini status_span\">Pending</span>";
                                    } elseif ($status === "open") {
                                        //scheduled date is today
                                        $status_span = "<span class=\"label label-success label-mini status_span\">Open</span>";
                                    } elseif ($status === "overdue") {
                                        //scheduled date has past
                                        $status_span = "<span class=\"label label-danger label-mini status_span\">Overdue</span>";
                                    } elseif ($status === "closed") {
                                        $status_span = "<span class=\"label label-primary label-mini status_span\">Closed</span>";
                                    }

                                    if ($active === "0") {
                                        //inactive
                                        $active_span = "<span class=\"label label-default label-mini status_span\">Inactive</span>";
                                    } elseif ($active === "1") {
                                        //active
                                        $active_span = "<span class=\"label label-success label-mini status_span\">Active</span>";
                                    }

                                    if ($count == 1) {
                                        $active = "active";
                                        $checked = "checked";
                                    } else {
                                        $active = "";
                                        $checked = "";
                                    }

//                                    $date_created = date('F j, Y', strtotime($row["date_created"]));
                                    $notification = date('F j, Y, g:i a', strtotime($row["notification"]));

                                    $content.="<tr class=\"booking_radio $active\">";
                                    $content.="<td><input class=\"booking_hidden_id\" type=\"hidden\" value=\"$id\">"
                                            . "$serial</td>"; //                                  
                                    $content.="<td>$ref_id</td>";
                                    $content.="<td>$title</td>";
                                    $content.="<td>$category</td>";
                                    $content.="<td>$active_span</td>";
                                    $content.="<td>$notification</td>";
                                    $content.="<td>$priority</td>";
                                    $content.="<td>$status_span</td>";
                                    $content.="</tr>";

                                    $count++;
                                endforeach;
                                ?>
                            <?php } ?>
                            <div class="form-group ">
                                <div class="col-sm-12">
                                    <?php
                                    $buttons = "<a href=\"" . base_url() . "index.php/app/showTask/0/" . $type . "/form_" . "\"class=\"btn btn-default  \" type=\"button\"><i class=\"fa fa-plus\"></i>&nbsp;New</a>&nbsp;"; //                    
                                    if ($count > 1) {
                                        $buttons.="<a onclick=\"taskmanager('view_');\" type=\"button\" class=\"btn btn-primary \"><i class=\"fa fa-eye\"></i>&nbsp;View</a>&nbsp;";
                                        $buttons.="<a onclick=\"taskmanager('form_');\" type=\"button\" class=\"btn btn-success \"><i class=\"fa fa-edit\"></i>&nbsp;Edit</a>&nbsp;";
                                        $buttons.="<a onclick=\"printmanager('tasks');\" type=\"button\" class=\"btn btn-info \"><i class=\"fa fa-print\"></i>&nbsp;Print Activities</a>&nbsp;";
                                    }
                                    echo $buttons;
                                    ?>
                                </div>
                            </div>


                        </div>
                        <div class="clearfix"></div>

                    </div>
                </header>


                <div class="panel-body"><p style="font-weight: 700;color: #f00;">
                        <?php
                        if (isset($request_response)) {
                            echo $request_response;
                        }
                        ?>
                    </p>
                    <table class="table  table-hover general-table table-bordered table-condensed">
                        <thead>
                            <tr>                                
                                <th>S/N</th>
                                <th>Reference Id</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Active/Inactive</th>
                                <th>Scheduled For</th>
                                <th>Priority</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (count($task) > 0) { ?>
                                <?php echo $content; ?>
                            <?php } ?>
                        </tbody>
                    </table>

                    <?php
                    if (strlen($pagination)) {
                        echo $pagination;
                    }
                    ?>

                </div>

        </div>
        </section>
    </div>
</div>

<!--body wrapper end-->

<div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="print_preview_modal" class="modal fade">
    <div class="modal-dialog" style="width: 600px;">
        <div class="modal-content">
            <div class="modal-header panel-heading dark" >                
                <h4 class="modal-title">Print Dialog</h4>
            </div>
            <div class="modal-body">

                <?php
                $attributes = array('class' => 'cmxform form-horizontal adminex-form', 'id' => 'print_filter_form');

                echo form_open_multipart('index.php/app/processPrintTaskFilter', $attributes);
                ?>
                <div class="panel-body">
                    <div class="form">
                        <div class="row">                        
                            <div class="col-md-7">
                                <div class="form-group ">
                                    <input type="hidden" value="" name="printfilter_task_id" id="printfilter_task_id">
                                    <label class="col-sm-3 control-label col-lg-3" for="printfilter_sort">Sort By </label>
                                    <div class="col-lg-9 col-sm-9">
                                        <select class="form-control " name="printfilter_sort">
                                            <option value="serial_asc" <?php
                                            if ($printfilter_sort === "serial_asc") {
                                                echo 'selected';
                                            }
                                            ?>>Serial Number Ascending</option>
                                            <option value="serial_desc" <?php
                                            if ($printfilter_sort === "serial_desc") {
                                                echo 'selected';
                                            }
                                            ?>>Serial Number Descending</option>

                                            <option value="sch_asc" <?php
                                            if ($printfilter_sort === "sch_asc") {
                                                echo 'selected';
                                            }
                                            ?>>Schedule Ascending</option>
                                            <option value="sch_desc" <?php
                                            if ($printfilter_sort === "sch_desc") {
                                                echo 'selected';
                                            }
                                            ?>>Schedule Descending</option>                                                    
                                            <option value="action_asc" <?php
                                            if ($printfilter_sort === "action_asc") {
                                                echo 'selected';
                                            }
                                            ?>>Action Ascending</option>
                                            <option value="action_desc" <?php
                                            if ($printfilter_sort === "action_desc") {
                                                echo 'selected';
                                            }
                                            ?>>Action Descending</option>

                                        </select>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-5"> 
                                <div class="form-group ">
                                    <label class="col-sm-7 control-label col-lg-7">No. of Activities</label>
                                    <div class="col-lg-5 col-sm-5">
                                        <select class="form-control " name="printfilter_number">
                                            <option value="all" <?php
                                            if ($printfilter_number == "all") {
                                                echo 'selected';
                                            }
                                            ?>>All</option>
                                            <option value="1" <?php
                                            if ($printfilter_number === "1") {
                                                echo 'selected';
                                            }
                                            ?>>1</option>
                                            <option value="5" <?php
                                            if ($printfilter_number === "5") {
                                                echo 'selected';
                                            }
                                            ?>>5</option>
                                            <option value="10" <?php
                                            if ($printfilter_number === "10") {
                                                echo 'selected';
                                            }
                                            ?>>10</option>
                                            <option value="20" <?php
                                            if ($printfilter_number === "20") {
                                                echo 'selected';
                                            }
                                            ?>>20</option> 
                                            <option value="50" <?php
                                            if ($printfilter_number === "50") {
                                                echo 'selected';
                                            }
                                            ?>>50</option>
                                            <option value="100" <?php
                                            if ($printfilter_number === "100") {
                                                echo 'selected';
                                            }
                                            ?>>100</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group">
                                <label class="col-sm-2 control-label col-lg-2" style="margin-top: 5%;font-weight: 700;">Status</label>
                                <div class="col-sm-9 icheck" style="margin-top: 5%;">
                                    <div class="flat_row" style="margin-top: 6px;">                                            
                                        <div class="checkbox_radio">
                                            <input type="checkbox" name="printfilter_status_open" value="">
                                            <label for="printfilter_status_open" >Open</label>
                                        </div>
                                        <div class="checkbox_radio">
                                            <input type="checkbox" name="printfilter_status_pending" value="">
                                            <label for="printfilter_status_pending" >Pending</label>
                                        </div>
                                        <div class="checkbox_radio">
                                            <input type="checkbox" name="printfilter_status_closed" value="">
                                            <label for="printfilter_status_closed">Closed</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <input class="btn btn-success btn-sm" type="submit" name="submit" value="GO" />
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </form> 
            </div>
        </div>
    </div>
</div>

