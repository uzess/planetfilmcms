<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('setting_m');
        $this->load->model('user_m');

        $this->data['setting'] = $this->setting_m->get('*', array('id' => 1), 1);
    }

    public function login()
    {
        if ($this->session->userdata('logged_in') == TRUE)
            redirect('dashboard/manage_contents', 'refresh');
        $data = '';

        if (isset($_POST['btn-submit']))
        {
            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');

            if ($this->form_validation->run() !== FALSE)
            {
                $user = $this->user_m->check();
                if ($user != FALSE)
                {
                    if ($user->status != 'A')
                        $data['msg'] = "Inactive user";
                    else
                    {
                        $userdata = array(
                            'username' => $user->username,
                            'user_id' => $user->id,
                            'last_login' => $user->lastLogin,
                            'role' => $user->role,
                            'logged_in' => TRUE
                        );
                        $this->session->set_userdata($userdata);
                        $this->user_m->updateLastLogin($user->id);
                        redirect('dashboard/manage_contents', 'refresh');
                    }
                } else
                    $data['msg'] = "Please enter valid Username Or Password!";
            }
        }
        $this->load->view('dashboard/index', $data);
    }

    public function delete($id, $load_view = true)
    {
        global $data;
        if ($this->user_m->delete($id) > 0)
            $data['message'] = 1;
        else
            $data['message'] = -1;
        if ($load_view == true)
            $this->index();
    }

    public function logout()
    {
        $user_id = $this->session->userdata('user_id');
        $this->user_m->updateLastLogin($user_id);
        $this->session->sess_destroy();
        $this->load->view('dashboard/index');
    }

    public function change_password()
    {
        if (isset($_POST['submit']))
        {
            $old_password = $this->input->post('old_password');
            $new_password = $this->input->post('new_password');
            $confirm_password = $this->input->post('confirm_password');
            if ($new_password == $confirm_password && !empty($new_password))
            {
                $user_id = $this->session->userdata('user_id');
                $row = $this->user_m->get('*', array('id' => $user_id, 'password' => md5($old_password)), 1);
                if ($row == FALSE)
                {
                    $this->session->set_flashdata('error', 'Incorrect Old Password.');
                    redirect('dashboard/user/change_password', 'refresh');
                } else
                {
                    $this->user_m->save(array('password' => md5($new_password)), array('id' => $user_id));
                    $this->session->set_flashdata('success', 'Password Changed Successfully.');
                    redirect('dashboard/user/change_password', 'refresh');
                }
            } else
            {
                $this->session->set_flashdata('error', 'Incorrect Password.');
                redirect('dashboard/user/change_password', 'refresh');
            }
        }
        $this->data['page'] = 'dashboard/change_password_v';
        $this->data['select'] = 'change_password';
        $this->load->view('dashboard/template', $this->data);
    }

}
