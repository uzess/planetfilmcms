<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Video extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    public function feature($page = NULL)
    {
        $this->data['page'] = 'dashboard/feature_v';
        $this->data['select'] = 'video';
        $this->data['title'] = ucfirst($page) . ' Page Video';
        $this->data['directors'] = $this->groups_m->get('*', array('type' => 'director'));
        $this->data['videos'] = $this->groups_m->get('*', array('type' => 'video'));

        if ($page == 'home')
        {
            $this->data['feature_type'] = 'home';
            $where = array('show_in_homepage' => 1, 'type' => 'video');
            $ord = 'home_weight ASC';
        } elseif ($page == 'feature')
        {
            $this->data['feature_type'] = 'feature';

            $where = array('show_in_feature' => 1, 'type' => 'video');
            $ord = 'feature_weight ASC';
        } else
        {
            redirect('dashboard/video', 'refresh');
        }
        $this->data['feature_videos'] = $this->groups_m->get('*', $where, false, $ord);

        $feature_video_id = array();
        foreach ($this->data['feature_videos']->result() as $tem)
        {
            $feature_video_id[] = $tem->id;
        }
        $this->data['feature_video_id'] = $feature_video_id;
        $this->load->view('dashboard/template', $this->data);
    }

    public function getVideos($director_id = NULL)
    {
        $director_id = $this->input->post('directorId');
        $avoid_id = $this->input->post('avoidId');
        $videos = $this->groups_m->get('*', array('type' => 'video', 'parent_id' => $director_id));

        $filtered_video = array();
        foreach ($videos->result() as $vid)
        {
            if (!in_array($vid->id, $avoid_id))
            {
                $filtered_video[] = $vid;
            }
        }
        $this->data['videos'] = $filtered_video;
        $this->load->view('dashboard/ajax/getVideos', $this->data);
    }

    public function addFeature($type)
    {
        $this->data['directors'] = $this->groups_m->get('*', array('type' => 'director'));
        $videos = $this->groups_m->get('*', array('type' => 'video'));
        $avoid_video = array();
        if ($type == 'home')
        {
            $avoid_video_r = $this->groups_m->get('id', array('show_in_homepage' => 1, 'type' => 'video'));
            foreach ($avoid_video_r->result() as $tem)
            {
                $avoid_video[] = $tem->id;
            }
        } elseif ($type == 'feature')
        {
            $avoid_video_r = $this->groups_m->get('id', array('show_in_feature' => 1, 'type' => 'video'));
            foreach ($avoid_video_r->result() as $tem)
            {
                $avoid_video[] = $tem->id;
            }
        } else
        {
            die();
        }

        foreach ($videos->result() as $vid)
        {
            if (!in_array($vid->id, $avoid_video))
            {
                $this->data['videos'][] = $vid;
            }
        }

        $this->load->view('dashboard/ajax/featureVideo', $this->data);
    }

    public function saveFeature()
    {
        $weight = $this->input->post('weight');
        $vid_id = $this->input->post('vid_id');
        $feature_type = $this->input->post('feature_type');
        if ($feature_type == 'home')
        {
            $ins = array('show_in_homepage' => 0, 'home_weight' => NULL);
            $this->groups_m->save($ins, array('type' => 'video'));

            if ($vid_id)
            {
                foreach ($vid_id as $key => $id)
                {
                    $data = array('show_in_homepage' => 1, 'home_weight' => $weight[$key]);
                    $this->groups_m->save($data, array('id' => $id));
                }
            }
        } elseif ($feature_type == 'feature')
        {
            $ins = array('show_in_feature' => 0, 'feature_weight' => NULL);
            $this->groups_m->save($ins, array('type' => 'video'));
            if ($vid_id)
            {
                foreach ($vid_id as $key => $id)
                {
                    $data = array('show_in_feature' => 1, 'feature_weight' => $weight[$key]);
                    $this->groups_m->save($data, array('id' => $id));
                }
            }
        } else
        {
            redirect('dashboard/video/', 'refresh');
        }

        redirect('dashboard/video/feature/' . $feature_type, 'refresh');
    }

    public function index()
    {

        $this->data['page'] = 'dashboard/video_v';
        $this->data['select'] = 'video';
        $this->data['action'] = 'director';

        $this->data['directors'] = $this->groups_m->get('*', array('type' => 'director'), FALSE, 'weight ASC');
        $w = $this->groups_m->get('IFNULL(MAX(weight),0)+10 weight', array('type' => 'director', 'parent_id' => 0), 1);
        $this->data['weight'] = $w->weight;
        $this->load->view('dashboard/template', $this->data);
    }

    public function save()
    {
        if (isset($_POST['submit']))
        {
            $action = $this->input->post('action');
            $user_id = $this->session->userdata('user_id');
            $weight = $this->input->post('weight');
            $status = $this->input->post('status');
            if ($action == 'director')
            {

                $id = $this->input->post('id');
                $name = $this->input->post('name');
                $description = $this->input->post('description');

                $ins = array(
                    'name' => $name,
                    'content' => $description,
                    'type' => $action,
                    'parent_id' => 0,
                    'weight' => $weight,
                    'status' => $status,
                    'user_id' => $user_id,
                    'target' => 0
                );
                if ($id == NULL)
                {
                    $id = $this->groups_m->save($ins);
                    $slug = str_replace(' ', '-', $name) . '-' . $id;
                    $slug = $this->skip_char($slug);
                    $this->groups_m->save(array('slug' => $slug), array('id' => $id));
                    $this->session->set_flashdata('success', $this->save_msg);
                    redirect('dashboard/video', 'refresh');
                } else
                {
                    $slug = str_replace(' ', '-', $name) . '-' . $id;
                    $slug = $this->skip_char($slug);

                    $ins['slug'] = $slug;
                    $this->groups_m->save($ins, array('id' => $id));
                    $this->session->set_flashdata('success', $this->update_msg);
                    redirect('dashboard/video/edit/' . $id, 'refresh');
                }
            } elseif ($action == 'video')
            {
                $name = $this->input->post('name');
                $image_status = $this->input->post('image_status');
                $id = $this->input->post('id');
                $video_id = $this->input->post('video_id');
                $parent_id = $this->input->post('parent_id');
                $short_content = $this->input->post('short_content');
                $feature = $this->input->post('feature');
                $home_page = $this->input->post('home_page');
                $ins = array(
                    'name' => $name,
                    'image_status' => $image_status,
                    'content' => $video_id,
                    'short_content' => $short_content,
                    'type' => $action,
                    'status' => $status,
                    'weight' => $weight,
                    'user_id' => $user_id,
                    'target' => 0,
                    'parent_id' => $parent_id
                );


                if ($id == NULL)
                {
                    //save
                    $id = $this->groups_m->save($ins);
                    $slug = str_replace(' ', '-', $name) . '-' . $id;
                    $slug = $this->skip_char($slug);

                    $this->groups_m->save(array('slug' => $slug), array('id' => $id));
                    $this->save_image($id);
                    $this->session->set_flashdata('success', $this->save_msg);
                    redirect('dashboard/video/open/' . $parent_id, 'refresh');
                } else
                {
                    $slug = str_replace(' ', '-', $name) . '-' . $id;
                    $slug = $this->skip_char($slug);

                    $ins['slug'] = $slug;
                    $this->groups_m->save($ins, array('id' => $id));
                    $this->save_image($id);

                    $this->session->set_flashdata('success', $this->update_msg);
                    redirect('dashboard/video/edit/' . $id, 'refresh');
                }
            } else
            {
                $this->session->set_flashdata('error', $this->error_msg);
                redirect('dashboard/video', 'refresh');
            }
        } else
        {
            $this->session->set_flashdata('error', $this->error_msg);
            redirect('dashboard/video', 'refresh');
        }
    }

    public function save_featured()
    {

        $parent_id = $this->input->post('parent_id');
        if (isset($parent_id))
        {
            $feature_id = $this->input->post('feature_id');
            $home_page_id = $this->input->post('home_page_id');
            $this->groups_m->save(array('show_in_feature' => 0, 'show_in_homepage' => 0), array('parent_id' => $parent_id, 'type' => 'video'));
            foreach ($feature_id as $i)
            {
                $this->groups_m->save(array('show_in_feature' => 1), array('id' => $i));
            }
            foreach ($home_page_id as $i)
            {
                $this->groups_m->save(array('show_in_homepage' => 1), array('id' => $i));
            }
        }
    }

    public function edit($id = NULL)
    {
        $this->data['select'] = 'video';

        $row = $this->groups_m->get('*', array('id' => $id), 1);
        if ($row == FALSE)
        {
            $this->session->set_flashdata('error', $this->error_msg);
            redirect('dashboard/video', 'refresh');
        } else
        {
            if ($row->type == 'director')
            {
                $this->data['action'] = 'director';
                $this->data['directors'] = $this->groups_m->get('*', array('type' => 'director', 'parent_id' => 0), FALSE, 'weight ASC');
            } elseif ($row->type == 'video')
            {
                $this->data['action'] = 'video';
                $top_node = $this->groups_m->get('name', array('id' => $row->parent_id), 1);
                $this->data['current_director'] = $top_node->name;
                $this->data['parent_id'] = $row->parent_id;
                $this->data['videos'] = $this->groups_m->get('*', array('type' => 'video', 'parent_id' => $row->parent_id), FALSE, 'weight DESC');
            } else
            {
                $this->session->set_flashdata('error', $this->error_msg);
                redirect('dashboard/video', 'refresh');
            }

            $this->data['page'] = 'dashboard/video_v';
            $this->data['row'] = $row;

            $this->load->view('dashboard/template', $this->data);
        }
    }

    public function open($id)
    {
        $this->data['page'] = 'dashboard/video_v';
        $this->data['action'] = 'video';
        $this->data['select'] = 'video';

        $this->data['videos'] = $this->groups_m->get('*', array('type' => 'video', 'parent_id' => $id), FALSE, 'weight DESC');
        $w = $this->groups_m->get('IFNULL(MAX(weight),0)+10 weight,name', array('type' => 'video', 'parent_id' => $id), 1);
        $this->data['weight'] = $w->weight;

        $dir = $this->groups_m->get('name', array('type' => 'director', 'id' => $id), 1);
        $this->data['current_director'] = $dir->name;
        $this->data['parent_id'] = $id;
        $this->load->view('dashboard/template', $this->data);
    }

    public function delete($id)
    {
        $deletable = $this->groups_m->get('id', array('parent_id' => $id), 1);
        $row = $this->groups_m->get('*', array('id' => $id), 1);
        if ($row == FALSE)
        {
            $this->session->set_flashdata('error', $this->error_msg);
            redirect('dashboard/video', 'refresh');
        } else
        {
            if ($row->type == 'director')
            {
                $redirect_url = 'dashboard/video';
            } else
            {
                $redirect_url = 'dashboard/video/open/' . $row->parent_id;
            }
        }

        if ($deletable == FALSE)
        {
            //is deletable
            $this->delete_image($row->id, FALSE);
            $this->groups_m->delete(array('id' => $row->id));

            $this->session->set_flashdata('success', 'Item Deleted.');

            redirect($redirect_url, 'refresh');
        } else
        {
            $this->session->set_flashdata('error', $this->error_msg);
            redirect($redirect_url, 'refresh');
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
            redirect("dashboard/video/open/" . $row->parent_id, "refresh");
        }
        return TRUE;
    }

}
