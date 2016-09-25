<?php

class MY_Model extends CI_Model
{

    public function save($data, $where=FALSE)
    {
        if ($where == FALSE)
        { //Insert
            $this->db->set("updated_on", "now()", FALSE);
            $this->db->set("created_on", "now()", FALSE);
            $this->db->insert($this->table, $data);
            return $this->db->insert_id();
        } else
        { //Update
            $this->db->where($where);
            $this->db->set("updated_on", "now()", FALSE);
            $this->db->update($this->table, $data);
            return $this->db->affected_rows();
        }
    }

    public function get($column = '*', $where = FALSE, $limit = FALSE, $order = FALSE)
    {
        $this->db->select($column,false);
        if ($where != FALSE)
        {
            $this->db->where($where);
        }
        if ($limit != FALSE)
        {
            $this->db->limit($limit);
        }
        if ($order != FALSE)
        {
            $this->db->order_by($order);
        } else
        {
            $this->db->order_by('id', $this->order);
        }

        $this->db->from($this->table, false);
        $query = $this->db->get();
        if ($limit == 1)
        {
            $query = $query->row();
        }
        return $query;
    }

    public function delete($where)
    {
        $this->db->where($where);
        $this->db->delete($this->table);
        return $this->db->affected_rows();
    }


}