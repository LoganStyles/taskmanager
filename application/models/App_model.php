<?php

include_once("passHash.php");

class App_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function getLoginItems($type, $login_signature, $login_password, $login_remember) {
        /* create sessions for authenticated users 
         * update user login data
         */
        $tableitems = strtolower($type) . "items";

        $q = "SELECT ID,title,signature,access,hashed_p FROM $tableitems "
                . "WHERE signature='$login_signature' LIMIT 1";

        $query = $this->db->query($q);

        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            if (isset($result)) {
                $curr_id = $result["ID"];
                $curr_hashed_p = $result["hashed_p"];
                $pass_valid = validate_password($login_password, $curr_hashed_p);

                if ($pass_valid) {
                    if ($login_remember) {
                        //create cookie
                        $this->createCookie($type, $login_signature, $curr_id);
                    }
                    //session data
                    $this->session->us_username = $result["signature"];
                    $this->session->us_name = $result["title"];
                    $this->session->us_access = $result["access"];
                    $this->session->us_id = $result["ID"];

                    //UPDATE USER'S IP AND LAST LOGIN
                    $curr_ip_add = $_SERVER['REMOTE_ADDR'];
                    $curr_time = date("Y-m-d H:i:s");
                    $data = array(
                        'last_login_ip' => $curr_ip_add,
                        'last_login_time' => $curr_time
                    );
                    $this->db->where('ID', $curr_id);
                    $this->db->update($tableitems, $data);
                    return true;
                } else {
                    $this->session->set_flashdata('error_message', 'Invalid Username/Password');
                    return false;
                }
            } else {
                $this->session->set_flashdata('error_message', 'Login Failed');
                return false;
            }
        } else {
            $this->session->set_flashdata('error_message', 'Login Failed');
            return false;
        }
    }

    private function createCookie($type, $login_signature, $curr_id) {
        $tableitems = strtolower($type) . "items";
        //set cookie data
        $cookie_uname = array(//username
            'name' => 'existing_uname',
            'value' => $login_signature,
            'expire' => '999999999',
        );

        $serial = mt_rand(1, 100);
        $token = mt_rand(1000, 999999999);
        $hashed_token = create_hash($token);

        $cookie_userial = array(//serial
            'name' => 'existing_userial',
            'value' => $serial,
            'expire' => '999999999',
        );

        $cookie_utoken = array(//token
            'name' => 'existing_utoken',
            'value' => $token,
            'expire' => '999999999',
        );
        set_cookie($cookie_uname);
        set_cookie($cookie_userial);
        set_cookie($cookie_utoken);
        //update db
        $data = array(
            'serial' => $serial,
            'token' => $hashed_token
        );
        $this->db->where('ID', $curr_id);
        $this->db->update($tableitems, $data);
    }

    public function updateLoginItems($type, $serial, $token) {
        /* create sessions for authenticated users 
         * update user login data
         */
        $tableitems = strtolower($type) . "items";
        $q = "SELECT ID,title,signature,access,token FROM $tableitems "
                . "WHERE serial='$serial' LIMIT 1";

        $query = $this->db->query($q);

        if ($query->num_rows() > 0) {
            $result = $query->row_array();
            if (isset($result)) {
                $curr_id = $result["ID"];
                $curr_token = $result["token"];
                $curr_signature = $result["signature"];
                $token_valid = validate_password($token, $curr_token);

                if ($token_valid) {
                    //create cookie
                    $this->createCookie($type, $curr_signature, $curr_id);

                    $this->session->us_username = $result["signature"];
                    $this->session->us_name = $result["title"];
                    $this->session->us_access = $result["access"];
                    $this->session->us_id = $result["ID"];

                    //UPDATE USER'S IP AND LAST LOGIN
                    $curr_ip_add = $_SERVER['REMOTE_ADDR'];
                    $curr_time = date("Y-m-d H:i:s");
                    $data = array(
                        'last_login_ip' => $curr_ip_add,
                        'last_login_time' => $curr_time
                    );
                    $this->db->where('ID', $curr_id);
                    $this->db->update($tableitems, $data);
                    return true;
                } else {
                    $this->session->set_flashdata('error_message', 'Invalid Login');
                    return false;
                }
            } else {
                $this->session->set_flashdata('error_message', 'Login Failed');
                return false;
            }
        } else {
            $this->session->set_flashdata('error_message', 'Login Failed');
            return false;
        }
    }

    public function getTitles($type) {
        /* gets the titles of the sections */
        $tableitems = strtolower($type) . "items";
        $results = array();

        $q = "SELECT * FROM $tableitems order by position,ID";
        $query = $this->db->query($q);
        if ($query->num_rows() > 0)
            $results = $query->result_array();

        return $results;
    }

    public function getTotalComponents($type) {
        $table = $type . "items";
        $this->db->from($table);
        return $this->db->count_all_results();
    }

    public function getTotalTodayDisplayedComponents($type) {
        $table = $type . "items";
        $q = "SELECT * FROM $table WHERE display='1' and status <>'closed' and active='1'"
                . " and DATE(notification)=CURDATE()";
        $count = 0;
        $query = $this->db->query($q);
        if ($query->num_rows() > 0) {
            $count = $query->num_rows();
        }
        return $count;
    }

    public function getTotalOverdueDisplayedComponents($type) {
        $table = $type . "items";
        $q = "SELECT * FROM $table WHERE display='1' and status ='open' and active='1'"
                . " and DATE(notification) < CURDATE()";
        $count = 0;
        $query = $this->db->query($q);
        if ($query->num_rows() > 0) {
            $count = $query->num_rows();
        }
        return $count;
    }

    public function getTotalPendingDisplayedComponents($type) {
        $table = $type . "items";
        $q = "SELECT * FROM $table WHERE display='1' and status ='pending' and active='1'"
                . " and DATE(notification) <= CURDATE()";
        $count = 0;
        $query = $this->db->query($q);
        if ($query->num_rows() > 0) {
            $count = $query->num_rows();
        }
        return $count;
    }

    public function getTotalDisplayedActivities($type, $ID) {
        /* gets required fields for the sections */
        $tableitems = strtolower($type) . "items";
        $count = 0;

        $q = "SELECT * from $tableitems where taskid='$ID' and display='1'";

//        echo $q;
//            exit;
        $query = $this->db->query($q);
        if ($query->num_rows() > 0) {
            $count = $query->num_rows();
        }
        return $count;
    }

    public function getItems($type, $ID = FALSE) {
        /* gets required fields for the sections */
        $tableitems = strtolower($type) . "items";
        $tableimages = strtolower($type) . "images";
        $results = array();

        if ($ID === FALSE) {
            $q = "SELECT items.*,images.filename,images.ID as imageid,images.alt,images.caption,"
                    . "images.main from $tableitems as items "
                    . "left join $tableimages as images on (items.ID=images.itemid) "
                    . "order by items.position, items.ID ";
        } else {
            $q = "SELECT items.*,images.filename,images.ID as imageid,images.alt,images.caption,"
                    . "images.main from $tableitems as items "
                    . "left join $tableimages as images on (items.ID=images.itemid) "
                    . " where items.ID='$ID' "
                    . "order by items.position, items.ID ";
        }
//        echo $q;exit;
        $query = $this->db->query($q);
        if ($query->num_rows() > 0)
            $results = $query->result_array();

        return $results;
    }

    public function getActivityItems($type, $ID) {
        /* gets required fields for the sections */
        $tableitems = strtolower($type) . "items";
        $results = array();

        $q = "SELECT items.*,t.ref_id from $tableitems as items "
                . "left join taskitems as t on (items.taskid=t.ID) "
                . " where items.ID='$ID' "
                . "order by items.position, items.ID ";
//        echo $q;exit;
        $query = $this->db->query($q);
        if ($query->num_rows() > 0)
            $results = $query->result_array();

        return $results;
    }

    public function updateItem($type, $ID, $action) {
        /* update any of task/activity/reminder */
        $tableitems = strtolower($type) . "items";
        $now = date("Y-m-d H:i:s");
        switch ($action) {
            case 'close':
                $data = array(
                    'active' => '0',
                    'status' => 'closed',
                    'date_closed' => $now
                );
                break;
            case 'ignore':
                $data = array(
                    'active' => '0',
                    'date_modified' => $now
                );
                break;
            case 'trash':
                $data = array(
                    'display' => '0',
                    'date_modified' => $now
                );
                break;
        }

        $this->db->where('ID', $ID);
        if ($this->db->update($tableitems, $data)) {
            return true;
        } else {
            return false;
        }
    }

    public function getReportItems($limit_val = FALSE, $sort_val = FALSE, $filter_val = FALSE) {
        /* gets displayed rows for these sections */
        $limit = $sort = "";
        $results = array();

        if ($limit_val) {
            $limit = "LIMIT $limit_val";
        }

        if ($sort_val) {
            $sort = $sort_val;
        }

        if ($filter_val) {
            $filter = $filter_val;
        }

        $date_temp = $this->input->post("printreport_from");
        $temp_date = str_replace('/', '-', $date_temp);
        $report_from = date('Y-m-d', strtotime($temp_date));

        $date_temp = $this->input->post("printreport_to");
        $temp_date = str_replace('/', '-', $date_temp);
        $report_to = date('Y-m-d', strtotime($temp_date));
        $report_date = "and items.notification between '" . $report_from . "' and '" . $report_to . "'";

        $q = "SELECT items.*,t.title as task_title from "
                . "(SELECT * from activityitems $sort) as items "
                . "left join taskitems as t on (items.taskid=t.ID) "
                . "where items.display='1' $filter $report_date group by items.taskid $sort $limit";
//        echo $q;exit;

        $query = $this->db->query($q);
        if ($query->num_rows() > 0) {
            $results = $query->result_array();
        }
        return $results;
    }

    public function getDisplayedItems($type, $ID = FALSE, $offset = FALSE, $limit_val = FALSE, $filter_val = FALSE) {
        /* gets displayed rows for these sections */
        $tableitems = strtolower($type) . "items";
        $limit = $filter = "";
        $sort = "order by position, ID desc";
        $results['data'] = array();
        $results['count'] = 0;

        if ($limit_val) {
            $limit = "LIMIT $offset,$limit_val";
        }


        if ($filter_val) {
            $sort = $filter_val;
        }

        if ($ID == FALSE) {
            $q = "SELECT * from $tableitems where display='1' $sort $limit";
            $q_total = "SELECT * from $tableitems where display='1' $sort ";
        } else {
            $q = "SELECT * from $tableitems where display='1' and ID='$ID' $sort $limit ";
            $q_total = "SELECT * from $tableitems where display='1' and ID='$ID' $sort";
        }
//                    echo $q;echo '<br>';
//                    echo $q_total;
//                    exit;
        $query = $this->db->query($q);
        if ($query->num_rows() > 0) {
            $results['data'] = $query->result_array();
        }

        $query = $this->db->query($q_total);
        if ($query->num_rows() > 0) {
            $results['count'] = $query->num_rows();
        }
        return $results;
    }

    public function getDeletedItems($type, $ID = FALSE, $offset = FALSE, $limit_val = FALSE, $filter_val = FALSE) {
        /* gets displayed rows for these sections */
        $tableitems = strtolower($type) . "items";
        $limit = $filter = "";
        $sort = "order by position, ID desc";
        $results['data'] = array();
        $results['count'] = 0;

        if ($limit_val) {
            $limit = "LIMIT $offset,$limit_val";
        }


        if ($filter_val) {
            $sort = $filter_val;
        }

        if ($ID == FALSE) {
            $q = "SELECT * from $tableitems where display='0' $sort $limit";
            $q_total = "SELECT * from $tableitems where display='0' $sort ";
        } else {
            $q = "SELECT * from $tableitems where display='0' and ID='$ID' $sort $limit ";
            $q_total = "SELECT * from $tableitems where display='0' and ID='$ID' $sort";
        }
//                    echo $q;echo '<br>';
//                    echo $q_total;
//                    exit;
        $query = $this->db->query($q);
        if ($query->num_rows() > 0) {
            $results['data'] = $query->result_array();
        }

        $query = $this->db->query($q_total);
        if ($query->num_rows() > 0) {
            $results['count'] = $query->num_rows();
        }
        return $results;
    }

    public function getDisplayedActivities($type, $ID = FALSE, $offset = FALSE, $limitval = FALSE, $sortval = FALSE) {
        /* gets required fields for the sections */
        $tableitems = strtolower($type) . "items";
        $limit = $sort = "";

        if ($sortval) {
            $sort = $sortval;
        }

        if ($limitval) {
            $limit = "LIMIT $offset,$limitval";
        }

        if (!$ID) {
            $q = "SELECT * from $tableitems where display='1' $sort $limit";
        } else {
            $q = "SELECT * from $tableitems where display='1' "
                    . "and taskid='$ID' $sort $limit";
        }
//        echo $q;
//            exit;
        $query = $this->db->query($q);
        return $query->result_array();
    }

    public function getTodayDisplayedItems($type, $offset = FALSE, $limit = FALSE) {
        /* gets displayed items for today */
        $tableitems = strtolower($type) . "items";
        $results = array();

        if ($limit) {
            $limit = "LIMIT $offset,$limit";
        } else {
            $limit = "";
        }

        $q = "SELECT * from $tableitems where display='1' "
                . "and status <>'closed' and active='1' and DATE(notification)=CURDATE() "
                . "order by position,ID desc $limit";
        $query = $this->db->query($q);
        if ($query->num_rows() > 0)
            $results = $query->result_array();

        return $results;
    }

    public function deleteTrashItem() {
        $this->load->helper('url');

        $item_id = "delete_id";
        $item_type = "delete_type";

        $id = $this->input->post($item_id);
        $type = strtolower($this->input->post($item_type));

        $tableitems = $type . "items";
        $this->db->where('ID', $id);
        $this->db->delete($tableitems);

        return true;
    }

    public function restoreTrashItem() {
        $this->load->helper('url');

        $item_id = "restore_id";
        $item_type = "restore_type";

        $id = $this->input->post($item_id);
        $type = strtolower($this->input->post($item_type));

        $tableitems = $type . "items";
        $data = array('display' => '1');

        $this->db->where('ID', $id);
        $this->db->update($tableitems, $data);

        return true;
    }

    public function deleteItem($id, $type) {
        /* deletes a section item's data */
        //select itemid of this item

        $table = strtolower($type) . "items";
        $image_table = strtolower($type) . "images";

        $q_del = "SELECT filename,ID as imageid from $image_table "
                . "where itemid='$id'";

        $query = $this->db->query($q_del);
        $results = $query->result_array();
        foreach ($results as $row):
            $imageid = $row["imageid"];
            $filename = $row["filename"];
            $res_im = $this->db->delete($image_table, array('ID' => $imageid));
            if ($res_im) {
                $file = FCPATH . "images/UPLOADS/" . $filename;
                if (is_readable($file)) {
                    unlink($file);
                }
            }
        endforeach;

        $res = $this->db->delete($table, array('ID' => $id)); //delete item

        $firstid = 0;

        if ($res) {
            $q_first = "SELECT ID from $table limit 1";
            $query = $this->db->query($q_first);
            $results = $query->result_array();
            foreach ($results as $row):
                $firstid = $row["ID"];
            endforeach;
        }
        return $firstid;
    }

    public function updateUsers($type, $image_present) {
        /* performs insert/update of items */
        $this->load->helper('url');

        $type = strtolower($type);
        $tableitems = $type . "items";
        $tableimages = $type . "images";


        $item_title = $type . "_title";
        $item_signature = $type . "_signature";
        $item_id = $type . "_id";
        $item_access = $type . "_access";
        $item_hashed_p = $type . "_hashed_p";
        $item_position = $type . "_position";
        $item_display = $type . "_display";

        $ID = $this->input->post($item_id);
        //$itemid = $this->input->post($item_itemid);
        $title = $this->input->post($item_title);
        $signature = $this->input->post($item_signature);
        $hashed_p = $this->input->post($item_hashed_p);
        $access = $this->input->post($item_access);
        $position = $this->input->post($item_position);
        $display = $this->input->post($item_display);

        if ($image_present) {
            $image_filename = $this->upload->data('file_name');
        } else {
            $image_filename = "";
        }

        $hashed_pass = create_hash($hashed_p);

        if ($ID > 0) {
            //update
            $data = array(
                'title' => $title,
                'signature' => $signature,
                'hashed_p' => $hashed_pass,
                'access' => $access,
                'position' => $position,
                'display' => $display,
                'date_modified' => date("Y-m-d H:i:s")
            );
            $this->db->where('ID', $ID);
            $this->db->update($tableitems, $data);

            if ($image_filename != "") {
                $image_data_temp = array(
                    'filename' => $image_filename,
                    'alt' => $caption,
                    'date_created' => date("Y-m-d H:i:s"),
                    'caption' => $caption,
                    'itemid' => $ID,
                    'main' => $main,
                    'type' => $type
                );
                $this->db->insert($tableimages, $image_data_temp);
                $itemid = $this->db->insert_id();
            }
            return $ID;
        } elseif ($ID == 0) {
            //insert
            $data = array(
                'title' => $title,
                'signature' => $signature,
                'hashed_p' => $hashed_pass,
                'access' => $access,
                'position' => $position,
                'display' => $display,
                'type' => $type,
                'date_created' => date("Y-m-d H:i:s")
            );

            $this->db->insert($tableitems, $data);
            $insert_id = $this->db->insert_id();

            if ($image_filename != "") {
                $image_data_temp = array(
                    'filename' => $image_filename,
                    'alt' => $caption,
                    'date_created' => date("Y-m-d H:i:s"),
                    'caption' => $caption,
                    'itemid' => $insert_id,
                    'main' => $main,
                    'type' => $type
                );
                $this->db->insert($tableimages, $image_data_temp);
                $itemid = $this->db->insert_id();
            }
            return $insert_id;
        } else {
            return false;
        }
    }

    public function updateOptions($type, $image_present) {
        /* performs insert/update of items */
        $this->load->helper('url');

        $type = strtolower($type);
        $tableitems = $type . "items";
        $tableimages = $type . "images";


        $item_id = $type . "_id";
        $item_title = $type . "_title";
        $item_description = $type . "_description";
        $item_position = $type . "_position";
        $item_display = $type . "_display";
        $item_main = $type . "_main";
        $item_caption = $type . "_caption";

        $ID = $this->input->post($item_id);
        $title = $this->input->post($item_title);
        $position = $this->input->post($item_position);
        $description = $this->input->post($item_description);
        $display = $this->input->post($item_display);
        $main = $this->input->post($item_main);
        $caption = $this->input->post($item_caption);
        $linklabel = url_title($title, "-", true);

        if ($image_present) {
            $image_filename = $this->upload->data('file_name');
        } else {
            $image_filename = "";
        }

        if ($ID > 0) {
            //update
            $data = array(
                'title' => $title,
                'position' => $position,
                'description' => $description,
                'display' => $display,
                'linklabel' => $linklabel,
                'date_modified' => date("Y-m-d H:i:s")
            );

            $this->db->where('ID', $ID);
            $this->db->update($tableitems, $data);

            if ($image_filename != "") {
                $image_data_temp = array(
                    'filename' => $image_filename,
                    'alt' => $caption,
                    'date_created' => date("Y-m-d H:i:s"),
                    'caption' => $caption,
                    'itemid' => $ID,
                    'main' => $main,
                    'type' => $type
                );
                $this->db->insert($tableimages, $image_data_temp);
                $itemid = $this->db->insert_id();
            }
            return $ID;
        } elseif ($ID == 0) {
            //insert
            $data = array(
                'title' => $title,
                'position' => $position,
                'description' => $description,
                'display' => $display,
                'linklabel' => $linklabel,
                'type' => $type,
                'date_created' => date("Y-m-d H:i:s")
            );

            $this->db->insert($tableitems, $data);
            $insert_id = $this->db->insert_id();

            //$itemid = 0;
            if ($image_filename != "") {
                $image_data_temp = array('filename' => $image_filename,
                    'alt' => $caption,
                    'date_created' => date("Y-m-d H:i:s"),
                    'caption' => $caption,
                    'itemid' => $insert_id,
                    'main' => $main,
                    'type' => $type
                );
                $this->db->insert($tableimages, $image_data_temp);
                $itemid = $this->db->insert_id();
            }

            return $insert_id;
        } else {
            return false;
        }
    }

    private function updateTaskStatus($type, $ID, $schedule) {
        /* if sch_time is < $now, update task status to pending */
        $tableitems = $type . "items";

        $curr_time = date('Y-m-d H:i:s');
        if (($schedule < $curr_time)) {
            //update status
            $data = array(
                'status' => 'pending',
                'date_modified' => $curr_time);
            $this->db->where('ID', $ID);
            $this->db->update($tableitems, $data);
            return true;
        } else {
            return false;
        }
    }

    public function updateTask($type, $image_present) {
        /* performs insert/update of items */
        $this->load->helper('url');

        $type = strtolower($type);
        $tableitems = $type . "items";
        $tableimages = $type . "images";

        $item_id = $type . "_id";
        $item_ref_id = $type . "_ref_id";
        $item_type = $type . "_type";
        $item_title = $type . "_title";
        $item_category = $type . "_category";
        $item_description = $type . "_description";
        $item_active = $type . "_active";
        $item_priority = $type . "_priority";
        $item_date = $type . "_date";
        $item_time = $type . "_time";

        $ID = $this->input->post($item_id);
        $ref_id = $this->input->post($item_ref_id);
        $title = $this->input->post($item_title);
        $description = $this->input->post($item_description);
        $category = $this->input->post($item_category);
        $active = $this->input->post($item_active);
        $priority = $this->input->post($item_priority);

        $position = 1;
        $display = "1";
        $linklabel = url_title($title, "-", true);

        $date_temp = $this->input->post($item_date);
        $temp_date = str_replace('/', '-', $date_temp);
        $scheduled_date = date('Y-m-d', strtotime($temp_date));

        $time_temp = $this->input->post($item_time);
        $scheduled_time = date('H:i:s', strtotime($time_temp));
        $sch_time = $scheduled_date . " " . $scheduled_time;

        if ($image_present) {
            $image_filename = $this->upload->data('file_name');
        } else {
            $image_filename = "";
        }

        if ($ID > 0) {
            //update tasks
            $data = array(
                'title' => $title,
                'notification' => $sch_time,
                'description' => $description,
                'active' => $active,
                'priority' => $priority,
                'ref_id' => $ref_id,
                'category' => $category,
                'linklabel' => $linklabel,
                'date_modified' => date("Y-m-d H:i:s")
            );

            $this->db->where('ID', $ID);
            $this->db->update($tableitems, $data);

            if ($image_filename != "") {
                $image_data_temp = array(
                    'filename' => $image_filename,
                    'alt' => $caption,
                    'date_created' => date("Y-m-d H:i:s"),
                    'caption' => $caption,
                    'itemid' => $ID,
                    'main' => $main,
                    'type' => $type
                );
                $this->db->insert($tableimages, $image_data_temp);
                $itemid = $this->db->insert_id();
            }
            //update task status if necessary
            $update_task_res = $this->updateTaskStatus($type, $ID, $sch_time);

            return $ID;
        } elseif ($ID == 0) {
            //insert
            $status = "open";
            //get last serial
            $last_serial = 0;
            $q_serial = "SELECT serial from $tableitems order by ID DESC LIMIT 1";
            $query = $this->db->query($q_serial);
            if ($query->num_rows() > 0) {
                $results = $query->row_array();
                if (isset($results)) {
                    $last_serial = intval($results["serial"]);
                }
            }

            $serial_created = 0;
            do {
                //create serial no
                ++$last_serial;
                $q_serial = "SELECT ID from $tableitems where serial='$last_serial'";

                $query = $this->db->query($q_serial);
                $count = $query->num_rows();
                if ($count <= 0) {
                    $serial_created = 1;
                }
            } while ($serial_created == 0);

            $data = array(
                'ref_id' => $ref_id,
                'title' => $title,
                'serial' => $last_serial,
                'notification' => $sch_time,
                'status' => $status,
                'category' => $category,
                'position' => $position,
                'active' => $active,
                'priority' => $priority,
                'description' => $description,
                'display' => $display,
                'linklabel' => $linklabel,
                'type' => $type,
                'date_created' => date("Y-m-d H:i:s")
            );

            $this->db->insert($tableitems, $data);
            $insert_id = $this->db->insert_id();

            //$itemid = 0;
            if ($image_filename != "") {
                $image_data_temp = array('filename' => $image_filename,
                    'alt' => $caption,
                    'date_created' => date("Y-m-d H:i:s"),
                    'caption' => $caption,
                    'itemid' => $insert_id,
                    'main' => $main,
                    'type' => $type
                );
                $this->db->insert($tableimages, $image_data_temp);
                $itemid = $this->db->insert_id();
            }

            return $insert_id;
        } else {
            return false;
        }
    }

    public function updateReminder($type, $image_present) {
        /* performs insert/update of items */
        $this->load->helper('url');

        $type = strtolower($type);
        $tableitems = $type . "items";
        $tableimages = $type . "images";

        $item_id = $type . "_id";
        $item_type = $type . "_type";
        $item_title = $type . "_title";
        $item_description = $type . "_description";
        $item_active = $type . "_active";
        $item_date = $type . "_date";
        $item_time = $type . "_time";

        $ID = $this->input->post($item_id);
        $title = $this->input->post($item_title);
        $description = $this->input->post($item_description);
        $active = $this->input->post($item_active);

        $position = 1;
        $display = "1";
        $linklabel = url_title($title, "-", true);

        $date_temp = $this->input->post($item_date);
        $temp_date = str_replace('/', '-', $date_temp);
        $scheduled_date = date('Y-m-d', strtotime($temp_date));

        $time_temp = $this->input->post($item_time);
        $scheduled_time = date('H:i:s', strtotime($time_temp));
        $sch_time = $scheduled_date . " " . $scheduled_time;

        if ($image_present) {
            $image_filename = $this->upload->data('file_name');
        } else {
            $image_filename = "";
        }

        if ($ID > 0) {
            //update
            $data = array(
                'title' => $title,
                'notification' => $sch_time,
                'description' => $description,
                'active' => $active,
                'linklabel' => $linklabel,
                'date_modified' => date("Y-m-d H:i:s")
            );

            $this->db->where('ID', $ID);
            $this->db->update($tableitems, $data);

            if ($image_filename != "") {
                $image_data_temp = array(
                    'filename' => $image_filename,
                    'alt' => $caption,
                    'date_created' => date("Y-m-d H:i:s"),
                    'caption' => $caption,
                    'itemid' => $ID,
                    'main' => $main,
                    'type' => $type
                );
                $this->db->insert($tableimages, $image_data_temp);
                $itemid = $this->db->insert_id();
            }
            return $ID;
        } elseif ($ID == 0) {
            //insert
            $status = "open";
            //get last serial
            $last_serial = 0;
            $q_serial = "SELECT serial from $tableitems order by ID DESC LIMIT 1";
            $query = $this->db->query($q_serial);
            if ($query->num_rows() > 0) {
                $results = $query->row_array();
                if (isset($results)) {
                    $last_serial = intval($results["serial"]);
                }
            }

            $serial_created = 0;
            do {
                //create serial no
                ++$last_serial;
                $q_serial = "SELECT ID from $tableitems where serial='$last_serial'";

                $query = $this->db->query($q_serial);
                $count = $query->num_rows();
                if ($count <= 0) {
                    $serial_created = 1;
                }
            } while ($serial_created == 0);

            $data = array(
                'title' => $title,
                'serial' => $last_serial,
                'notification' => $sch_time,
                'status' => $status,
                'position' => $position,
                'active' => $active,
                'description' => $description,
                'display' => $display,
                'linklabel' => $linklabel,
                'type' => $type,
                'date_created' => date("Y-m-d H:i:s")
            );

            $this->db->insert($tableitems, $data);
            $insert_id = $this->db->insert_id();

            //$itemid = 0;
            if ($image_filename != "") {
                $image_data_temp = array('filename' => $image_filename,
                    'alt' => $caption,
                    'date_created' => date("Y-m-d H:i:s"),
                    'caption' => $caption,
                    'itemid' => $insert_id,
                    'main' => $main,
                    'type' => $type
                );
                $this->db->insert($tableimages, $image_data_temp);
                $itemid = $this->db->insert_id();
            }

            return $insert_id;
        } else {
            return false;
        }
    }

    public function updateActivity($type, $image_present) {

        /* performs insert/update of items */
        $this->load->helper('url');

        $type = strtolower($type);
        $tableitems = $type . "items";
        $tableimages = $type . "images";

        $item_title = $type . "_title";
        $item_position = $type . "_position";
        $item_type = $type . "_type";
        $item_id = $type . "_id";
        $item_date = $type . "_date";
        $item_time = $type . "_time";
        $item_active = $type . "_active";
        $item_sender = $type . "_sender";
        $item_receiver = $type . "_receiver";
        $item_instruction = $type . "_instruction";
//        $item_status = $type . "_status";
        $item_outcome = $type . "_outcome";
//        $item_description = $type . "_description";
        $item_taskid = "task_id";

        $ID = $this->input->post($item_id);
        $title = $this->input->post($item_title);
//        $description = $this->input->post($item_description);
        $position = $this->input->post($item_position);
        $active = $this->input->post($item_active);
        $sender = $this->input->post($item_sender);
        $receiver = implode(",", $this->input->post($item_receiver));
        $instruction = $this->input->post($item_instruction);
//        $status = $this->input->post($item_status);
        $outcome = $this->input->post($item_outcome);
        $taskid = $this->input->post($item_taskid);

        $display = "1";
        $linklabel = url_title($title, "-", true);

        $date_temp = $this->input->post($item_date);
        $temp_date = str_replace('/', '-', $date_temp);
        $scheduled_date = date('Y-m-d', strtotime($temp_date));

        $time_temp = $this->input->post($item_time);
        $scheduled_time = date('H:i:s', strtotime($time_temp));
        $sch_time = $scheduled_date . " " . $scheduled_time;

        if ($image_present) {
            $image_filename = $this->upload->data('file_name');
        } else {
            $image_filename = "";
        }

        //update task status if necessary
        $this->db->select("notification,ref_id");
        $this->db->from("taskitems");
        $this->db->where("ID", $taskid);
        $query = $this->db->get();

        if ($query->num_rows() > 0) {
            $results = $query->row_array();
            $notification = $results['notification'];
            $ref_id = $results['ref_id'];
            $update_task_res = $this->updateTaskStatus("task", $taskid, $notification);
        }

        if ($ID > 0) {
            //update
            $data = array(
                'title' => $title,
                'notification' => $sch_time,
                'sender' => $sender,
                'receiver' => $receiver,
                'instruction' => $instruction,
                'outcome' => $outcome,
                'position' => $position,
                'active' => $active,
//                'description' => $description,
                'display' => $display,
                'linklabel' => $linklabel,
                'date_modified' => date("Y-m-d H:i:s")
            );

            $this->db->where('ID', $ID);
            $this->db->update($tableitems, $data);

            if ($image_filename != "") {
                $image_data_temp = array(
                    'filename' => $image_filename,
                    'alt' => $caption,
                    'date_created' => date("Y-m-d H:i:s"),
                    'caption' => $caption,
                    'itemid' => $ID,
                    'main' => $main,
                    'type' => $type
                );
                $this->db->insert($tableimages, $image_data_temp);
                $itemid = $this->db->insert_id();
            }

            return $ID;
        } elseif ($ID == 0) {
            //insert
            $status = "open";
            //get last serial
            $last_serial = 0;
            $q_serial = "SELECT serial from $tableitems order by ID DESC LIMIT 1";
            $query = $this->db->query($q_serial);
            if ($query->num_rows() > 0) {
                $results = $query->row_array();
                if (isset($results)) {
                    $last_serial = intval($results["serial"]);
                }
            }

            $serial_created = 0;
            do {
                //create serial no
                ++$last_serial;
                $q_serial = "SELECT ID from $tableitems where serial='$last_serial'";

                $query = $this->db->query($q_serial);
                $count = $query->num_rows();
                if ($count <= 0) {
                    $serial_created = 1;
                }
            } while ($serial_created == 0);

            $data = array(
                'title' => $title,
                'serial' => $last_serial,
                'notification' => $sch_time,
                'status' => $status,
                'taskid' => $taskid,
                'sender' => $sender,
                'receiver' => $receiver,
                'instruction' => $instruction,
                'outcome' => $outcome,
                'position' => $position,
                'active' => $active,
//                'description' => $description,
                'display' => $display,
                'linklabel' => $linklabel,
                'type' => $type,
                'date_created' => date("Y-m-d H:i:s")
            );

            $this->db->insert($tableitems, $data);
            $insert_id = $this->db->insert_id();

            //$itemid = 0;
            if ($image_filename != "") {
                $image_data_temp = array('filename' => $image_filename,
                    'alt' => $caption,
                    'date_created' => date("Y-m-d H:i:s"),
                    'caption' => $caption,
                    'itemid' => $insert_id,
                    'main' => $main,
                    'type' => $type
                );
                $this->db->insert($tableimages, $image_data_temp);
                $itemid = $this->db->insert_id();
            }
            return $insert_id;
        } else {
            return false;
        }
    }

    public function getNotifications() {
        /* get existing notifications */
        $results = array();
        $now = date("Y-m-d H:i:s");
        $q = "SELECT ID,'' as taskid,title,type,notification FROM taskitems where active='1' and display='1' "
                . "and status <> 'closed' and notification <='$now' "
                . "union SELECT ID,taskid,title,type,notification FROM activityitems where active='1' "
                . "and display='1' and status <> 'closed' and notification <='$now' "
                . "union SELECT ID,'' as taskid,title,type,notification FROM reminderitems where active='1' "
                . "and display='1' and status <> 'closed' and notification <='$now' "
                . "order by notification desc";
//        echo $q;exit;

        $query = $this->db->query($q);

        if ($query->num_rows() > 0) {
            $results = $query->result_array();
        }
        return $results;
    }

    public function updatePassword($type) {
        /* update user's password */
        $tableitems = strtolower($type) . "items";
        $hashed_p = create_hash($this->input->post('users_hashed_p'));
        $user_id = $this->session->us_id;

        $data = array(
            'hashed_p' => $hashed_p,
            'date_modified' => date("Y-m-d H:i:s")
        );

        $this->db->where('ID', $user_id);
        $res = $this->db->update($tableitems, $data);

        if ($res) {
            return true;
        } else {
            return false;
        }
    }

    public function confirmPassword($id, $password) {
        $pass_valid=false;
        
        $this->db->select('hashed_p');
        $this->db->where('ID', $id);
        $query = $this->db->get('usersitems');
        $result = $query->row_array();
        if (isset($result)) {
            $hashed_p = $result["hashed_p"];
            $pass_valid = validate_password($password, $hashed_p);
        }        
        
        if ($pass_valid) {
            return true;
        } else {
            return false;
        }
    }

    public function resetApp() {
        $this->db->truncate('actionsimages');
        $this->db->truncate('actionsitems');
        $this->db->truncate('activityitems');
        $this->db->truncate('activityimages');
        $this->db->truncate('messageitems');
        $this->db->truncate('reminderimages');
        $this->db->truncate('reminderitems');
        $this->db->truncate('taskimages');
        $this->db->truncate('taskitems');
        $this->db->truncate('taskcategoryimages');
        $this->db->truncate('taskcategoryitems');

//        $this->db->select('ID');
//        $this->db->from('usersitems');
//        $this->db->where('access',3);        
//        $query=$this->db->get();
//        
//        if ($query->num_rows() > 0) {
//            $results = $query->row_array();
//            $ID = $results['ID'];
//        }

        $this->db->where('access <', 2);
        $this->db->delete('usersitems');

        return true;
    }

}
