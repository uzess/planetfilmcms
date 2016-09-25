<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['page'] = 'dashboard/setting_v';
        $this->data['select'] = 'setting';
        $this->load->view('dashboard/template', $this->data);
    }

    public function save()
    {
        if (isset($_POST['submit']))
        {
            $name = $this->input->post('web_name');
            $email = $this->input->post('web_email');
            $title = $this->input->post('meta_title');
            $keyword = $this->input->post('meta_keyword');
            $description = $this->input->post('meta_description');
            $footer = $this->input->post('footer');
            $fb_link = $this->input->post('fb_link');
            $twitter_link = $this->input->post('twitter_link');
            $ins = array('meta_title' => $title,
                'meta_keyword' => $keyword,
                'meta_description' => $description,
                'email' => $email,
                'site_name' => $name,
                'footer' => $footer,
                'fb_link' => $fb_link,
                'twitter_link' => $twitter_link
            );
            $this->setting_m->save($ins, array('id' => 1));

            if ($_FILES['logo']['size'] > 0)
            {
                $error = $this->save_image(1);
            } else
                $error = 1;
            if ($error == 1)
            {
                $this->session->set_flashdata('success', 'Updated Successfully');
            } else
            {
                $this->session->set_flashdata('error', $error);
            }
                
            redirect('dashboard/setting', 'refresh');
        } else
        {
            $this->session->set_flashdata('error', 'Invalid Argument');
            redirect('dashboard/setting', 'refresh');
        }
    }

    public function save_image($id)
    {

        $config['upload_path'] = $this->config->item('logo_path'); //folder to upload files
        $config['allowed_types'] = "jpg|png|jpeg|gif|JPG|PNG"; // allowed extensions
        $config['max_size'] = 0; // maximum file size, 0 for no limit

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('logo'))
        {

            return $this->upload->display_errors();
        }
        $arr = $this->upload->data();
        $this->data = array('logo' => $arr['file_name']);
        $this->delete_image($id, FALSE);
        $this->setting_m->save($this->data, array('id' => $id));
        return 1;
    }

    public function delete_image($id, $load_view = TRUE)
    {
        $row = $this->setting_m->get('*', array('id' => $id), 1);
        $image_path = $this->config->item('logo_path') . '/' . $row->logo;
        if (file_exists($image_path) && !empty($row->logo))
        {
            unlink($image_path);
            $this->setting_m->save(array('logo' => ''), array("id" => $id));
        }
        if ($load_view == TRUE)
        {
            $this->session->set_flashdata('success', $this->success_msg);
            redirect("dashboard/setting", "refresh");
        }
        return TRUE;
    }

}
