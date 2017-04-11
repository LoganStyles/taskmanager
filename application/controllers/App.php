<?php

class App extends CI_Controller {
    /* controller for app
     * displays the dashboard by default
     * if no other section is loaded
     */

    public function __construct() {
        parent::__construct();
        $this->load->model('app_model');
    }

    public function index($offset = 0) {
        if (!isset($_SESSION['us_username'])) {
            if (get_cookie('existing_uname')) {
                $serial = get_cookie('existing_userial');
                $token = get_cookie('existing_utoken');
                $res_id = $this->app_model->updateLoginItems("users", $serial, $token);

                if ($res_id) {
                    $redirect = "index.php/app/";
                    redirect($redirect);
                } else {
                    $errors = TRUE;
                    $this->showLogin($errors);
                }
            } else {
                $this->showLogin(); //user has not logged in so redirect to login  
            }
        } else {
            /* loads initial data for the dashboard */
            $this->load->library('pagination');
            $config = array();
            $limit = 5;

            //get component titles for navigation bar
            $data["header_title"] = "";
            $data["allow_date"] = "";
            $data["notification"] = "";
            $data['notification_date'] = "";
            $data['notification_time'] = "";
            $data['taskcategory_titles'] = $this->app_model->getTitles('taskcategory');
            $data['actions_titles'] = $this->app_model->getTitles('actions');
            $data['users_titles'] = $this->app_model->getTitles('users');

            $data['site'] = $this->app_model->getItems('site');
            $data['received'][0]['title'] = "";
            $data['received'][0]['type'] = 'home';
            $data['received'][0]['ID'] = 0;
            //get summary for today
            $data['tasksummary'] = $this->app_model->getTodayDisplayedItems('task', $offset, $limit);
            $data['tasks_today_count'] = $this->app_model->getTotalTodayDisplayedComponents("task");
            $data['tasks_overdue_count'] = $this->app_model->getTotalOverdueDisplayedComponents("task");
            $data['tasks_pending_count'] = $this->app_model->getTotalPendingDisplayedComponents("task");

            $data['activity_today_count'] = $this->app_model->getTotalTodayDisplayedComponents("activity");
            $data['activity_overdue_count'] = $this->app_model->getTotalOverdueDisplayedComponents("activity");
            $data['activity_pending_count'] = $this->app_model->getTotalPendingDisplayedComponents("activity");

            $data['reminder_today_count'] = $this->app_model->getTotalTodayDisplayedComponents("reminder");
            $data['reminder_overdue_count'] = $this->app_model->getTotalOverdueDisplayedComponents("reminder");
            $data['reminder_pending_count'] = $this->app_model->getTotalPendingDisplayedComponents("reminder");
            $this->session->notif_obj = json_encode($this->app_model->getNotifications());

            $config["base_url"] = base_url() . 'index.php/app/';
            $config["total_rows"] = $data['tasks_today_count'];
            $config["per_page"] = $limit;

            //various pagination configuration
            $config['full_tag_open'] = '<ul class="pagination">';
            $config['full_tag_close'] = '</ul>';
            $config['prev_link'] = '«Previous';
            $config['prev_tag_open'] = '<li>';
            $config['prev_tag_close'] = '</li>';
            $config['next_link'] = 'Next»';
            $config['next_tag_open'] = '<li>';
            $config['next_tag_close'] = '</li>';
            $config['cur_tag_open'] = '<li class="active"><a href="#">';
            $config['cur_tag_close'] = '</a></li>';
            $config['num_tag_open'] = '<li>';
            $config['num_tag_close'] = '</li>';

            $config['first_tag_open'] = '<li>';
            $config['first_tag_close'] = '</li>';
            $config['last_tag_open'] = '<li>';
            $config['last_tag_close'] = '</li>';

            $config['first_link'] = '&lt;&lt;';
            $config['last_link'] = '&gt;&gt;';
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();

            $this->load->view('app/scripts/header_scripts_side_navigation', $data);
            $this->load->view('app/index', $data);
            $this->load->view('app/scripts/footer');
        }
    }

    public function login($type) {
        //authenticates users        
        if (isset($this->session->us_username)) {
            //do nothing
        } else {
            $item_signature = "login_signature";
            $item_password = "login_password";
            $this->form_validation->set_rules($item_signature, 'Username', 'required');
            $this->form_validation->set_rules($item_password, 'Password', 'required');

            if ($this->form_validation->run() == FALSE) {
                $prefix = "form_";
                $errors = TRUE;
                $this->showLogin($errors);
            } else {
                $login_signature = $this->input->post('login_signature');
                $login_password = $this->input->post('login_password');
                $login_remember = $this->input->post('login_remember');

                $res_id = $this->app_model->getLoginItems($type, $login_signature, $login_password, $login_remember);
                if ($res_id) {
                    $redirect = "index.php/app/";
                    redirect($redirect);
                } else {
                    $errors = TRUE;
                    $this->showLogin($errors);
                }
            }
        }
    }

    public function showLogin($errors = NULL) {
        /* displays login form */
        $item_signature = "login_signature";
        $item_password = "login_password";

        //fresh login
        $data['received'][0]['signature'] = "";
        $data['received'][0]['password'] = "";
        $data['received'][0]['login_error'] = "";
//        $data['received'][0]['password_change_success'] = "";

        if ($errors) {
            $data['received'][0]['signature'] = $this->input->post($item_signature);
            $data['received'][0]['password'] = $this->input->post($item_password);
            $data['received'][0]['login_error'] = $this->session->error_message;
//            if (isset($_SESSION["password_success_message"])) {
//                $data['received'][0]['password_change_success'] = $this->session->password_success_message;
//            }
        }

        $data['site'] = $this->app_model->getItems('site'); //get site info 

        $this->load->view('app/login', $data);
    }

    public function logout() {
        //delete cookies
        delete_cookie('existing_uname');
        delete_cookie('existing_userial');
        delete_cookie('existing_utoken');
        //delete sesssions
        $this->session->sess_destroy();
        $redirect = "index.php/app/";
        redirect($redirect);
    }

    public function showActivity($taskid, $id, $type, $prefix = NULL, $errors = NULL) {
        /* displays section items */
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        $page = $prefix . $type;
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
        $item_outcome = $type . "_outcome";
        $item_description = $type . "_description";

        //get titles for navigation
        $data["header_title"] = ucwords($type);
        $data['taskcategory_titles'] = $this->app_model->getTitles('taskcategory');
        $data['actions_titles'] = $this->app_model->getTitles('actions');
        $data['activities'] = $this->app_model->getDisplayedActivities("activity", $taskid);
        $data['users_titles'] = $this->app_model->getTitles('users');
        $data['site'] = $this->app_model->getItems('site'); //get site info  
        if ($prefix == "form_") {
            $data["allow_date"] = "Activity";
        } else {
            $data["allow_date"] = "";
        }

        $this->session->notif_obj = json_encode($this->app_model->getNotifications());

        $data['received'][0]['taskid'] = $taskid;
        if ($id == "0" && !$errors) {//new item so set defaults
            $data['received'][0]['title'] = "Follow Up";
            $data['received'][0]['type'] = $type;
            $data['received'][0]['ID'] = 0;
            $data['received'][0]['sender'] = "self";
            $data['received'][0]['receiver'] = "";
            $data['received'][0]['instruction'] = "";
            $data['received'][0]['outcome'] = "";
            $data['received'][0]['position'] = count($data['activities']) + 1;
            $data['received'][0]['active'] = "1";
            $data['received'][0]['description'] = "";
            $data['received'][0]['filename'] = "";
            $data['received'][0]['form_error'] = "";
            $data["notification"] = "";
            $data['notification_date'] = "";
            $data['notification_time'] = "";
        } elseif ($errors) {
            $data['received'][0]['form_error'] = $this->session->error_message;
            $data['received'][0]['title'] = $this->input->post($item_title);
            $data['received'][0]['type'] = $this->input->post($item_type);
            $data['received'][0]['ID'] = $this->input->post($item_id);
            $data['received'][0]['position'] = $this->input->post($item_position);
            $data['received'][0]['sender'] = $this->input->post($item_sender);
            $data['received'][0]['receiver'] = $this->input->post($item_receiver);
            $data['received'][0]['instruction'] = $this->input->post($item_instruction);
            $data['received'][0]['outcome'] = $this->input->post($item_outcome);
            $data["notification"] = "";
            $data['notification_date'] = $this->input->post($item_date);
            $data['notification_time'] = $this->input->post($item_time);
            $data['received'][0]['active'] = $this->input->post($item_active);
            $data['received'][0]['description'] = $this->input->post($item_description);
            $data['received'][0]['filename'] = $this->input->post('filename');
        } else {//existing about item                        
            $data['received'] = $this->app_model->getActivityItems($type, $id); //get current section items
            $data['notification_date'] = "";
            $data['notification_time'] = "";
            $data["notification"] = $data["received"][0]["notification"];
            $data['received'][0]['form_error'] = "";
            if (empty($data['received'][0]['ID'])) {//no items exist
                $redirect = "index.php/app/";
                redirect($redirect);
            }
        }
        if (!file_exists(APPPATH . 'views/app/templates/' . $page . '.php')) {
            echo base_url() . 'views/app/templates/' . $page . '.php';
            show_404();
        }

        $this->load->view('app/scripts/header_scripts_side_navigation', $data);
        $this->load->view('app/templates/' . $page, $data);
        $this->load->view('app/scripts/footer');
    }

    public function showReminder($id, $type, $prefix = NULL, $errors = NULL) {
        /* displays section items */
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        $page = $prefix . $type;
        $item_title = $type . "_title";
        $item_type = $type . "_type";
        $item_id = $type . "_id";
        $item_date = $type . "_date";
        $item_time = $type . "_time";
        $item_active = $type . "_active";
        $item_description = $type . "_description";

        //get titles for navigation
        $data["header_title"] = ucwords($type);
        $data['taskcategory_titles'] = $this->app_model->getTitles('taskcategory');
        $data['actions_titles'] = $this->app_model->getTitles('actions');
        $data['users_titles'] = $this->app_model->getTitles('users');
        $data['site'] = $this->app_model->getItems('site'); //get site info  
        if ($prefix == "form_") {
            $data["allow_date"] = "Reminder";
        } else {
            $data["allow_date"] = "";
        }

        $this->session->notif_obj = json_encode($this->app_model->getNotifications());

        if ($id == "0" && !$errors) {//new item so set defaults
            $data['received'][0]['title'] = "";
            $data['received'][0]['type'] = $type;
            $data['received'][0]['ID'] = 0;
            $data['received'][0]['active'] = "1";
            $data['received'][0]['description'] = "";
            $data['received'][0]['filename'] = "";
            $data['received'][0]['form_error'] = "";
            $data["notification"] = "";
            $data['notification_date'] = "";
            $data['notification_time'] = "";
        } elseif ($errors) {
            $data['received'][0]['form_error'] = $this->session->error_message;
            $data['received'][0]['title'] = $this->input->post($item_title);
            $data['received'][0]['type'] = $this->input->post($item_type);
            $data['received'][0]['ID'] = $this->input->post($item_id);
            $data["notification"] = "";
            $data['notification_date'] = $this->input->post($item_date);
            $data['notification_time'] = $this->input->post($item_time);
            $data['received'][0]['active'] = $this->input->post($item_active);
            $data['received'][0]['description'] = $this->input->post($item_description);
            $data['received'][0]['filename'] = $this->input->post('filename');
        } else {//existing about item                        
            $data['received'] = $this->app_model->getItems($type, $id); //get current section items
            $data['notification_date'] = "";
            $data['notification_time'] = "";
            $data["notification"] = $data["received"][0]["notification"];
            $data['received'][0]['form_error'] = "";
            if (empty($data['received'][0]['ID'])) {//no items exist
                $redirect = "index.php/app/";
                redirect($redirect);
            }
        }
        if (!file_exists(APPPATH . 'views/app/templates/' . $page . '.php')) {
            echo base_url() . 'views/app/templates/' . $page . '.php';
            show_404();
        }

        $this->load->view('app/scripts/header_scripts_side_navigation', $data);
        $this->load->view('app/templates/' . $page, $data);
        $this->load->view('app/scripts/footer');
    }

    public function showSettings() {
        /* displays section items */
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        $page = "view_settings";

        //get titles for navigation
        $data["header_title"] = "Settings";
        $data['taskcategory_titles'] = $this->app_model->getTitles('taskcategory');
        $data['actions_titles'] = $this->app_model->getTitles('actions');
        $data['users_titles'] = $this->app_model->getTitles('users');
        $data['site'] = $this->app_model->getItems('site'); //get site info  
        $data["allow_date"] = "";

        $this->session->notif_obj = json_encode($this->app_model->getNotifications());
        $data['received'][0]['title'] = "";
        $data['received'][0]['type'] = "";
        $data['received'][0]['ID'] = 0;
        $data['received'][0]['active'] = "1";
        $data['received'][0]['description'] = "";
        $data['received'][0]['filename'] = "";
        $data['received'][0]['form_error'] = "";
        $data["notification"] = "";
        $data['notification_date'] = "";
        $data['notification_time'] = "";

        if (!file_exists(APPPATH . 'views/app/templates/' . $page . '.php')) {
            echo base_url() . 'views/app/templates/' . $page . '.php';
            show_404();
        }

        $this->load->view('app/scripts/header_scripts_side_navigation', $data);
        $this->load->view('app/templates/' . $page);
        $this->load->view('app/scripts/footer');
    }

    public function showReports() {
        /* displays section items */
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        $page = "view_reports";

        //get titles for navigation
        $data["header_title"] = "Reports";
        $data['taskcategory_titles'] = $this->app_model->getTitles('taskcategory');
        $data['actions_titles'] = $this->app_model->getTitles('actions');
        $data['users_titles'] = $this->app_model->getTitles('users');
        $data['site'] = $this->app_model->getItems('site'); //get site info  
        $data["allow_date"] = "Reports";

        $this->session->notif_obj = json_encode($this->app_model->getNotifications());
        $data['received'][0]['title'] = "";
        $data['received'][0]['type'] = "";
        $data['received'][0]['ID'] = 0;
        $data['received'][0]['active'] = "1";
        $data['received'][0]['description'] = "";
        $data['received'][0]['filename'] = "";
        $data['received'][0]['form_error'] = "";
        $data["notification"] = "";
        $data['notification_date'] = "";
        $data['notification_time'] = "";

        if (!file_exists(APPPATH . 'views/app/templates/' . $page . '.php')) {
            echo base_url() . 'views/app/templates/' . $page . '.php';
            show_404();
        }

        $this->load->view('app/scripts/header_scripts_side_navigation', $data);
        $this->load->view('app/templates/' . $page);
        $this->load->view('app/scripts/footer');
    }

    public function showTask($id, $type, $prefix = NULL, $errors = NULL) {
        /* displays section items */
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        $page = $prefix . $type;
        $item_title = $type . "_title";
        $item_category = $type . "_category";
        $item_type = $type . "_type";
        $item_id = $type . "_id";
        $item_date = $type . "_date";
        $item_time = $type . "_time";
        $item_active = $type . "_active";
        $item_priority = $type . "_priority";
        $item_ref_id = $type . "_ref_id";
        $item_description = $type . "_description";

        //get titles for navigation
        $data["header_title"] = ucwords($type);
        $data['taskcategory_titles'] = $this->app_model->getTitles('taskcategory');
        $data['actions_titles'] = $this->app_model->getTitles('actions');
        $data['users_titles'] = $this->app_model->getTitles('users');
        $data['site'] = $this->app_model->getItems('site'); //get site info  
        if ($prefix == "form_") {
            $data["allow_date"] = "Task";
        } else {
            $data["allow_date"] = "";
        }

        $this->session->notif_obj = json_encode($this->app_model->getNotifications());

        if ($id == "0" && !$errors) {//new item so set defaults
            $data['received'][0]['title'] = "";
            $data['received'][0]['type'] = $type;
            $data['received'][0]['ID'] = 0;
            $data['received'][0]['category'] = "";
            $data['received'][0]['active'] = "1";
            $data['received'][0]['priority'] = "1";
            $data['received'][0]['ref_id'] = "";
            $data['received'][0]['description'] = "";
            $data['received'][0]['filename'] = "";
            $data['received'][0]['form_error'] = "";
            $data["notification"] = "";
            $data['notification_date'] = "";
            $data['notification_time'] = "";
        } elseif ($errors) {
            $data['received'][0]['form_error'] = $this->session->error_message;
            $data['received'][0]['title'] = $this->input->post($item_title);
            $data['received'][0]['type'] = $this->input->post($item_type);
            $data['received'][0]['ID'] = $this->input->post($item_id);
            $data['received'][0]['category'] = $this->input->post($item_category);
            $data["notification"] = "";
            $data['notification_date'] = $this->input->post($item_date);
            $data['notification_time'] = $this->input->post($item_time);
            $data['received'][0]['active'] = $this->input->post($item_active);
            $data['received'][0]['priority'] = $this->input->post($item_priority);
            $data['received'][0]['ref_id'] = $this->input->post($item_ref_id);
            $data['received'][0]['description'] = $this->input->post($item_description);
            $data['received'][0]['filename'] = $this->input->post('filename');
        } else {//existing about item                        
            $data['received'] = $this->app_model->getItems($type, $id); //get current section items
            $data['notification_date'] = "";
            $data['notification_time'] = "";
            $data["notification"] = $data["received"][0]["notification"];
            $data['received'][0]['form_error'] = "";
            if (empty($data['received'][0]['ID'])) {//no items exist
                $redirect = "index.php/app/";
                redirect($redirect);
            }
        }
        if (!file_exists(APPPATH . 'views/app/templates/' . $page . '.php')) {
            echo base_url() . 'views/app/templates/' . $page . '.php';
            show_404();
        }

        $this->load->view('app/scripts/header_scripts_side_navigation', $data);
        $this->load->view('app/templates/' . $page, $data);
        $this->load->view('app/scripts/footer');
    }

    public function showOptions($id, $type, $prefix = NULL, $errors = NULL) {
        /* displays section items */
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        $page = $prefix . $type;
        $item_title = $type . "_title";
        $item_caption = $type . "_caption";
        $item_main = $type . "_main";
        $item_type = $type . "_type";
        $item_id = $type . "_id";

        $item_imageid = $type . "_imageid";
        $item_position = $type . "_position";
        $item_display = $type . "_display";
        $item_description = $type . "_description";
        $item_alt = $type . "_alt";

        //get titles for navigation
        $data["header_title"] = ucwords($type);
        $data['taskcategory_titles'] = $this->app_model->getTitles('taskcategory');
        $data['actions_titles'] = $this->app_model->getTitles('actions');
        $data['users_titles'] = $this->app_model->getTitles('users');
        $data['site'] = $this->app_model->getItems('site'); //get site info 
        $data["allow_date"] = "";
        $data["notification"] = "";
        $data['notification_date'] = "";
        $data['notification_time'] = "";

        $this->session->notif_obj = json_encode($this->app_model->getNotifications());

        if ($id == "0" && !$errors) {//new item so set defaults
            $data['received'][0]['form_error'] = "";
            $data['received'][0]['title'] = "";
            $data['received'][0]['type'] = $type;
            $data['received'][0]['ID'] = 0;
            $data['received'][0]['imageid'] = "";
            $data['received'][0]['position'] = 1;
            $data['received'][0]['display'] = 1;
            $data['received'][0]['description'] = "";
            $data['received'][0]['filename'] = "";
            $data['received'][0]['alt'] = "";
            $data['received'][0]['caption'] = "";
            $data['received'][0]['main'] = "";
            if ($type == "taskcategory") {
                $data['received'][0]['position'] = count($data['taskcategory_titles']) + 1;
            } elseif ($type == "actions") {
                $data['received'][0]['position'] = count($data['actions_titles']) + 1;
            }
        } elseif ($errors) {
            $data['received'][0]['form_error'] = $this->session->error_message;
            $data['received'][0]['title'] = $this->input->post($item_title);
            $data['received'][0]['type'] = $this->input->post($item_type);
            $data['received'][0]['ID'] = $this->input->post($item_id);
            $data['received'][0]['imageid'] = $this->input->post($item_imageid);
            $data['received'][0]['position'] = $this->input->post($item_position);
            $data['received'][0]['display'] = $this->input->post($item_display);
            $data['received'][0]['description'] = $this->input->post($item_description);
            $data['received'][0]['filename'] = $this->input->post('filename');
            $data['received'][0]['caption'] = $this->input->post($item_caption);
            $data['received'][0]['main'] = $this->input->post($item_main);
            $data['received'][0]['alt'] = $this->input->post($item_alt);
        } else {//existing about item            
            $data['received'] = $this->app_model->getItems($type, $id); //get current section items
            $data['received'][0]['form_error'] = "";
            if (empty($data['received'][0]['ID'])) {//no items exist
                $redirect = "index.php/app/";
                redirect($redirect);
            }
        }
        if (!file_exists(APPPATH . 'views/app/templates/' . $page . '.php')) {
            echo base_url() . 'views/app/templates/' . $page . '.php';
            show_404();
        }

        $this->load->view('app/scripts/header_scripts_side_navigation', $data);
        $this->load->view('app/templates/' . $page, $data);
        $this->load->view('app/scripts/footer');
    }

    public function showUsers($id, $type, $prefix = NULL, $errors = NULL) {
        /* displays user details */
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }
        $page = $prefix . $type;
        $item_title = $type . "_title";
        $item_signature = $type . "_signature";
        $item_type = $type . "_type";
        $item_id = $type . "_id";
        $item_hashed_p = $type . "_hashed_p";
        $item_access = $type . "_access";
        $item_position = $type . "_position";
        $item_display = $type . "_display";

        //get titles for navigation
        $data['taskcategory_titles'] = $this->app_model->getTitles('taskcategory');
        $data['actions_titles'] = $this->app_model->getTitles('actions');
        $data['users_titles'] = $this->app_model->getTitles('users');
        $data["header_title"] = ucwords($type);
        $data["allow_date"] = "";
        $data["notification"] = "";
        $data['notification_date'] = "";
        $data['notification_time'] = "";
        $data['site'] = $this->app_model->getItems('site'); //get users info

        $this->session->notif_obj = json_encode($this->app_model->getNotifications());

        if ($id == "0" && !$errors) {//new item so set defaults
            $data['received'][0]['form_error'] = "";
            $data['received'][0]['title'] = "";
            $data['received'][0]['type'] = $type;
            $data['received'][0]['ID'] = 0;
            $data['received'][0]['signature'] = "";
            $data['received'][0]['hashed_p'] = "";
            $data['received'][0]['access'] = "";
            $data['received'][0]['display'] = "1";
            $data['received'][0]['position'] = count($data['users_titles']) + 1;
            // $data['received'][0]['filename'] = "";
        } elseif ($errors) {
            $data['received'][0]['form_error'] = $this->session->error_message;
            $data['received'][0]['title'] = $this->input->post($item_title);
            $data['received'][0]['type'] = $this->input->post($item_type);
            $data['received'][0]['ID'] = $this->input->post($item_id);
            $data['received'][0]['signature'] = $this->input->post($item_signature);
            $data['received'][0]['hashed_p'] = $this->input->post($item_hashed_p);
            $data['received'][0]['access'] = $this->input->post($item_access);
            $data['received'][0]['display'] = $this->input->post($item_display);
            $data['received'][0]['position'] = $this->input->post($item_position);
            //$data['received'][0]['filename'] = $this->input->post('filename');
        } else {//existing item
            $data['received'] = $this->app_model->getItems($type, $id); //get current user items
            $data['received'][0]['form_error'] = "";

            if (empty($data['received'][0]['ID'])) {//no items exist
                $redirect = "index.php/app/";
                redirect($redirect);
            }
        }
        if (!file_exists(APPPATH . 'views/app/templates/' . $page . '.php')) {
            echo base_url() . 'views/app/templates/' . $page . '.php';
            show_404();
        }

        $this->load->view('app/scripts/header_scripts_side_navigation', $data);
        $this->load->view('app/templates/' . $page, $data);
        $this->load->view('app/scripts/footer');
    }

    public function delete($id, $type) {
        //delete an item from a specific type
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }
        $type = strtolower($type);
        $res_id = $this->app_model->deleteItem($id, $type);
        if ($res_id) {
            if ($type == "slide") {
                $show = "showImageGroup";
            } elseif ($type == "users") {
                $show = "showUsers";
            } elseif ($type == "taskcategory") {
                $show = "showOptions";
            } else {
                $show = "showItems";
            }
            $redirect = "index.php/app/" . $show . "/" . $res_id . "/" . $type . "/view_";
            redirect($redirect);
        }
    }

    public function saveActivity($type) {
        //save a particular item
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

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
        $item_outcome = $type . "_outcome";
//        $item_description = $type . "_description";
        $item_filename = $type . "_filename";
        $item_table = $type . "items";

        $item_taskid = "task_id";
        $taskid = $this->input->post($item_taskid);
        $item_unique_validation = "trim|required";

        $ID = $this->input->post($item_id);
        $type = $this->input->post($item_type);

        if ($ID > 0) {
            $this->form_validation->set_rules($item_title, 'Title', 'trim|required');
            $this->form_validation->set_rules($item_sender, 'From', 'trim');
            $this->form_validation->set_rules($item_instruction, 'Instruction', 'trim|required');
            $this->form_validation->set_rules($item_outcome, 'Outcome', 'trim');
        } else {
            $this->form_validation->set_rules($item_title, 'Title', 'trim|required');
            $this->form_validation->set_rules($item_sender, 'From', 'trim');
            $this->form_validation->set_rules($item_instruction, 'Instruction', 'trim|required');
            $this->form_validation->set_rules($item_outcome, 'Outcome', 'trim');
        }



        if ($this->form_validation->run() == FALSE) {
            $this->session->error_message = validation_errors();
            $prefix = "form_";
            $errors = TRUE;
            $this->showActivity($taskid, $ID, $type, $prefix, $errors);
        } else {
            //upload image if exists
            $imagepresent = FALSE;
            if (!empty($_FILES[$item_filename]['name'][0])) {
                $config['upload_path'] = './images/UPLOADS/';
                $config['allowed_types'] = 'gif|jpg|png|JPEG';
                $config['max_size'] = 2048;
                $config['max_width'] = 2048;
                $config['max_height'] = 2048;
                $config['remove_spaces'] = TRUE;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload($item_filename)) {//IF image was notuploaded
                    $error = array('error' => $this->upload->display_errors());
                    $errors = TRUE;
                    $prefix = "form_";
                    $this->showActivity($taskid, $ID, $type, $prefix, $errors);
                } else {
                    $imagedata = array('upload_data' => $this->upload->data());
                    $imagepresent = TRUE;
                }
            }

            $res_id = $this->app_model->updateActivity($type, $imagepresent);
            if ($res_id) {
                //view activities for this task
                $redirect = "index.php/app/showActivity/" . $taskid . "/" . $res_id . "/activity/view_";
                redirect($redirect);
            }
        }
    }

    public function processDashboardFilter($itemtype, $dashboardtype) {
        //
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        //filter fields
        switch ($dashboardtype) {
            case 'due':
                $filter = " and status <>'closed' and active='1' and display='1' and DATE(notification)=CURDATE()";
                break;
            case 'pending':
                $filter = " and status ='pending' and active='1' and display='1' and DATE(notification) <= CURDATE() ";
                break;
            case 'overdue':
                $filter = " and status ='open' and active='1' and display='1' and DATE(notification) < CURDATE() ";
                break;
            default:
                $filter = "";
                break;
        }

        $this->session->sent_filters = $filter;
        //store other filter values
        $this->session->filter_sort = "";
        $this->session->filter_reference = "";
        $this->session->filter_priority = "";
        $this->session->filter_status_open = "";
        $this->session->filter_status_pending = "";
        $this->session->filter_status_closed = "";
        $this->session->filter_category = "";
        $this->session->dashboard = "active";

        $redirect = "index.php/app/viewComponent/" . $itemtype . "/filter";
        redirect($redirect);
    }

    public function processTaskFilter() {
        //save a particular item
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        $sort = $priority = $reference = $status = $category = $final_where = "";

        $item_filter_ref_id = "filter_ref_id";
        $this->form_validation->set_rules($item_filter_ref_id, 'Reference ID', 'trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('filter_error_message', validation_errors());

            unset($_SESSION['sent_filters']);
            $redirect = "index.php/app/viewComponent/task/filter";
            redirect($redirect);
        } else {
            //build sort & filter queries
            //filter fields

            $item_filter_sort = "filter_sort";
            $item_filter_priority = "filter_priority";
            $item_filter_category = "filter_category";

            $sort_val = $this->input->post($item_filter_sort);
            $reference_val = $this->input->post($item_filter_ref_id);
            $priority_val = $this->input->post($item_filter_priority);
            $category_val = $this->input->post($item_filter_category);

            $status_open = ($this->input->post("filter_status_open")) ? ("'open',") : ("");
            $status_pending = ($this->input->post("filter_status_pending")) ? ("'pending',") : ("");
            $status_closed = ($this->input->post("filter_status_closed")) ? ("'closed',") : ("");

            $status_items = $status_open . $status_pending . $status_closed;

            $status_items = rtrim($status_items, ",");
            if ($status_items != "") {
                $status = " status in (" . $status_items . ") ";
            }


            if ($reference_val) {
                $reference = " lower(ref_id) LIKE '%" . $reference_val . "%' and";
            }

            if (($priority_val) && ($priority_val != "all")) {
                $priority = " priority ='" . $priority_val . "' and";
            }

            if ($category_val && $category_val != "all") {
                $category = " category ='" . $category_val . "' and";
            }

            switch ($sort_val) {
                case 'serial_asc':
                    $sort = " order by serial asc ";
                    break;
                case 'serial_desc':
                    $sort = " order by serial desc ";
                    break;
                case 'priority_asc':
                    $sort = " order by priority asc";
                    break;
                case 'priority_desc':
                    $sort = " order by priority desc";
                    break;
                case 'sch_asc':
                    $sort = " order by notification asc";
                    break;
                case 'sch_desc':
                    $sort = " order by notification desc";
                    break;
                case 'status_asc':
                    $sort = " order by status asc";
                    break;
                case 'status_desc':
                    $sort = " order by status desc";
                    break;
                default:
                    $sort = " order by position,ID desc";
                    break;
            }

            if (!empty($reference) || !empty($priority) || !empty($status) || !empty($category)) {
                $where = " and " . $reference . $priority . $category . $status;
                $cleaned_where = rtrim($where, "and");
                $final_where = $cleaned_where;
            }

            $final_where.=$sort;
            $this->session->sent_filters = $final_where;
            //store other filter values
            $this->session->filter_sort = $this->input->post($item_filter_sort);
            $this->session->filter_reference = $this->input->post($item_filter_ref_id);
            $this->session->filter_priority = $this->input->post($item_filter_priority);
            $this->session->filter_status_open = $this->input->post("filter_status_open");
            $this->session->filter_status_pending = $this->input->post("filter_status_pending");
            $this->session->filter_status_closed = $this->input->post("filter_status_closed");
            $this->session->filter_category = $this->input->post($item_filter_category);

            $redirect = "index.php/app/viewComponent/task/filter";
            redirect($redirect);
        }
    }

    public function processPrintTaskFilter() {
        //save a particular item
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }
        //printfilter fields
        $sort_val = $this->input->post("printfilter_sort");
        $task_id_val = $this->input->post("printfilter_task_id");
        $number_val = $this->input->post("printfilter_number");

        $this->session->printfilter_sort_val = $sort_val;
        $this->session->printfilter_number = $number_val;
        $this->session->printfilter_task = $task_id_val;

        $status_open = ($this->input->post("printfilter_status_open")) ? ("'open',") : ("");
        $status_pending = ($this->input->post("printfilter_status_pending")) ? ("'pending',") : ("");
        $status_closed = ($this->input->post("printfilter_status_closed")) ? ("'closed',") : ("");

        $sort = $limit = "";
        $sort_items = $status_open . $status_pending . $status_closed;

        $sort_items = rtrim($sort_items, ",");
        if ($sort_items != "") {
            $sort = " and status in (" . $sort_items . ") ";
        }

        if ($number_val != "all") {
            $limit = $number_val;
        }

        switch ($sort_val) {
            case 'serial_asc':
                $sort .= " order by serial asc ";
                break;
            case 'serial_desc':
                $sort .= " order by serial desc ";
                break;
            case 'priority_asc':
                $sort .= " order by priority asc";
                break;
            case 'priority_desc':
                $sort .= " order by priority desc";
                break;
            case 'sch_asc':
                $sort .= " order by notification asc";
                break;
            case 'sch_desc':
                $sort .= " order by notification desc";
                break;
            case 'action_asc':
                $sort .= " order by receiver asc";
                break;
            case 'action_desc':
                $sort .= " order by receiver desc";
                break;
            default:
                $sort .= " order by position,ID desc";
                break;
        }
        $this->session->printfilter_sort = $sort;
        $redirect = "index.php/app/printTaskActivities/" . $task_id_val . "/task/" . $limit;
        redirect($redirect);
    }

    public function saveTask($type) {
        //save a particular item
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        $item_id = $type . "_id";
        $item_type = $type . "_type";
        $item_title = $type . "_title";
        $item_category = $type . "_category";
        $item_date = $type . "_date";
        $item_time = $type . "_time";
        $item_ref_id = $type . "_ref_id";
        $item_filename = $type . "_filename";
        $item_table = $type . "items";
        $item_unique_validation = "trim|required|is_unique[" . $item_table . ".title]";
        $item_unique_ref_validation = "trim|is_unique[" . $item_table . ".ref_id]";

        $ID = $this->input->post($item_id);
        $type = $this->input->post($item_type);
        if ($ID > 0) {
            $this->form_validation->set_rules($item_title, 'Title', 'trim|required');
        } else {
            $this->form_validation->set_rules($item_title, 'Title', $item_unique_validation);
            $this->form_validation->set_rules($item_ref_id, 'Reference ID', $item_unique_ref_validation);
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->error_message = validation_errors();
            $prefix = "form_";
            $errors = TRUE;
            $this->showTask($ID, $type, $prefix, $errors);
        } else {
            //upload image if exists
            $imagepresent = FALSE;
            if (!empty($_FILES[$item_filename]['name'][0])) {
                $config['upload_path'] = './images/UPLOADS/';
                $config['allowed_types'] = 'gif|jpg|png|JPEG';
                $config['max_size'] = 2048;
                $config['max_width'] = 2048;
                $config['max_height'] = 2048;
                $config['remove_spaces'] = TRUE;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload($item_filename)) {//IF image was notuploaded
                    $error = array('error' => $this->upload->display_errors());
                    $errors = TRUE;
                    $prefix = "form_";
                    $this->showTask($ID, $type, $prefix, $errors);
                } else {
                    $imagedata = array('upload_data' => $this->upload->data());
                    $imagepresent = TRUE;
                }
            }

            $res_id = $this->app_model->updateTask($type, $imagepresent);
//            echo 'res: '.$res_id;exit;
            if ($res_id) {
                $redirect = "index.php/app/viewTaskActivities/" . $res_id . "/" . $type;
//                $redirect = "index.php/app/showTask/" . $res_id . "/" . $type . "/view_";
                redirect($redirect);
            }
        }
    }

    public function saveReminder($type) {
        //save a particular item
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        $item_id = $type . "_id";
        $item_type = $type . "_type";
        $item_title = $type . "_title";
        $item_date = $type . "_date";
        $item_time = $type . "_time";
        $item_filename = $type . "_filename";
        $item_table = $type . "items";
        $item_unique_validation = "trim|required|is_unique[" . $item_table . ".title]";

        $ID = $this->input->post($item_id);
        $type = $this->input->post($item_type);
        if ($ID > 0) {
            $this->form_validation->set_rules($item_title, 'Title', 'trim|required');
        } else {
            $this->form_validation->set_rules($item_title, 'Title', $item_unique_validation);
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->error_message = validation_errors();
            $prefix = "form_";
            $errors = TRUE;
            $this->showReminder($ID, $type, $prefix, $errors);
        } else {
            //upload image if exists
            $imagepresent = FALSE;
            if (!empty($_FILES[$item_filename]['name'][0])) {
                $config['upload_path'] = './images/UPLOADS/';
                $config['allowed_types'] = 'gif|jpg|png|JPEG';
                $config['max_size'] = 2048;
                $config['max_width'] = 2048;
                $config['max_height'] = 2048;
                $config['remove_spaces'] = TRUE;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload($item_filename)) {//IF image was notuploaded
                    $error = array('error' => $this->upload->display_errors());
                    $errors = TRUE;
                    $prefix = "form_";
                    $this->showReminder($ID, $type, $prefix, $errors);
                } else {
                    $imagedata = array('upload_data' => $this->upload->data());
                    $imagepresent = TRUE;
                }
            }

            $res_id = $this->app_model->updateReminder($type, $imagepresent);
            if ($res_id) {
                $redirect = "index.php/app/showReminder/" . $res_id . "/" . $type . "/view_";
                redirect($redirect);
            }
        }
    }

    public function processDelete() {
        //save a particular item
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        $type = $this->input->post("delete_type");

        $res_id = $this->app_model->deleteTrashItem();
        if ($res_id) {
            $redirect = "index.php/app/showTrash/" . $type;
            redirect($redirect);
        }
    }

    public function processRestore() {
        //save a particular item
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        $type = $this->input->post("restore_type");

        $res_id = $this->app_model->restoreTrashItem();
        if ($res_id) {
            $redirect = "index.php/app/showTrash/" . $type;
            redirect($redirect);
        }
    }

    public function saveTaskcategory($type) {
        //save a particular item
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }
        $item_id = $type . "_id";
        $item_type = $type . "_type";
        $item_title = $type . "_title";
        $item_position = $type . "_position";
        $item_display = $type . "_display";
        $item_filename = $type . "_filename";
        $item_table = $type . "items";
        $item_unique_validation = "trim|required|is_unique[" . $item_table . ".title]";

        $ID = $this->input->post($item_id);
        $type = $this->input->post($item_type);
        if ($ID > 0) {
            $this->form_validation->set_rules($item_title, 'Title', 'trim|required');
        } else {
            $this->form_validation->set_rules($item_title, 'Title', $item_unique_validation);
        }

        $this->form_validation->set_rules($item_position, 'Position', 'less_than_equal_to[999]|integer');
        $this->form_validation->set_rules($item_display, 'Display', 'less_than_equal_to[1]|integer');


        if ($this->form_validation->run() == FALSE) {
            $this->session->error_message = validation_errors();
            $prefix = "form_";
            $errors = TRUE;
            $this->showOptions($ID, $type, $prefix, $errors);
        } else {
            //upload image if exists
            $imagepresent = FALSE;
            if (!empty($_FILES[$item_filename]['name'][0])) {
                $config['upload_path'] = './images/UPLOADS/';
                $config['allowed_types'] = 'gif|jpg|png|JPEG';
                $config['max_size'] = 2048;
                $config['max_width'] = 2048;
                $config['max_height'] = 2048;
                $config['remove_spaces'] = TRUE;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload($item_filename)) {//IF image was notuploaded
                    $error = array('error' => $this->upload->display_errors());
                    $errors = TRUE;
                    $prefix = "form_";
                    $this->showOptions($ID, $type, $prefix, $errors);
                } else {
                    $imagedata = array('upload_data' => $this->upload->data());
                    $imagepresent = TRUE;
                }
            }

            $res_id = $this->app_model->updateOptions($type, $imagepresent);
            if ($res_id) {
                $redirect = "index.php/app/showOptions/" . $res_id . "/" . $type . "/view_";
                redirect($redirect);
            }
        }
    }

    public function saveUsers($type) {
        //save a particular item
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }
        $item_title = $type . "_title";
        $item_signature = $type . "_signature";
        $item_type = $type . "_type";
        $item_id = $type . "_id";
        $item_access = $type . "_access";
        $item_hashed_p = $type . "_hashed_p";
        //$item_imageid = $type . "_imageid";
        $item_filename = $type . "_filename";
        $item_table = $type . "items";
        $item_unique_validation = "trim|required|is_unique[" . $item_table . ".title]";

        $ID = $this->input->post($item_id);
        $type = $this->input->post($item_type);
        if ($ID > 0) {
            $this->form_validation->set_rules($item_title, 'Name', 'trim|required');
            $this->form_validation->set_rules($item_signature, 'Username', 'required');
            $this->form_validation->set_rules($item_hashed_p, 'Password', 'required');
        } else {
            $this->form_validation->set_rules($item_title, 'Name', $item_unique_validation);
            $this->form_validation->set_rules($item_signature, 'Username', 'required');
            $this->form_validation->set_rules($item_hashed_p, 'Password', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->session->error_message = validation_errors();
            $prefix = "form_";
            $errors = TRUE;
            $this->showUsers($ID, $type, $prefix, $errors);
        } else {
            //upload image if exists
            $imagepresent = FALSE;
            if (!empty($_FILES[$item_filename]['name'][0])) {
                $config['upload_path'] = './images/UPLOADS/';
                $config['allowed_types'] = 'gif|jpg|png|JPEG';
                $config['max_size'] = 2048;
                $config['max_width'] = 2048;
                $config['max_height'] = 2048;
                $config['remove_spaces'] = TRUE;

                $this->load->library('upload', $config);

                if (!$this->upload->do_upload($item_filename)) {//IF image was notuploaded
                    $error = array('error' => $this->upload->display_errors());
                    $errors = TRUE;
                    $prefix = "form_";
                    $this->showUsers($ID, $type, $prefix, $errors);
                } else {
                    $imagedata = array('upload_data' => $this->upload->data());
                    $imagepresent = TRUE;
                }
            }

            $res_id = $this->app_model->updateUsers($type, $imagepresent);
            if ($res_id) {
                $redirect = "index.php/app/showUsers/" . $res_id . "/" . $type . "/view_";
                redirect($redirect);
            }
        }
    }

    private function unsetFilters() {
        unset($_SESSION['sent_filters']);
        unset($_SESSION['filter_reference']);
        unset($_SESSION['filter_sort']);
        unset($_SESSION['filter_priority']);
        unset($_SESSION['filter_status_open']);
        unset($_SESSION['filter_status_pending']);
        unset($_SESSION['filter_status_closed']);
        unset($_SESSION['filter_category']);
        unset($_SESSION['dashboard']);
    }

    public function showTrash($type, $offset = 0) {
        /* displays paginised list of items */
        //$this->session->back_uri = base_url() . "index.php/" . uri_string();
        //security check
        if (!isset($this->session->us_username) || ($this->session->us_access < 1)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        $page = "trash_" . $type;
        $this->load->library('pagination');
        $config = array();
        $limit = 5;

        $results = $this->app_model->getDeletedItems($type, FALSE, $offset, $limit);
        $data[$type] = $results['data'];

        $data['taskcategory_titles'] = $this->app_model->getTitles('taskcategory');
        $data['actions_titles'] = $this->app_model->getTitles('actions');
        $data['site'] = $this->app_model->getItems('site');
        $data["users_titles"] = $this->app_model->getTitles('users');
        //defaults
        $data["allow_date"] = "";
        $data["notification"] = "";
        $data['notification_date'] = "";
        $data['notification_time'] = "";
        $data["header_title"] = "Trash";

        $data["received"][0]["title"] = "";
        $data["received"][0]["type"] = $type;
        $data["received"][0]["ID"] = 0;

        $this->session->notif_obj = json_encode($this->app_model->getNotifications());

        $config["base_url"] = base_url() . 'index.php/app/showTrash/' . $type;
        $config["total_rows"] = $results['count'];
        $config["per_page"] = $limit;

        //various pagination configuration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '«Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['first_link'] = '&lt;&lt;';
        $config['last_link'] = '&gt;&gt;';
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if (!file_exists(APPPATH . 'views/app/' . $page . '.php')) {
            echo base_url() . 'views/app/' . $page . '.php';
            show_404();
        }

        $this->load->view('app/scripts/header_scripts_side_navigation', $data);
        $this->load->view('app/' . $page, $data);
        $this->load->view('app/scripts/footer', $data);
    }

    public function viewComponent($type, $source = 'normal', $offset = 0) {
        /* displays paginised list of items */
        $this->session->back_uri = base_url() . "index.php/" . uri_string();

        //security check
        if (!isset($this->session->us_username) || ($this->session->us_access < 1)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        //check for filters        
        if (($source == 'filter') && (isset($_SESSION['sent_filters']))) {
            $filter = $this->session->sent_filters;
        } else {
            $filter = FALSE;
            $this->unsetFilters();
        }

        $this->load->library('pagination');
        $config = array();
        $limit = 5;

        $results = $this->app_model->getDisplayedItems($type, FALSE, $offset, $limit, $filter);
        $data[$type] = $results['data'];

        $data['taskcategory_titles'] = $this->app_model->getTitles('taskcategory');
        $data['actions_titles'] = $this->app_model->getTitles('actions');
        $data['task_titles'] = $this->app_model->getTitles('task');
        $data['site'] = $this->app_model->getItems('site');
        $data["users_titles"] = $this->app_model->getTitles('users');
        //defaults
        $data["allow_date"] = "";
        $data["notification"] = "";
        $data['notification_date'] = "";
        $data['notification_time'] = "";
        $data["header_title"] = ucwords($type);

        $data["received"][0]["title"] = "";
        $data["received"][0]["type"] = $type;
        $data["received"][0]["ID"] = 0;

        $data["received"][0]["filter_ref_id"] = "";
        $data['received'][0]['form_error'] = "";
        $data["received"][0]["filter_sort"] = "";
        $data["received"][0]["filter_priority"] = "";
        $data["received"][0]["filter_status_open"] = "";
        $data["received"][0]["filter_status_pending"] = "";
        $data["received"][0]["filter_status_closed"] = "";
        $data["received"][0]["filter_category"] = "";
        if (isset($_SESSION['filter_error_message']) || (isset($_SESSION['sent_filters']))) {//retain filter values if exist                      
            $data["received"][0]["filter_ref_id"] = $this->session->filter_reference;
            $data['received'][0]['form_error'] = $this->session->filter_error_message;
            $data["received"][0]["filter_sort"] = $this->session->filter_sort;
            $data["received"][0]["filter_priority"] = $this->session->filter_priority;
            $data["received"][0]["filter_status_open"] = $this->session->filter_status_open;
            $data["received"][0]["filter_status_pending"] = $this->session->filter_status_pending;
            $data["received"][0]["filter_status_closed"] = $this->session->filter_status_closed;
            $data["received"][0]["filter_category"] = $this->session->filter_category;
        }

        //printfilters
        $data["received"][0]["printfilter_sort"] = "serial_asc";
        $data["received"][0]["printfilter_number"] = "1";
        $data["received"][0]["printfilter_task"] = "";
        if (isset($_SESSION['printfilter_sort'])) {
            $data["received"][0]["printfilter_sort"] = $this->session->printfilter_sort_val;
            $data["received"][0]["printfilter_number"] = $this->session->printfilter_number;
            $data["received"][0]["printfilter_task"] = $this->session->printfilter_task;
        }

        $this->session->notif_obj = json_encode($this->app_model->getNotifications());

        $config["base_url"] = base_url() . 'index.php/app/viewComponent/' . $type . '/' . $source;
        $config["total_rows"] = $results['count'];
        $config["per_page"] = $limit;

        //various pagination configuration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '«Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['first_link'] = '&lt;&lt;';
        $config['last_link'] = '&gt;&gt;';
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if (!file_exists(APPPATH . 'views/app/' . $type . '.php')) {
            echo base_url() . 'views/app/' . $type . '.php';
            show_404();
        }

        $this->load->view('app/scripts/header_scripts_side_navigation', $data);
        $this->load->view('app/' . $type, $data);
        $this->load->view('app/scripts/footer', $data);
    }

    public function viewTaskActivities($id, $type, $offset = 0) {
        /* displays paginised list of items */

        //security check
        if (!isset($this->session->us_username) || ($this->session->us_access < 1)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }
        $this->load->library('pagination');
        $config = array();
        $limit = 5;

        $page = "view_" . $type;
        $results = $this->app_model->getDisplayedItems($type, $id);
        $data[$type] = $results['data'][0];
        $data['activities'] = $this->app_model->getDisplayedActivities("activity", $id, $offset, $limit);

        $data['taskcategory_titles'] = $this->app_model->getTitles('taskcategory');
        $data['actions_titles'] = $this->app_model->getTitles('actions');
        $data['site'] = $this->app_model->getItems('site');
        $data["users_titles"] = $this->app_model->getTitles('users');

        $data["allow_date"] = "";
        $data["notification"] = "";
        $data['notification_date'] = "";
        $data['notification_time'] = "";
        $data["header_title"] = ucwords($type);
        $data["received"][0]["title"] = "";
        $data["received"][0]["type"] = $type;
        $data["received"][0]["ID"] = 0;

        $this->session->current_offset = $offset;
        if ($this->session->request_response) {
            $data['received'][0]['request_response'] = $this->session->request_response;
        } else {
            $data['received'][0]['request_response'] = "";
        }

        $this->session->notif_obj = json_encode($this->app_model->getNotifications());

        $config["base_url"] = base_url() . 'index.php/app/viewTaskActivities/' . $id . '/' . $type;
        $config["total_rows"] = $this->app_model->getTotalDisplayedActivities("activity", $id);
        $config["per_page"] = $limit;

        //various pagination configuration
        $config['full_tag_open'] = '<ul class="pagination">';
        $config['full_tag_close'] = '</ul>';
        $config['prev_link'] = '«Previous';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Next»';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';

        $config['first_link'] = '&lt;&lt;';
        $config['last_link'] = '&gt;&gt;';
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();

        if (!file_exists(APPPATH . 'views/app/templates/' . $page . '.php')) {
            echo base_url() . 'views/app/templates/' . $page . '.php';
            show_404();
        }

        $this->load->view('app/scripts/header_scripts_side_navigation', $data);
        $this->load->view('app/templates/' . $page, $data);
        $this->load->view('app/scripts/footer');
    }

    public function processReports($type) {
        /* displays paginised list of items */

        //security check
        if (!isset($this->session->us_username) || ($this->session->us_access < 1)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        $item_sort = $type . "_sort";
        $item_number = $type . "_number";

        $sort_val = $this->input->post($item_sort);
        $number = $this->input->post($item_number);

        $status_open = ($this->input->post("printreport_status_open")) ? ("'open',") : ("");
        $status_pending = ($this->input->post("printreport_status_pending")) ? ("'pending',") : ("");
        $status_closed = ($this->input->post("printreport_status_closed")) ? ("'closed',") : ("");

        $limit = FALSE;
        $sort = "";

        if ($number != "all") {
            $limit = $number;
        }
        $filter_items = $status_open . $status_pending . $status_closed;

        $filter_items = rtrim($filter_items, ",");
        if ($filter_items != "") {
            $filter = " and items.status in (" . $filter_items . ") ";
        }

        switch ($sort_val) {
            case 'act_desc':
                $sort = " order by receiver desc ";
                break;
            case 'act_asc':
                $sort = " order by receiver asc ";
                break;
            case 'sch_desc':
                $sort = " order by notification desc";
                break;
            case 'sch_asc':
                $sort = " order by notification asc";
                break;
            case 'serial_desc':
                $sort = " order by serial desc";
                break;
            case 'serial_asc':
                $sort = " order by serial asc";
                break;
            default:
                $sort = " order by position,ID desc";
                break;
        }

        $page = "print_reports";
        $data[$type] = $this->app_model->getReportItems($limit, $sort, $filter);
        $data['actions_titles'] = $this->app_model->getTitles('actions');

        $data["date_from"] = $this->input->post("printreport_from");
        $data["date_to"] = $this->input->post("printreport_to");

        $data["header_title"] = ucwords("print reports");
        $data["received"][0]["title"] = "";
        $data["received"][0]["type"] = $type;
        $data["received"][0]["ID"] = 0;


        if (!file_exists(APPPATH . 'views/app/templates/' . $page . '.php')) {
            echo base_url() . 'views/app/templates/' . $page . '.php';
            show_404();
        }

        $this->load->view('app/scripts/header_print', $data);
        $this->load->view('app/templates/' . $page, $data);
        $this->load->view('app/scripts/footer_print');
    }

    public function printTaskActivities($id, $type, $limit = FALSE) {
        /* displays paginised list of items */

        //security check
        if (!isset($this->session->us_username) || ($this->session->us_access < 1)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }

        if ((isset($_SESSION['printfilter_sort']))) {
            $filter = $this->session->printfilter_sort;
        } else {
            $filter = FALSE;
        }

        $page = "print_" . $type;
        $results = $this->app_model->getDisplayedItems($type, $id);
        $data[$type] = $results['data'][0];
        $data['activities'] = $this->app_model->getDisplayedActivities("activity", $id, 0, $limit, $filter);
        $data['taskcategory_titles'] = $this->app_model->getTitles('taskcategory');
        $data['actions_titles'] = $this->app_model->getTitles('actions');

        $data["header_title"] = ucwords("print");
        $data["received"][0]["title"] = "";
        $data["received"][0]["type"] = $type;
        $data["received"][0]["ID"] = 0;

        if (!file_exists(APPPATH . 'views/app/templates/' . $page . '.php')) {
            echo base_url() . 'views/app/templates/' . $page . '.php';
            show_404();
        }

        $this->load->view('app/scripts/header_print', $data);
        $this->load->view('app/templates/' . $page, $data);
        $this->load->view('app/scripts/footer_print');
    }

    public function page404() {
        $this->load->view('app/page404');
    }

    public function updateItem($type, $ID, $action) {
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }
        $result = $this->app_model->updateItem($type, $ID, $action);
        echo $result;
        exit;
    }

    public function updatePageItem($type, $ID, $action) {
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }
        $result = $this->app_model->updateItem($type, $ID, $action);
        if ($result) {
            //modify this later
            $redirect = "index.php/app/viewComponent/" . $type;
            redirect($redirect);
        }
    }

    public function resetApp() {
        if (!isset($this->session->us_username)) {
            $redirect = "index.php/app/";
            redirect($redirect);
        }
        $result = $this->app_model->resetApp();
        if ($result) {
            $this->session->set_flashdata('reset_message', "Application Reset Complete!");
            $redirect = "index.php/app/showSettings";
            redirect($redirect);
        }
    }

    public function changePassword() {
        $type = "users";
        $old_password = $this->input->post('users_oldpassword');
        $res_p = $this->app_model->confirmPassword($this->session->us_id, $old_password);
        if (!$res_p) {
            $this->session->set_flashdata('error_message', 'Invalid User');
            $errors = TRUE;
            $this->showPassword($errors);
        } else {
            $item_oldpassword = "users_oldpassword";
            $item_hashed_p = "users_hashed_p";
            $item_cpassword = "users_cpassword";
            $item_password_match = "required|matches[" . $item_hashed_p . "]";

            $this->form_validation->set_rules($item_oldpassword, 'Old password', 'required');
            $this->form_validation->set_rules($item_hashed_p, 'New Password', 'required');
            $this->form_validation->set_rules($item_cpassword, 'Re-type New Password', $item_password_match);

            if ($this->form_validation->run() == FALSE) {
                $this->session->set_flashdata('error_message', validation_errors());
                $errors = TRUE;
                $this->showPassword($errors);
            } else {
                $res_id = $this->app_model->updatePassword($type);
                if ($res_id) {
                    $redirect = "index.php/app/logout";
                    redirect($redirect);
                } else {
                    //display invalid username/password
                    $this->session->set_flashdata('error_message', 'Password change failed');
                    $errors = TRUE;
                    $this->showPassword($errors);
                }
            }
        }
    }

    public function showPassword($errors = NULL) {
        /* displays change password */
        if ($errors) {
            $data['received'][0]['password_error'] = $this->session->error_message;
        } else {//new change
            $data['received'][0]['password_error'] = "";
        }

        $data['site'] = $this->app_model->getItems('site'); //get site info   
        if (!file_exists(APPPATH . 'views/app/password.php')) {
            echo base_url() . 'views/app/password.php';
            show_404();
        }
        $this->load->view('app/password', $data);
    }

    public function serverUpdate() {
        /* get task & activities notifs
         * for due notifs & output
         */
        $notifs_count = 0;
        $output = "";
        $notifs = $this->app_model->getNotifications();
        if (count($notifs) > 0) {
            header("Content-Type: text/event-stream");
            header("Cache-Control: no-cache");
            header("Connection: keep-alive");
            $retry = 60000; //reconnects in 60s        
            echo "retry:" . $retry . "\n";
            echo "data:" . json_encode($notifs);
            echo "\n\n";
            ob_flush();
            flush();
        }
    }

}
