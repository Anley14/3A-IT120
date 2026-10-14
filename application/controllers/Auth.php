<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    /** Show the selected account portal. */
    public function login($role = 'customer') {
        if (!in_array($role, array('customer', 'employee'), TRUE)) {
            show_404();
            return;
        }

        $account = $this->session->userdata('sunson_account');
        if (is_array($account) && isset($account['role']) && $account['role'] === $role) {
            redirect('auth/dashboard');
            return;
        }

        $this->load->view('portal_login', array(
            'account_role' => $role,
            'login_error' => $this->session->flashdata('login_error'),
            'employee_notice' => $this->session->flashdata('employee_notice')
        ));
    }

    /** Verify credentials only against the account type selected on the portal. */
    public function process_login($role = 'customer') {
        if (!in_array($role, array('customer', 'employee'), TRUE) || $this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }

        $identifier = trim((string) $this->input->post('identifier', TRUE));
        $password = (string) $this->input->post('password', FALSE);
        if ($identifier === '' || $password === '' || strlen($password) > 4096) {
            $this->session->set_flashdata('login_error', 'Enter your username or email and password.');
            redirect('auth/login/' . $role);
            return;
        }

        $table = $role === 'employee' ? 'employees' : 'users';
        $this->db->group_start()
            ->where('username', $identifier)
            ->or_where('email', strtolower($identifier))
            ->group_end();
        if ($role === 'customer') {
            $this->db->where('role', 'customer');
        }
        $account = $this->db->get($table)->row_array();

        if (!$account || !isset($account['password']) || !password_verify($password, $account['password'])) {
            $this->session->set_flashdata('login_error', 'We could not sign you in with those details. Check them and try again.');
            redirect('auth/login/' . $role);
            return;
        }

        $this->session->sess_regenerate(TRUE);
        $this->session->set_userdata('sunson_account', array(
            'id' => (int) $account['id'],
            'role' => $role,
            'name' => trim($account['first_name'] . ' ' . $account['last_name']),
            'username' => $account['username'],
            'department' => isset($account['department']) ? $account['department'] : NULL
        ));
        redirect('auth/dashboard');
    }

    /** Display the signed-in area for the current account type. */
    public function dashboard() {
        $account = $this->session->userdata('sunson_account');
        if (!is_array($account) || !isset($account['role']) || !in_array($account['role'], array('customer', 'employee'), TRUE)) {
            redirect('auth/login/customer');
            return;
        }

        $this->_ensure_quote_dashboard_fields();
        $this->load->model('Shop_model', 'shop');
        $this->shop->ensure_tables();
        if ($account['role'] === 'employee') {
            $requests = $this->db->order_by('created_at', 'DESC')->get('quote_requests')->result_array();
            $orders = $this->shop->all_orders();
        } else {
            $requests = $this->db->where('customer_id', (int) $account['id'])
                ->order_by('created_at', 'DESC')->get('quote_requests')->result_array();
            $orders = $this->shop->customer_orders((int) $account['id']);
        }

        $this->load->view('account_dashboard', array(
            'account' => $account,
            'requests' => $requests,
            'orders' => $orders
        ));
    }

    /** Update a quote's workflow status from the staff workspace. */
    public function update_quote_status() {
        $account = $this->session->userdata('sunson_account');
        if ($this->input->method(TRUE) !== 'POST' || !is_array($account)
            || !isset($account['role']) || $account['role'] !== 'employee') {
            show_404();
            return;
        }

        $request_id = (int) $this->input->post('request_id', TRUE);
        $status = $this->input->post('status', TRUE);
        $allowed_statuses = array('New', 'Contacted', 'Scheduled', 'Completed');
        if ($request_id < 1 || !in_array($status, $allowed_statuses, TRUE)) {
            $this->session->set_flashdata('dashboard_error', 'Choose a valid request and status.');
            redirect('auth/dashboard');
            return;
        }

        $this->_ensure_quote_dashboard_fields();
        $updated = $this->db->where('id', $request_id)->update('quote_requests', array('status' => $status));
        $this->session->set_flashdata($updated ? 'dashboard_success' : 'dashboard_error', $updated
            ? 'Request status updated.'
            : 'The request could not be updated.');
        redirect('auth/dashboard');
    }

    /** Add workflow fields to existing quote tables without losing saved leads. */
    private function _ensure_quote_dashboard_fields() {
        if (!$this->db->table_exists('quote_requests')) {
            $this->load->dbforge();
            $this->dbforge->add_field(array(
                'id' => array('type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'auto_increment' => TRUE),
                'name' => array('type' => 'VARCHAR', 'constraint' => 160),
                'email' => array('type' => 'VARCHAR', 'constraint' => 255),
                'phone' => array('type' => 'VARCHAR', 'constraint' => 30),
                'service' => array('type' => 'VARCHAR', 'constraint' => 30),
                'message' => array('type' => 'TEXT', 'null' => TRUE),
                'customer_id' => array('type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'null' => TRUE),
                'status' => array('type' => 'VARCHAR', 'constraint' => 30, 'default' => 'New'),
                'created_at' => array('type' => 'DATETIME', 'null' => FALSE)
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('quote_requests', TRUE);
        }

        $this->load->dbforge();
        if (!$this->db->field_exists('customer_id', 'quote_requests')) {
            $this->dbforge->add_column('quote_requests', array(
                'customer_id' => array('type' => 'INT', 'constraint' => 10, 'unsigned' => TRUE, 'null' => TRUE)
            ));
        }
        if (!$this->db->field_exists('status', 'quote_requests')) {
            $this->dbforge->add_column('quote_requests', array(
                'status' => array('type' => 'VARCHAR', 'constraint' => 30, 'default' => 'New')
            ));
        }
    }

    /** End the current Sun Son session. */
    public function logout() {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }
        $this->session->unset_userdata('sunson_account');
        $this->session->sess_regenerate(TRUE);
        $this->session->set_flashdata('login_success', 'You have been signed out.');
        redirect();
    }

    /** Validate and save a solar quote request. */
    public function process_quote() {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }

        $this->load->library('form_validation');
        $this->form_validation->set_rules('name', 'Name', 'trim|required|max_length[160]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[255]');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('service', 'Service', 'required|in_list[Consultation,Installation,Maintenance]');
        $this->form_validation->set_rules('message', 'Project details', 'trim|max_length[2000]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('quote_error', 'Please check the required fields and try again.');
            redirect(base_url() . '#contact');
            return;
        }

        // Upgrade older installations and keep their existing leads intact.
        $this->_ensure_quote_dashboard_fields();
        $session_account = $this->session->userdata('sunson_account');
        $customer_id = is_array($session_account) && isset($session_account['role'], $session_account['id'])
            && $session_account['role'] === 'customer'
            ? (int) $session_account['id']
            : NULL;

        $saved = $this->db->insert('quote_requests', array(
            'name' => trim($this->input->post('name', TRUE)),
            'email' => strtolower(trim($this->input->post('email', TRUE))),
            'phone' => trim($this->input->post('phone', TRUE)),
            'service' => $this->input->post('service', TRUE),
            'message' => trim($this->input->post('message', TRUE)) ?: NULL,
            'customer_id' => $customer_id,
            'status' => 'New',
            'created_at' => date('Y-m-d H:i:s')
        ));

        if (!$saved) {
            log_message('error', 'Quote request insert failed.');
            $this->session->set_flashdata('quote_error', 'We could not save your request right now. Please call us at (800) 555-0142.');
        } else {
            $this->session->set_flashdata('quote_success', 'Thanks! Your quote request is in. Our team will be in touch soon.');
        }
        redirect(base_url() . '#contact');
    }

    public function registration($role = 'customer') {
        if (!in_array($role, array('customer', 'employee'), TRUE)) {
            show_404();
            return;
        }

        if ($role === 'employee') {
            $this->session->set_flashdata('employee_notice', 'Employee accounts are created by HR or an administrator. Contact your team administrator for access.');
            redirect('auth/login/employee');
            return;
        }

        // Each account type has a direct registration route and matching form.
        $this->load->view('registration', array('account_role' => $role));
    }

    public function process_registration() {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }

        $role = $this->input->post('role', TRUE);
        if ($role !== 'customer') {
            $this->session->set_flashdata('employee_notice', 'Employee accounts are created by HR or an administrator. Contact your team administrator for access.');
            redirect('auth/login/employee');
            return;
        }

        $registration_path = 'register/customer';

        $this->load->library('form_validation');
        $this->form_validation->set_rules('first_name', 'First name', 'trim|required|max_length[80]');
        $this->form_validation->set_rules('middle_name', 'Middle name', 'trim|max_length[80]');
        $this->form_validation->set_rules('last_name', 'Last name', 'trim|required|max_length[80]');
        $this->form_validation->set_rules('birth_date', 'Date of birth', 'required|callback_valid_birth_date');
        $this->form_validation->set_rules('gender', 'Gender', 'required|in_list[Female,Male,Non-binary,Prefer not to say]');
        $this->form_validation->set_rules('country_code', 'Country code', 'required|in_list[+63,+1,+44]');
        $this->form_validation->set_rules('phone_number', 'Phone number', 'trim|required|max_length[30]');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|max_length[255]');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|max_length[80]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');
        $this->form_validation->set_rules('confirm_password', 'Password confirmation', 'required|matches[password]');
        $this->form_validation->set_rules('agree_terms', 'Terms of Service', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('registration_error', 'Check the required fields and make sure your email and username are unused.');
            redirect($registration_path);
            return;
        }

        $password = (string) $this->input->post('password', FALSE);
        if (strlen($password) > 72) {
            $this->session->set_flashdata('registration_error', 'Password must be no longer than 72 bytes.');
            redirect($registration_path);
            return;
        }

        $email = strtolower(trim($this->input->post('email', TRUE)));
        $username = trim($this->input->post('username', TRUE));
        foreach (array('users', 'employees', 'admins') as $table) {
            $this->db->group_start()
                ->where('username', $username)
                ->or_where('email', $email)
                ->group_end();
            if ($this->db->count_all_results($table) > 0) {
                $this->session->set_flashdata('registration_error', 'That email or username is already in use.');
                redirect($registration_path);
                return;
            }
        }

        $middle_name = trim($this->input->post('middle_name', TRUE));
        $account = array(
            'first_name' => trim($this->input->post('first_name', TRUE)),
            'middle_name' => $middle_name === '' ? NULL : $middle_name,
            'last_name' => trim($this->input->post('last_name', TRUE)),
            'birth_date' => $this->input->post('birth_date', TRUE),
            'gender' => $this->input->post('gender', TRUE),
            'country_code' => $this->input->post('country_code', TRUE),
            'phone_number' => trim($this->input->post('phone_number', TRUE)),
            'email' => $email,
            'username' => $username,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        );

        $account['role'] = 'customer';
        $account_table = 'users';

        $previous_db_debug = $this->db->db_debug;
        $this->db->db_debug = FALSE;
        $inserted = $this->db->insert($account_table, $account);
        $database_error = $this->db->error();
        $this->db->db_debug = $previous_db_debug;

        if (!$inserted) {
            log_message('error', 'Registration insert failed with database error ' . $database_error['code']);
            $message = (int) $database_error['code'] === 1062
                ? 'That email or username is already in use.'
                : 'Registration could not be completed. Please try again.';
            $this->session->set_flashdata('registration_error', $message);
            redirect($registration_path);
            return;
        }

        $this->session->set_flashdata('registration_success', 'Your account was created. You can now sign in.');
        redirect($registration_path);
    }

    public function valid_birth_date($date) {
        $birth_date = DateTime::createFromFormat('!Y-m-d', $date);
        $date_errors = DateTime::getLastErrors();

        if (!$birth_date || ($date_errors !== FALSE && ($date_errors['warning_count'] > 0 || $date_errors['error_count'] > 0))) {
            $this->form_validation->set_message('valid_birth_date', 'Enter a valid date of birth.');
            return FALSE;
        }

        if ($birth_date->format('Y-m-d') !== $date || $date > date('Y-m-d')) {
            $this->form_validation->set_message('valid_birth_date', 'Enter a valid date of birth.');
            return FALSE;
        }

        return TRUE;
    }
}
