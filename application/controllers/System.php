<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/** Role-aware operational pages for customers, staff, and administrators. */
class System extends CI_Controller {
    private $account;

    public function __construct() {
        parent::__construct();
        $this->account = $this->session->userdata('sunson_account');
        if (!is_array($this->account) || empty($this->account['role'])) {
            redirect('auth/login/customer');
            exit;
        }
        $this->ensure_tables();
    }

    public function index($section = 'overview') {
        $allowed = array('overview', 'schedule', 'profile', 'support', 'announcements', 'jobs', 'users', 'reports', 'settings');
        if (!in_array($section, $allowed, TRUE)) show_404();
        if ($this->session->userdata('sunson_force_password_change') && $section !== 'settings') { redirect('portal/settings'); return; }
        $role = $this->account['role'];
        $data = array('account' => $this->account, 'section' => $section, 'bookings' => array(), 'tickets' => array(), 'notices' => array(), 'users' => array(), 'employees' => array(), 'reports' => array(), 'checkins'=>array(), 'profile' => array(), 'services'=>array());

        if ($role === 'customer') {
            $data['bookings'] = $this->db->where('customer_id', $this->account['id'])->order_by('requested_at', 'DESC')->get('service_bookings')->result_array();
            $data['tickets'] = $this->db->where('customer_id', $this->account['id'])->order_by('created_at', 'DESC')->get('support_tickets')->result_array();
            $data['profile'] = $this->db->get_where('users', array('id' => $this->account['id']))->row_array();
        } elseif ($role === 'employee') {
            $query=$this->db->select('service_bookings.*, users.first_name AS customer_first_name, users.last_name AS customer_last_name')->from('service_bookings')->join('users','users.id = service_bookings.customer_id','left');
            if (($this->account['department'] ?? '') !== 'Dispatch') $query->where('technician_id', $this->account['id']);
            $data['bookings'] = $query->order_by('requested_at', 'ASC')->get()->result_array();
            if (($this->account['department'] ?? '') === 'Dispatch') $data['employees']=$this->db->where('is_active',1)->order_by('first_name')->get('employees')->result_array();
            if (($this->account['department'] ?? '') === 'HR') { $data['users']=$this->db->order_by('created_at','DESC')->get('users')->result_array(); $data['employees']=$this->db->order_by('created_at','DESC')->get('employees')->result_array(); }
            $data['tickets'] = array();
        } else {
            $data['bookings'] = $this->db->select('service_bookings.*, users.first_name AS customer_first_name, users.last_name AS customer_last_name')->from('service_bookings')->join('users','users.id = service_bookings.customer_id','left')->order_by('requested_at', 'ASC')->get()->result_array();
            $data['tickets'] = $this->db->order_by('created_at', 'DESC')->get('support_tickets')->result_array();
            $data['users'] = $this->db->order_by('created_at', 'DESC')->get('users')->result_array();
            $data['employees'] = $this->db->where('is_active',1)->order_by('created_at', 'DESC')->get('employees')->result_array();
            $data['reports'] = $this->db->order_by('created_at', 'DESC')->limit(30)->get('job_reports')->result_array();
            $data['checkins'] = $this->db->order_by('checked_in_at','DESC')->limit(50)->get('technician_checkins')->result_array();
        }
        $data['services'] = $this->db->order_by('service_name','ASC')->get('service_catalog')->result_array();
        $this->db->group_start()->where('audience', 'all')->or_where('audience', $role)
            ->or_group_start()->where('user_id', $this->account['id'])->where('audience', $role)->group_end()->group_end();
        $data['notices'] = $this->db->order_by('created_at', 'DESC')->limit(20)->get('system_notifications')->result_array();
        $data['success'] = $this->session->flashdata('system_success');
        $data['error'] = $this->session->flashdata('system_error');
        $this->load->view('system_workspace', $data);
    }

    public function book() {
        if (!$this->post_as('customer')) return;
        $service = trim($this->input->post('service', TRUE));
        if (!$this->db->where(array('service_name'=>$service,'is_active'=>1))->count_all_results('service_catalog')) return $this->flash_redirect('schedule','error','Choose an available service.');
        $this->load->library('form_validation');
        $this->form_validation->set_rules('service', 'Service', 'required|max_length[80]');
        $this->form_validation->set_rules('requested_at', 'Preferred date', 'required');
        $this->form_validation->set_rules('service_address', 'Service address', 'trim|required|max_length[255]');
        $this->form_validation->set_rules('notes', 'Notes', 'trim|max_length[1200]');
        if (!$this->form_validation->run()) return $this->flash_redirect('schedule', 'error', 'Please complete the service, preferred date, and address.');
        $date = strtotime($this->input->post('requested_at', TRUE));
        if (!$date || $date < time()) return $this->flash_redirect('schedule', 'error', 'Choose a future date and time.');
        $this->db->insert('service_bookings', array('customer_id' => $this->account['id'], 'service' => $service, 'requested_at' => date('Y-m-d H:i:s', $date), 'service_address' => trim($this->input->post('service_address', TRUE)), 'notes' => trim($this->input->post('notes', TRUE)), 'status' => 'Requested', 'created_at'=>date('Y-m-d H:i:s')));
        $this->flash_redirect('schedule', 'success', 'Your service request was sent to the Sun Son team.');
    }

    public function update_booking() {
        if (!$this->can_manage_schedule()) return;
        $id = (int) $this->input->post('booking_id', TRUE);
        $status = $this->input->post('status', TRUE);
        $tech = (int) $this->input->post('technician_id', TRUE);
        $requested = $this->input->post('requested_at', TRUE);
        if (!$id || !in_array($status, array('Requested','Approved','Declined','Scheduled','In progress','Completed','Cancelled'), TRUE)) return $this->flash_redirect('schedule', 'error', 'Choose a valid booking and status.');
        if ($tech && !$this->db->where(array('id' => $tech, 'is_active' => 1))->count_all_results('employees')) return $this->flash_redirect('schedule', 'error', 'Choose an active employee.');
        $changes = array('status' => $status, 'technician_id' => $tech ?: NULL, 'updated_at' => date('Y-m-d H:i:s'));
        if ($requested !== '') { $timestamp=strtotime($requested); if (!$timestamp || $timestamp < time()) return $this->flash_redirect('schedule','error','Choose a future date for the booking.'); $changes['requested_at']=date('Y-m-d H:i:s',$timestamp); }
        $this->db->where('id', $id)->update('service_bookings', $changes);
        $booking = $this->db->get_where('service_bookings', array('id' => $id))->row_array();
        if ($booking) $this->notify_user($booking['customer_id'], 'Booking update', 'Your ' . $booking['service'] . ' request is now ' . $status . '.', 'customer');
        $this->flash_redirect('schedule', 'success', 'Booking updated.');
    }

    public function manage_booking() {
        if (!$this->post_as('customer')) return;
        $id = (int)$this->input->post('booking_id', TRUE); $action = $this->input->post('action', TRUE);
        $booking = $this->db->get_where('service_bookings', array('id'=>$id,'customer_id'=>$this->account['id']))->row_array();
        if (!$booking || !in_array($booking['status'],array('Requested','Approved','Scheduled'),TRUE)) return $this->flash_redirect('schedule','error','This booking can no longer be changed online. Please contact support.');
        if ($action === 'cancel') {
            $this->db->where('id',$id)->update('service_bookings',array('status'=>'Cancelled','updated_at'=>date('Y-m-d H:i:s')));
            $this->flash_redirect('schedule','success','Your booking was cancelled.');
        }
        if ($action !== 'reschedule') return $this->flash_redirect('schedule','error','Choose reschedule or cancel.');
        $date = strtotime($this->input->post('requested_at', TRUE));
        if (!$date || $date < time()) return $this->flash_redirect('schedule','error','Choose a future date and time.');
        $this->db->where('id',$id)->update('service_bookings',array('requested_at'=>date('Y-m-d H:i:s',$date),'status'=>'Requested','updated_at'=>date('Y-m-d H:i:s')));
        $this->flash_redirect('schedule','success','Your new preferred time was sent for confirmation.');
    }

    public function checkin() {
        if (!$this->post_as('employee')) return;
        $booking_id = (int) $this->input->post('booking_id', TRUE);
        $booking = $this->db->get_where('service_bookings', array('id' => $booking_id, 'technician_id' => $this->account['id']))->row_array();
        if (!$booking) return $this->flash_redirect('jobs', 'error', 'That job is not assigned to your account.');
        $lat = $this->input->post('latitude', TRUE); $lng = $this->input->post('longitude', TRUE);
        $lat = is_numeric($lat) && abs((float)$lat) <= 90 ? (float)$lat : NULL;
        $lng = is_numeric($lng) && abs((float)$lng) <= 180 ? (float)$lng : NULL;
        $this->db->insert('technician_checkins', array('booking_id' => $booking_id, 'employee_id' => $this->account['id'], 'latitude' => $lat, 'longitude' => $lng, 'checked_in_at'=>date('Y-m-d H:i:s')));
        $this->flash_redirect('jobs', 'success', 'Site check-in recorded at ' . date('g:i A') . '.');
    }

    public function report_job() {
        if (!$this->post_as('employee')) return;
        $id = (int) $this->input->post('booking_id', TRUE); $details = trim($this->input->post('details', TRUE));
        if (!$this->db->where(array('id' => $id, 'technician_id' => $this->account['id']))->count_all_results('service_bookings') || $details === '' || strlen($details) > 3000) return $this->flash_redirect('jobs', 'error', 'Choose an assigned job and enter a report under 3,000 characters.');
        $this->db->insert('job_reports', array('booking_id' => $id, 'employee_id' => $this->account['id'], 'details' => $details, 'created_at'=>date('Y-m-d H:i:s')));
        $this->flash_redirect('jobs', 'success', 'Your job report was sent to the admin workspace.');
    }

    public function support_ticket() {
        if (!$this->post_as('customer')) return;
        $subject = trim($this->input->post('subject', TRUE)); $details = trim($this->input->post('details', TRUE));
        if ($subject === '' || strlen($subject) > 160 || $details === '' || strlen($details) > 3000) return $this->flash_redirect('support', 'error', 'Add a subject and a message under 3,000 characters.');
        $this->db->insert('support_tickets', array('customer_id' => $this->account['id'], 'subject' => $subject, 'details' => $details, 'status' => 'Open', 'created_at'=>date('Y-m-d H:i:s')));
        $this->flash_redirect('support', 'success', 'Your support request has been sent.');
    }

    public function create_employee() {
        if (!$this->can_manage_users()) return;
        $this->load->library('form_validation');
        foreach (array('first_name'=>'First name','last_name'=>'Last name','birth_date'=>'Birth date','gender'=>'Gender','phone_number'=>'Phone','email'=>'Email','username'=>'Username','department'=>'Department','password'=>'Temporary password') as $field=>$label) $this->form_validation->set_rules($field, $label, 'trim|required|max_length[255]');
        $this->form_validation->set_rules('email', 'Email', 'valid_email');
        $this->form_validation->set_rules('password', 'Temporary password', 'min_length[12]');
        if (!$this->form_validation->run() || strlen((string)$this->input->post('password', FALSE)) > 72) return $this->flash_redirect('users', 'error', 'Complete all fields and use a temporary password of 12–72 characters.');
        $email = strtolower(trim($this->input->post('email', TRUE))); $username = trim($this->input->post('username', TRUE));
        foreach (array('users','employees','admins') as $table) if ($this->db->where('username', $username)->or_where('email', $email)->count_all_results($table)) return $this->flash_redirect('users', 'error', 'That email or username is already in use.');
        $this->db->insert('employees', array('first_name'=>trim($this->input->post('first_name', TRUE)), 'middle_name'=>trim($this->input->post('middle_name', TRUE)) ?: NULL, 'last_name'=>trim($this->input->post('last_name', TRUE)), 'birth_date'=>$this->input->post('birth_date', TRUE), 'gender'=>$this->input->post('gender', TRUE), 'country_code'=>'+63', 'phone_number'=>trim($this->input->post('phone_number', TRUE)), 'email'=>$email, 'username'=>$username, 'password'=>password_hash((string)$this->input->post('password', FALSE), PASSWORD_DEFAULT), 'department'=>trim($this->input->post('department', TRUE)), 'is_active'=>1));
        $this->flash_redirect('users', 'success', 'Employee account created. The employee should change the temporary password after signing in.');
    }

    public function toggle_user() {
        if (!$this->can_manage_users()) return;
        $id = (int)$this->input->post('id', TRUE); $kind = $this->input->post('kind', TRUE); $table = $kind === 'employee' ? 'employees' : ($kind === 'customer' ? 'users' : '');
        if (!$id || !$table) return $this->flash_redirect('users', 'error', 'Choose a valid account.');
        $user = $this->db->get_where($table, array('id'=>$id))->row_array();
        if (!$user) return $this->flash_redirect('users', 'error', 'Account not found.');
        $this->db->where('id',$id)->update($table,array('is_active'=>empty($user['is_active']) ? 0 : 1));
        $this->flash_redirect('users','success','Account status updated.');
    }

    public function announce() {
        if (!$this->post_as('admin')) return;
        $title = trim($this->input->post('title', TRUE)); $message = trim($this->input->post('message', TRUE)); $audience = $this->input->post('audience', TRUE);
        if ($title === '' || strlen($title)>140 || $message === '' || strlen($message)>2000 || !in_array($audience,array('all','customer','employee'),TRUE)) return $this->flash_redirect('announcements','error','Add a title, message, and valid audience.');
        $this->db->insert('system_notifications',array('title'=>$title,'message'=>$message,'audience'=>$audience,'user_id'=>NULL,'created_by'=>$this->account['id'],'created_at'=>date('Y-m-d H:i:s')));
        $this->flash_redirect('announcements','success','Announcement published.');
    }

    public function save_service() {
        if (!$this->post_as('admin')) return;
        $id=(int)$this->input->post('id',TRUE); $name=trim($this->input->post('service_name',TRUE));
        $price=$this->input->post('estimate_price',TRUE); $active=$this->input->post('is_active',TRUE)==='1'?1:0;
        if ($name==='' || strlen($name)>80 || !is_numeric($price) || (float)$price<0 || (float)$price>999999999) return $this->flash_redirect('settings','error','Enter a service name and a non-negative estimate price.');
        $this->db->where('service_name',$name); if ($id) $this->db->where('id !=',$id);
        if ($this->db->count_all_results('service_catalog')>0) return $this->flash_redirect('settings','error','A service with that name already exists.');
        $row=array('service_name'=>$name,'estimate_price'=>number_format((float)$price,2,'.',''),'is_active'=>$active);
        if ($id) $this->db->where('id',$id)->update('service_catalog',$row); else { $row['created_at']=date('Y-m-d H:i:s'); $this->db->insert('service_catalog',$row); }
        $this->flash_redirect('settings','success','Service catalog updated.');
    }

    public function update_profile() {
        if (!$this->post_as('customer')) return;
        $first = trim($this->input->post('first_name', TRUE)); $last = trim($this->input->post('last_name', TRUE)); $phone = trim($this->input->post('phone_number', TRUE));
        if ($first === '' || $last === '' || strlen($first)>80 || strlen($last)>80 || strlen($phone)>30) return $this->flash_redirect('profile','error','Enter a valid first name, last name, and phone number.');
        $address = trim($this->input->post('address', TRUE));
        if (strlen($address) > 255) return $this->flash_redirect('profile','error','Address must be 255 characters or fewer.');
        $this->db->where('id',$this->account['id'])->update('users',array('first_name'=>$first,'middle_name'=>trim($this->input->post('middle_name',TRUE)) ?: NULL,'last_name'=>$last,'phone_number'=>$phone,'address'=>$address ?: NULL));
        $this->account['name'] = $first . ' ' . $last; $this->session->set_userdata('sunson_account',$this->account);
        $this->flash_redirect('profile','success','Your profile was updated.');
    }

    public function change_password() {
        if ($this->input->method(TRUE)!=='POST' || !in_array($this->account['role'],array('customer','employee','admin'),TRUE)) show_404();
        $admin=$this->account['role']==='admin'; $table=$admin?'admins':($this->account['role']==='customer'?'users':'employees'); $id_field=$admin?'admin_id':'id'; $hash_field=$admin?'password_hash':'password';
        $row=$this->db->get_where($table,array($id_field=>$this->account['id']))->row_array();
        $current=(string)$this->input->post('current_password',FALSE); $next=(string)$this->input->post('new_password',FALSE);
        if (!$row || !password_verify($current,$row[$hash_field]) || strlen($next)<12 || strlen($next)>72) return $this->flash_redirect($admin?'settings':'profile','error','Check your current password and choose a new password of 12–72 characters.');
        $change=array($hash_field=>password_hash($next,PASSWORD_DEFAULT)); if($admin) $change['must_change_password']=0;
        $this->db->where($id_field,$this->account['id'])->update($table,$change);
        if($admin) $this->session->unset_userdata('sunson_force_password_change');
        $this->flash_redirect($admin?'settings':'profile','success','Your password was changed.');
    }

    public function ticket_status() {
        if (!$this->post_as('admin')) return;
        $id=(int)$this->input->post('ticket_id',TRUE); $status=$this->input->post('status',TRUE);
        if (!$id || !in_array($status,array('Open','In progress','Resolved'),TRUE)) return $this->flash_redirect('support','error','Choose a valid ticket and status.');
        $this->db->where('id',$id)->update('support_tickets',array('status'=>$status));
        $this->flash_redirect('support','success','Ticket status updated.');
    }

    private function post_as($role) {
        if ($this->input->method(TRUE)!=='POST' || $this->account['role']!==$role) { show_404(); return FALSE; }
        return TRUE;
    }
    private function can_manage_schedule() {
        $allowed=$this->account['role']==='admin' || ($this->account['role']==='employee' && ($this->account['department'] ?? '')==='Dispatch');
        if ($this->input->method(TRUE)!=='POST' || !$allowed) { show_404(); return FALSE; }
        return TRUE;
    }
    private function can_manage_users() {
        $allowed=$this->account['role']==='admin' || ($this->account['role']==='employee' && ($this->account['department'] ?? '')==='HR');
        if ($this->input->method(TRUE)!=='POST' || !$allowed) { show_404(); return FALSE; }
        return TRUE;
    }
    private function flash_redirect($section,$type,$message) { $this->session->set_flashdata('system_'.$type,$message); redirect('portal/'.$section); }
    private function notify_user($user_id,$title,$message,$audience) { $this->db->insert('system_notifications',array('title'=>$title,'message'=>$message,'audience'=>$audience,'user_id'=>$user_id,'created_by'=>$this->account['id'],'created_at'=>date('Y-m-d H:i:s'))); }

    private function ensure_tables() {
        $this->load->dbforge();
        $tables = array(
            'service_bookings'=>array('id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE,'auto_increment'=>TRUE),'customer_id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE),'technician_id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE,'null'=>TRUE),'service'=>array('type'=>'VARCHAR','constraint'=>80),'requested_at'=>array('type'=>'DATETIME'),'service_address'=>array('type'=>'VARCHAR','constraint'=>255),'notes'=>array('type'=>'TEXT','null'=>TRUE),'status'=>array('type'=>'VARCHAR','constraint'=>30,'default'=>'Requested'),'created_at'=>array('type'=>'DATETIME'),'updated_at'=>array('type'=>'DATETIME','null'=>TRUE)),
            'support_tickets'=>array('id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE,'auto_increment'=>TRUE),'customer_id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE),'subject'=>array('type'=>'VARCHAR','constraint'=>160),'details'=>array('type'=>'TEXT'),'status'=>array('type'=>'VARCHAR','constraint'=>30,'default'=>'Open'),'created_at'=>array('type'=>'DATETIME')),
            'system_notifications'=>array('id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE,'auto_increment'=>TRUE),'title'=>array('type'=>'VARCHAR','constraint'=>140),'message'=>array('type'=>'TEXT'),'audience'=>array('type'=>'VARCHAR','constraint'=>30,'default'=>'all'),'user_id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE,'null'=>TRUE),'created_by'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE,'null'=>TRUE),'created_at'=>array('type'=>'DATETIME')),
            'technician_checkins'=>array('id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE,'auto_increment'=>TRUE),'booking_id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE),'employee_id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE),'latitude'=>array('type'=>'DECIMAL','constraint'=>'10,7','null'=>TRUE),'longitude'=>array('type'=>'DECIMAL','constraint'=>'10,7','null'=>TRUE),'checked_in_at'=>array('type'=>'DATETIME')),
            'job_reports'=>array('id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE,'auto_increment'=>TRUE),'booking_id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE),'employee_id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE),'details'=>array('type'=>'TEXT'),'created_at'=>array('type'=>'DATETIME'))
            ,'service_catalog'=>array('id'=>array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE,'auto_increment'=>TRUE),'service_name'=>array('type'=>'VARCHAR','constraint'=>80),'estimate_price'=>array('type'=>'DECIMAL','constraint'=>'12,2','default'=>0),'is_active'=>array('type'=>'TINYINT','constraint'=>1,'default'=>1),'created_at'=>array('type'=>'DATETIME'))
        );
        foreach ($tables as $name=>$fields) if (!$this->db->table_exists($name)) { $this->dbforge->add_field($fields); $this->dbforge->add_key('id',TRUE); $this->dbforge->create_table($name,TRUE); }
        foreach (array('users','employees') as $table) if ($this->db->table_exists($table) && !$this->db->field_exists('is_active',$table)) $this->dbforge->add_column($table,array('is_active'=>array('type'=>'TINYINT','constraint'=>1,'default'=>1)));
        if ($this->db->table_exists('users') && !$this->db->field_exists('address','users')) $this->dbforge->add_column('users',array('address'=>array('type'=>'VARCHAR','constraint'=>255,'null'=>TRUE)));
        if ($this->db->table_exists('service_catalog') && $this->db->count_all('service_catalog')===0) foreach (array('Consultation','Design','Permitting','Installation','Maintenance','Repair','Monitoring') as $service) $this->db->insert('service_catalog',array('service_name'=>$service,'estimate_price'=>0,'is_active'=>1,'created_at'=>date('Y-m-d H:i:s')));
        foreach (array('service_bookings'=>'created_at','support_tickets'=>'created_at','system_notifications'=>'created_at','technician_checkins'=>'checked_in_at','job_reports'=>'created_at') as $table=>$field) if ($this->db->table_exists($table) && !$this->db->field_exists($field,$table)) $this->dbforge->add_column($table,array($field=>array('type'=>'DATETIME','default'=>date('Y-m-d H:i:s'))));
    }
}
