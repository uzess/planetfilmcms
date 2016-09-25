<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Admin_Controller extends MY_Controller {

    protected $update_msg = 'Updated Successfully';
    protected $save_msg = 'Saved Successfully';
    protected $error_msg = 'Operation Failed.';
    
    public function __construct()
    {
        parent::__construct();
        $this->data['select'] = 'setting';
        if ($this->session->userdata('logged_in') != TRUE)
        {
            redirect('dashboard/login', 'refresh');
        }
    }

    public function get_bread_crumb($id)
    {
        $list = array();
        $str = '';
        $this->gen_bread_crumb($id, $list);
        for ($i = count($list) - 1; $i >= 0; $i--)
        {
            $str .= $list[$i];
        }
        return $str;
    }

    public function gen_bread_crumb($id, &$list)
    {

        $row = $this->groups_m->get('*', array("id" => $id), 1);
        if ($row != FALSE)
        {
            $list[] = "<li><a  href='dashboard/manage_content/view/" . $row->section . '/' . $row->id . "'>" . $row->name . "</a></li>";

            $this->gen_bread_crumb($row->parent_id, $list);
        }
    }

    public function get_link_list($section, $parent_id, $selected_id, $times)
    {
        $store = '';
        $this->comboRecursion($section, $parent_id, $selected_id, $times, $store);
        return $store;
    }

    public function comboRecursion($section, $parent_id, $selected_id, $times, &$store)
    {
        $query = $this->groups_m->get('*', array('parent_id' => $parent_id, "section" => $section), NULL, 'weight ASC');

        foreach ($query->result() as $row)
        {
            $spaces = $this->spaces($times);
            if ($row->type != "Gallery Sub" && $row->type != "Video Sub")
            {
                if ($row->type == "Normal Group")
                {
                    $store .= "<option value='" . $row->id . "'";
                    if ($row->id == $selected_id)
                        $store .= " selected ";
                }
                else
                {
                    $store .= "<optgroup label='" . $spaces . $row->name . "'";
                }
                $store .= ">";
                $store .= $spaces . $row->name;
                if ($row->type != "Normal Group")
                    $store .= "</optgroup>";
                else
                    $store .= "</option>";
            }
            $this->comboRecursion($section, (int) $row->id, $selected_id, ++$times, $store);
            --$times;
        }
    }

    public function spaces($times)
    {
        $spaces = "";
        for ($i = 0; $i < $times; $i++)
            $spaces .= "--";

        return $spaces;
    }

}
