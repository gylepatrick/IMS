<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Medicine_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function get_inventory() {
        $this->db->order_by('barcode', 'ASC');
        $query = $this->db->get('inv_medicines');
        return $query->result();
    }

    public function get_all_medicine() {
        $this->db->order_by('item_code', 'ASC');
        $query = $this->db->get('inv_medicines');
        return $query->result();
    }
    

    public function insert_medicine($data) {
        return $this->db->insert('inv_medicines', $data);
    }

    public function get_inventory_medicine($item_code = null, $date_from = null, $date_to = null) {
        $this->db->select('*');
        $this->db->from('inv_medicines');
    
        if (!empty($item_code)) { 
            $this->db->where('item_code', $item_code);
        }
    
        if (!empty($date_from) && !empty($date_to)) {
            $this->db->where('entered_date >=', $date_from);
            $this->db->where('entered_date <=', $date_to);
        }
    
        // Add this line to sort by item_code (or barcode column)
        $this->db->order_by('item_code', 'ASC'); // Change 'ASC' to 'DESC' for descending order
        return $this->db->get()->result();
    }
    
    

    public function get_item_codes() {
        $this->db->distinct();
        $this->db->select('item_code'); // Select only item_code
        $this->db->from('inv_medicines'); // Adjust table name if needed
        $this->db->where_in('type', ['Purchase', 'Donation']); // Filter by type
        $this->db->group_by('item_code'); // Group by item_code
        $query = $this->db->get();
        return $query->result();
    }
    
    

    public function get_balance_by_barcode($barcode) {
        $this->db->select('balance');
        $this->db->from('inv_medicines');
        $this->db->where('barcode', $barcode);
        $query = $this->db->get();
        $result = $query->row();
        return $result ? $result->balance : 0;
    }

    public function update_balance($barcode, $new_balance) {
        $this->db->where('barcode', $barcode);
        $this->db->update('inv_medicines', ['balance' => $new_balance]);
    }
    
    public function save_transaction($data) {
        return $this->db->insert('inv_medicines', $data);
    }
    
    public function get_item_by_barcode($barcode) {
        $this->db->select('id, barcode, item_code, quantity, balance, unit_cost, unit, total, type, batch_number, supplier, brand, discription');
        $this->db->from('inv_medicines');
        $this->db->where('barcode', $barcode);
        $query = $this->db->get();
        
        return $query->row(); // Return single result
    }
    
}