<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Client extends Client_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->data['director_list'] = $this->groups_m->get('*', array('parent_id' => 0, 'type' => 'director', 'status' => 1), FALSE, 'weight ASC');
        $this->data['menu'] = $this->groups_m->get('*', array('parent_id' => 0, 'section' => 'Menu', 'status' => 1), FALSE, 'weight ASC');
        $menu_name_id = $this->config->item('menu_name_id');
        $this->data['menu_names'] = $this->groups_m->get('*',array('parent_id'=>$menu_name_id),FALSE,'weight desc');
    }

    public function index($slug = NULL)
    {
        $row = $this->groups_m->get('*', array('slug' => $slug), 1);
        $this->data['row'] = $row;
        $this->data['page'] = 'index';
        if ($row != FALSE)
        {
            if (!empty($row->meta_title))
            {
                $this->data['setting']->meta_title = $row->meta_title;
            }

            if (!empty($row->meta_keyword))
            {
                $this->data['setting']->meta_keyword = $row->meta_keyword;
            }

            if (!empty($row->meta_description))
            {
                $this->data['setting']->meta_description = $row->meta_description;
            }

            $this->data['inner_rows'] = $this->groups_m->get('*', array('parent_id' => $row->id), FALSE, 'weight ASC');
        }

        if ($slug == NULL)
        {
            $this->data['videos'] = $this->groups_m->get('*', array('show_in_homepage' => 1, 'type' => 'video'), FALSE, 'home_weight ASC');
          // var_dump($this->data['videos']->result()); die();
            $this->data['news_row'] = $this->groups_m->get('*', array('section' => 'News', 'parent_id' => 0), 1, 'id DESC');
        }
        

        $this->load->view('client/template', $this->data);
    }

    public function directors($slug = NULL)
    {
        $director = $this->groups_m->get('*', array('slug' => $slug), 1);
        $this->data['nav_selected'] = 'director';
        if ($director == FALSE)
        {
            redirect('', 'refresh');
        }

        $id = $director->id;
        $this->data['name'] = $director->name;
        $this->data['content'] = $director->content;
        $this->data['videos'] = $this->groups_m->get('*', array('parent_id' => $id, 'type' => 'video'), FALSE, 'weight DESC');

        $this->data['page'] = 'director';

        $this->load->view('client/template', $this->data);
    }

    public function services()
    {
//        $director = $this->groups_m->get('*', array('slug' => $slug), 1);
        $this->data['nav_selected'] = 'services';
       
        $this->data['result'] = $this->groups_m->get('*',array('section'=>'Production'));
      
        $this->data['page'] = 'services';

        $this->load->view('client/template', $this->data);
    }

    public function getVimeoImage($id, $size = "midium")
    {
        $hash = unserialize(file_get_contents("http://vimeo.com/api/v2/video/$id.php"));
        if ($size == 'large')
        {
            $img = $hash[0]['thumbnail_large'];
        } elseif ($size == 'small')
        {
            $img = $hash[0]['thumbnail_small'];
        } else
        {
            $img = $hash[0]['thumbnail_medium'];
        }
        return $img;
    }

    public function features()
    {
        $this->data['nav_selected'] = 'feature';
        $this->data['page'] = 'features';
        $this->data['features'] = $this->groups_m->get('*', array('show_in_feature' => 1, 'type' => 'video'), FALSE, 'feature_weight ASC');
        $this->load->view('client/template', $this->data);
    }

    public function news()
    {
        $this->data['page'] = 'news';
        $this->data['news'] = $this->groups_m->get('*', array('section' => 'News', 'parent_id' => 0), FALSE, 'id DESC');
        $this->load->view('client/template', $this->data);
    }

}
