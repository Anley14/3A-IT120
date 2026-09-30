<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function registration() {
        // Displays the registration view page
        $this->load->view('registration');
    }

    public function process_registration() {
        if ($this->input->method(TRUE) !== 'POST') {
            show_404();
            return;
        }

        $role = $this->input->post('role', TRUE);
        if (!in_array($role, array('customer', 'employee'), TRUE)) {
            $this->session->set_flashdata('registration_error', 'Choose a valid account type.');
            redirect('register');
            return;
        }

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
            redirect('register');
            return;
        }

        $password = (string) $this->input->post('password', FALSE);
        if (strlen($password) > 72) {
            $this->session->set_flashdata('registration_error', 'Password must be no longer than 72 bytes.');
            redirect('register');
            return;
        }

        $department = NULL;
        if ($role === 'employee') {
            $department = $this->input->post('department', TRUE);
            $departments = array('IT', 'Dispatch', 'Accounting', 'Installation', 'Engineering', 'Sales', 'Customer Support', 'Operations');
            if (!in_array($department, $departments, TRUE)) {
                $this->session->set_flashdata('registration_error', 'Choose a valid employee department.');
                redirect('register');
                return;
            }
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
                redirect('register');
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

        if ($role === 'employee') {
            $account['department'] = $department;
            $account_table = 'employees';
        } else {
            $account['role'] = 'customer';
            $account_table = 'users';
        }

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
            redirect('register');
            return;
        }

        $this->session->set_flashdata('registration_success', 'Your account was created. You can now sign in.');
        redirect('register');
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