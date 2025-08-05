<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Office_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_inventory() {
        $this->db->order_by('barcode', 'ASC'); // Sort by barcode in ascending order
        $query = $this->db->get('inv_office'); // Adjust table name if necessary
        return $query->result();
    }

    public function get_all_office() {
        $this->db->order_by('item_code', 'ASC');
        $query = $this->db->get('inv_office');
        return $query->result();
    }
    

    public function insert_office($data) {
        return $this->db->insert('inv_office', $data);
    }

    public function get_inventory_office($item_code = null, $date_from = null, $date_to = null) {
        $this->db->select('*');
        $this->db->from('inv_office');
    
        if (!empty($item_code)) { 
            $this->db->where('item_code', $item_code);
        }
    
        if (!empty($date_from) && !empty($date_to)) {
            $this->db->where('entered_date >=', $date_from);
            $this->db->where('entered_date <=', $date_to);
        }
    
        $this->db->order_by('item_code', 'ASC');
    
        // ✅ Removed group_by() to get all records
        return $this->db->get()->result();
    }
    
    
    
    public function get_item_codes() {
        $this->db->distinct();
        $this->db->select('item_code');
        $this->db->from('inv_office'); // Adjust table name if needed
        $this->db->where_in('type', ['Purchase', 'Donation']); // Filter by type
        $this->db->group_by('item_code'); // Group by item_code
        $query = $this->db->get();
        return $query->result();
    }
    
    
    

    public function get_balance_by_barcode($barcode) {
        $this->db->select('balance');
        $this->db->from('inv_office');
        $this->db->where('barcode', $barcode);
        $query = $this->db->get();
        $result = $query->row();
        return $result ? $result->balance : 0;
    }

    public function update_balance($barcode, $new_balance) {
        $this->db->where('barcode', $barcode);
        $this->db->update('inv_office', ['balance' => $new_balance]);
    }
    
    public function save_transaction($data) {
        return $this->db->insert('inv_office', $data);
    }
    
    public function get_item_by_barcode($barcode) {
        $this->db->select('id, barcode, item_code, quantity, balance, unit_cost, unit, total, type, batch_number, supplier, brand, discription');
        $this->db->from('inv_office');
        $this->db->where('barcode', $barcode);
        $query = $this->db->get();
        
        return $query->row(); // Return single result
    }
    
}