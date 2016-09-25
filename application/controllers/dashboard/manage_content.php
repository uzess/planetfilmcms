<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manage_content extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->data['page'] = 'dashboard/dashboard_v';
        $this->data['select'] = 'menu';
        $this->load->view('dashboard/template', $this->data);
    }

    
    public function view($section = NULL, $parent_id = 0, $edit_id = NULL)
    {
        $this->data['select'] = $section;

        $section = urldecode($section);
        $this->check_section($section); //If not valid section it will redirect to default page :- Dashboard
        $this->data['page'] = 'dashboard/manage_content_v';
        $this->data['section'] = $section;
        $this->data['heading'] = "Manage Content";

        $where = array('section' => $section, 'parent_id' => $parent_id);
        $this->data['groups'] = $this->groups_m->get('*', $where, NULL, 'weight DESC');
        $row = $this->groups_m->get('IFNULL(MAX(weight),0)+10 weight', array("parent_id" => $parent_id, "section" => $section), 1);
        $this->data['weight'] = $row->weight;
        $this->data['link_list'] = $this->get_link_list($section, 0, $parent_id, 0);
        $this->data['bread_crumb'] = $this->get_bread_crumb($parent_id);

        if ($edit_id != NULL)
        {
            $this->data['row'] = $this->groups_m->get('*', array("id" => $edit_id), 1);
        }
        $this->load->view('dashboard/template', $this->data);
    }

    public function save()
    {
        $user_id = $this->session->userdata('user_id');

        if (isset($_POST['save']) || isset($_POST['update']))
        {
            $section = $this->input->post('section');
            $type = $this->input->post('type');
            $parent_id = $this->input->post('parent_id');
            $name = $this->input->post('name');
            $slug = $this->input->post('slug');
            $image = $_FILES['image'];

            $image_status = $this->input->post('image_status');
            $image_caption = $this->input->post('image_caption');
            $status = $this->input->post('status');
            $weight = $this->input->post('weight');
            $target = $this->input->post('target');
            $meta_title = $this->input->post('meta_title');
            $meta_keyword = $this->input->post('meta_keyword');
            $meta_description = $this->input->post('meta_description');

            $id = $this->input->post('id');
            $short_content = $this->input->post('short_content');

            if ($type == "Normal Group")
            {
                $content = $this->input->post('content');
            } elseif ($type == 'Link')
            {
                $content = $this->input->post('link');
            } else
            {
                $this->session->set_flashdata('error', "Invalid Argument");
                redirect('dashboard/manage_content/view/' . $section . '/' . $parent_id, 'refresh');
            }
        } else
        {
            $this->session->set_flashdata('error', "Invalid Argument");
            redirect('dashboard/manage_content/view/', 'refresh');
        }

        if (isset($_POST['save']))
        {
            //Insert Data
            $this->data = array(
                "section" => $section, 
                "type" => $type, 
                "parent_id" => $parent_id, 
                'name' => $name, 
                "slug" => strtolower($slug),
                "image_status" => $image_status, 
                "image_caption" => $image_caption, 
                "status" => $status, 
                "weight" => $weight, 
                "target" => $target,
                "meta_title" => $meta_title, 
                "meta_keyword" => $meta_keyword, 
                "meta_description" => $meta_description,
                "short_content" => $short_content, 
                "content" => $content, 
                "user_id" => $user_id
            );
            $id = $this->groups_m->save($this->data);
            if ($image['size'] > 0)
                $this->save_image($id);

            if ($id > 0)
            {
                $this->session->set_flashdata('success', "Saved Successfully");
                redirect('dashboard/manage_content/view/' . $section . '/' . $parent_id, 'refresh');
            }
        } else
        {
            //Update Data
            $this->data = array(
                "parent_id" => $parent_id, 
                'name' => $name, 
                "slug" => strtolower($slug),
                "image_status" => $image_status, 
                "image_caption" => $image_caption, 
                "status" => $status, 
                "weight" => $weight, 
                "target" => $target,
                "meta_title" => $meta_title, 
                "meta_keyword" => $meta_keyword, 
                "meta_description" => $meta_description,
                "short_content" => $short_content, 
                "content" => $content, 
                "user_id" => $user_id
            );
            $affected_rows = $this->groups_m->save($this->data, array("id" => $id));
            if ($image['size'] > 0)
                $this->save_image($id);
            if ($affected_rows > 0)
            {
                $this->session->set_flashdata('success', "Update Successfully");
                redirect('dashboard/manage_content/view/' . $section . '/' . $parent_id . '/' . $id, 'refresh');
            }
        }
    }

    public function save_image($id)
    {
        $config['upload_path'] = $this->config->item('image_path'); //folder to upload files
        $config['allowed_types'] = "jpg|png|jpeg|gif"; // allowed extensions
        $config['max_size'] = 0; // maximum file size, 0 for no limit

        $this->load->library('upload', $config);
        if (!$this->upload->do_upload('image'))
        {
            return $this->upload->display_errors();
        }
        $arr = $this->upload->data();
        $this->data = array('image' => $arr['file_name']);
        $this->delete_image($id, FALSE);
        $this->groups_m->save($this->data, array('id' => $id));
        return TRUE;
    }

    public function delete_image($id, $load_view = TRUE)
    {
        $row = $this->groups_m->get('*', array('id' => $id), 1);
        $image_path = "./assets/uploads/groups/" . $row->image;
        if (file_exists($image_path) && !empty($row->image))
        {
            unlink($image_path);
            $this->groups_m->save(array('image' => ''), array("id" => $id));
        }
        if ($load_view == TRUE)
        {
            $this->session->set_flashdata('success', 'Image Deleted Successfully !');
            redirect("dashboard/manage_content/view/" . $row->section . "/" . $row->parent_id . "/" . $row->id, "refresh");
        }
        return TRUE;
    }

    public function check_section($section)
    {
        $section_arr = $this->config->item('section');
        foreach ($section_arr as $key => $val)
        {
            if ($key == $section)
            {
                return TRUE;
                break;
            }
        }
        $this->session->set_flashdata('error', 'Invalid Argument');
        redirect('dashboard/manage_content', 'refresh');
        exit();
    }

    public function generate_slug($key)
    {
        $key = urldecode($key);
        $key = str_replace(' ', '-', $key);
        $key = $this->skip_char($key);
        $row = $this->groups_m->get('id,name', array('slug' => $key), 1);
        if ($row == FALSE)
        {
            $temp = array('available' => 1, "slug" => $key);
        } else
        {
            $temp = array('available' => 0, "slug" => $key);
        }

        header('Content-Type:application/json');
        echo json_encode($temp);
    }

    public function delete($id)
    {
        $row = $this->groups_m->get('*', array('id' => $id), 1);

        $deletable = $this->groups_m->get('*', array('parent_id' => $id), 1);
        if ($deletable == FALSE)
        {
            $this->delete_image($row->id, FALSE);
            $this->groups_m->delete(array('id' => $row->id));
            $this->session->set_flashdata('success', 'Deleted');
            redirect('dashboard/manage_content/view/' . $row->section . '/' . $row->parent_id, 'refresh');
        } else
        {
            $this->session->set_flashdata('error', 'Not Deletable');
            redirect('dashboard/manage_content/view/' . $row->section . '/' . $row->parent_id, 'refresh');
        }
    }

}
