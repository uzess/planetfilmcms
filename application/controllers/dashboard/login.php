<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
    }

    public function index()
    {
        $this->check_login();
        $this->load->view('dashboard/login');
    }

    public function login_attempt()
    {
        $this->check_login();
        $data = array();

        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() !== FALSE)
        {
            $user_name = $this->input->post('username');
            $password = $this->input->post('password');
            $password = md5($password);

            $this->load->model('user_m');
            $where = array("user_name" => $user_name, "password" => $password);
            $user = $this->user_m->get('*', $where, 1);

            if ($user != FALSE)
            {
                if ($user->status == 'D')
                    $data['msg'] = "Inactive user";
                else
                {
                    $this->load->model('role_m');
                    $role = $this->role_m->get('name', array('id' => $user->role_id), 1);

                    $userdata = array(
                        'username' => $user->user_name,
                        'user_id' => $user->id,
                        'last_login' => $user->last_login,
                        'role' => $role->name,
                        'logged_in' => TRUE
                    );
                    $this->session->set_userdata($userdata);
                    $update = array('last_login' => date('Y-m-d H:i:s'), 'login_times' => $user->login_times + 1);
                    $where = array('id' => $user->id);
                    $this->user_m->save($update, $where);
                    redirect('dashboard/setting', 'refresh');
                }
            }
            else
                $data['msg'] = "Please enter valid Username Or Password!";
        }

        $this->load->view('dashboard/login', $data);
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect('dashboard/login','refresh');
    }

    public function check_login()
    {
        if ($this->session->userdata('logged_in') == TRUE)
            redirect('dashboard/manage_content', 'refresh');
        else
            return FALSE;
    }

}