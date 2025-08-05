<?php
require_once 'BaseController.php';
class Medicine extends BaseController {

    public function __construct() {
        parent::__construct();
        // Load necessary models and libraries
        $this->load->model('Office_model');
        $this->load->model('PPE_model');
        $this->load->model('Medicine_model');
        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('url');
    }

    public function index() {
        // Load the necessary models
        $this->load->model('Medicine_model');
        $data['medicines'] = $this->Medicine_model->get_all_medicine();
        $data['office_items'] = $this->Office_model->get_item_codes();
        $data['medicine_items'] = $this->Medicine_model->get_item_codes();
        $data['ppe_items'] = $this->PPE_model->get_item_codes();
        $this->load->view('templates/header');
        $this->load->view('templates/reports_modal', $data);
        $this->load->view('medicine/index', $data);
        $this->load->view('templates/medicine_modal');
        $this->load->view('templates/sweetalert');
        $this->load->view('templates/footer');
    }

    public function release() {
        $this->title = "Release Item";
        $this->load->model('Medicine_model');
        $this->load->view('templates/header');
        $this->load->view('office/release');
        $this->load->view('templates/modals');
        $this->load->view('templates/sweetalert');
        $this->load->view('templates/footer');
    }

    public function store() {
       // Get form inputs
        $barcode = $this->input->post('barcode');
        $purchased_date = $this->input->post('purchased_date');
        $entered_date = $this->input->post('entered_date');
        $expiry_date = $this->input->post('expiry_date');
        $transaction = $this->input->post('type');
        $item_code = $this->input->post('item_code');
        $discription = $this->input->post('discription');
        $brand = $this->input->post('brand');
        $supplier = $this->input->post('supplier');
        $name_person = $this->input->post('name_person');
        $type_person = $this->input->post('type_person');
        $unit = $this->input->post('unit');
        $quantity = $this->input->post('quantity');
        $entered_by = $this->input->post('entered_by');
        $unit_cost = $this->input->post('unit_cost');
        $total = $unit_cost * $quantity;
        $requesting_office = $this->input->post('requesting_office');
        $location = $this->input->post('location');

        // Generate batch number
        $item_abbr = strtoupper(preg_replace('/[aeiou]/i', '', substr($item_code, 0, 5))); // Remove vowels and limit to 5 characters
        $date_code = date('mdy'); // Get current date in MMDDYY format
        $batch_number = $item_abbr . $date_code; // Concatenate abbreviation and date

        // Data array
        $data = [
            'barcode' => $barcode,
            'purchased_date' => $purchased_date,
            'entered_date' => $entered_date,
            'expiry_date' => $expiry_date,
            'type' => $transaction,
            'item_code' => $item_code,
            'discription' => $discription,
            'brand' => $brand,
            'supplier' => $supplier,
            'name_person' => $name_person,
            'type_person' => $type_person,
            'unit' => $unit,
            'quantity' => $quantity,
            'balance' => $quantity,
            'unit_cost' => $unit_cost,
            'total' => $total,
            'batch_number' => $batch_number, 
            'entered_by' => $entered_by,
            'total' => $total,
            'requesting_office'=> $requesting_office,
            'location'=> $location,
        ];

        $this->Medicine_model->insert_medicine($data);
        $this->session->set_flashdata('success', 'Item added successfully!');
        redirect('medicine');
    }


    public function get_item_by_barcode() {
        $barcode = $this->input->post('barcode');
    
        if (!$barcode) {
            echo json_encode(['status' => 'error', 'message' => 'Barcode is required']);
            return;
        }
    
        $items = $this->Medicine_model->get_item_by_barcode($barcode);
    
        if ($items) {
            echo json_encode(['status' => 'success', 'data' => $items]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Item not found']);
        }
    }

    // for notif
    public function get_low_stock_items() {
        $this->db->where('quantity <', 10);
        $query = $this->db->get('inv_medicines'); // Change 'inv_medicines' to your actual inventory table
        echo json_encode($query->result());
    }
    


    public function delete_issuance() {
        $id = $this->input->post('id');
        $type = $this->input->post('type');
        $item_code = $this->input->post('item_code');
        $batch_number = $this->input->post('batch_number');
        $quantity = (int) $this->input->post('quantity');
    
        $issuance = $this->db->get_where('inv_medicines', ['id' => $id])->row();
    
        if (!$issuance) {
            echo json_encode(['status' => 'error', 'message' => 'Something went wrong. Try again!']);
            return;
        }
    
        $this->db->trans_start();
    
        if ($type == 'Purchase' || $type == 'Donation') {
            // 🔹 Delete all transactions with the same item_code
            $this->db->where('item_code', $item_code);
            $this->db->delete('inv_medicines');
        } else if ($type == 'Issuance' || $type == 'Disposal') {
            // 🔹 Restore inventory balance
            $this->db->set('balance', 'balance + ' . $quantity, FALSE);
            $this->db->where('item_code', $item_code);
            $this->db->where('batch_number', $batch_number);
            $this->db->update('inv_medicines');
    
            // 🔹 Delete only the specific issuance record
            $this->db->delete('inv_medicines', ['id' => $id]);
        }
    
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to delete item!']);
        } else {
            echo json_encode(['status' => 'success', 'message' => 'Item deleted successfully!']);
        }
    }
    
    
    


    public function save_transaction() {
        $this->load->model('Medicine_model'); // Load model
    
        $barcode = $this->input->post('barcode', true);
        $quantity = $this->input->post('quantity', true);
        $type = $this->input->post('type', true);
    
        if (!$barcode || !$quantity || !$type) {
            echo json_encode(['status' => 'error', 'message' => 'Required fields are missing!']);
            return;
        }
    
        // Get current balance from database
        $current_balance = $this->Medicine_model->get_balance_by_barcode($barcode);
    
        // Handle Issuance and Disposal
        if ($type === 'Issuance' || $type === 'Disposal') {
            if ($current_balance < $quantity) {
                echo json_encode(['status' => 'error', 'message' => 'Insufficient stock for ' . $type . '!']);
                return;
            }
    
            // Deduct quantity from balance
            $new_balance = $current_balance - $quantity;
            $this->Medicine_model->update_balance($barcode, $new_balance);
        }
    
        // Prepare transaction data
        $data = [
            'barcode' => $barcode,
            'item_code' => $this->input->post('item', true),
            'unit' => $this->input->post('unit', true),
            'discription' => $this->input->post('discription', true), // Fixed typo
            'brand' => $this->input->post('brand', true),
            'supplier' => $this->input->post('supplier', true),
            'requesting_office' => $this->input->post('requesting_office', true),
            'name_person' => $this->input->post('name_person', true),
            'type_person' => $this->input->post('type_person', true),
            'quantity' => $quantity,
            'unit_cost' => $this->input->post('unit_cost', true),
            'total' => $this->input->post('total', true),
            'batch_number' => $this->input->post('batch_number', true),
            'type' => $type,
            'entered_by' => $this->input->post('entered_by', true),
            'entered_date' => $this->input->post('entered_date', true),
            'location' => $this->input->post('location', true)
        ];
    
        // Save transaction
        $insert = $this->Medicine_model->save_transaction($data);
    
        if ($insert) {
            echo json_encode(['status' => 'success', 'message' => $type . ' transaction saved successfully!']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Failed to save ' . $type . ' transaction!']);
        }
    }
    
    
    
    public function update_transaction() {
        $id = $this->input->post('id');
        $new_quantity = (int) $this->input->post('quantity');
        $type = $this->input->post('type');
        $unit_cost = (float) $this->input->post('unit_cost');
    
        // Get the existing transaction record
        $this->db->from('inv_medicines');
        $this->db->where('id', $id);
        $query = $this->db->get();
        $current_data = $query->row_array();
    
        if (!$current_data) {
            echo json_encode(['status' => 'error', 'message' => 'Transaction not found!']);
            return;
        }
    
        $old_quantity = (int) $current_data['quantity'];
        $item_code = $current_data['item_code'];
    
        // 🔹 If type is Issuance or Disposal, do not update its balance (keep it 0)
        if ($type == 'Issuance' || $type == 'Disposal') {
            // Calculate the difference in quantity
            $quantity_difference = $new_quantity - $old_quantity; // (+) if issuing more, (-) if reducing issuance
    
            // Get the **earliest** Purchase/Donation entry (FIFO method)
            $this->db->from('inv_medicines');
            $this->db->where('item_code', $item_code);
            $this->db->where_in('type', ['Purchase', 'Donation']);
            $this->db->where('balance >', 0); // Only consider entries with remaining balance
            $this->db->order_by('id', 'ASC'); // Get the oldest stock first
            $query = $this->db->get();
            $stock_entry = $query->row_array();
    
            if (!$stock_entry) {
                echo json_encode(['status' => 'error', 'message' => 'No available stock to adjust!']);
                return;
            }
    
            $previous_balance = (int) $stock_entry['balance'];
            $previous_id = $stock_entry['id'];
    
            // Calculate new balance for the previous entry
            $new_previous_balance = $previous_balance - $quantity_difference;
    
            if ($new_previous_balance < 0) {
                echo json_encode(['status' => 'error', 'message' => 'Insufficient stock in previous entry!']);
                return;
            }
    
            // Use a transaction for safe updates
            $this->db->trans_start();
    
            // Update the previous stock-in entry balance
            $this->db->where('id', $previous_id);
            $this->db->update('inv_medicines', ['balance' => $new_previous_balance]);
    
            // Keep the current transaction balance as **zero**
            $this->db->where('id', $id);
            $this->db->update('inv_medicines', [
                'quantity' => $new_quantity,
                'balance' => 0,
                'unit_cost' => $unit_cost,
                'total' => $new_quantity * $unit_cost
            ]);
    
            $this->db->trans_complete();
    
            

            if ($this->db->trans_status() === FALSE) {
                echo json_encode(['status' => 'error', 'message' => 'Failed to update item!']);
            } else {
                echo json_encode(['status' => 'success', 'message' => 'Item update successfully!']);
            }
    
            return;
        }
    
        // 🔹 For Purchase/Donation: Update its own balance normally
        $new_balance = $new_quantity;
        $new_total = $new_quantity * $unit_cost;
    
        // Update transaction
        $this->db->trans_start();
        $this->db->where('id', $id);
        $this->db->update('inv_medicines', [
            'quantity' => $new_quantity,
            'balance' => $new_balance,
            'unit_cost' => $unit_cost,
            'total' => $new_total
        ]);
        $this->db->trans_complete();
    
        if ($this->db->trans_status() === FALSE) {
            echo json_encode(['status' => 'error', 'message' => 'Failed to update transaction']);
        } else {
            echo json_encode(['status' => 'success', 'message' => 'Transaction updated successfully']);
        }
    }
    
    
    
    
    
    

    public function add_office() {
        $this->load->model('Medicine_model');

        $data = array(
            'barcode' => $this->input->post('barcode'),
            'purchased_date' => $this->input->post('purchased_date'),
            'entered_date' => $this->input->post('entered_date'),
            'transaction' => $this->input->post('transaction'),
            'item_code' => $this->input->post('item_code'),
            'discription' => $this->input->post('discription'),
            'brand' => $this->input->post('brand'),
            'supplier' => $this->input->post('supplier'),
            'name_person' => $this->input->post('name_person'),
            'type_person' => $this->input->post('type_person'),
            'unit' => $this->input->post('unit'),
            'quantity' => $this->input->post('quantity'),
            'ammount' => $this->input->post('ammount'),
        );

        if ($this->Medicine_model->insert_office($data)) {
            $this->session->set_flashdata('success', 'Data saved successfully!');
        } else {
            $this->session->set_flashdata('error', 'Failed to save data. Please try again.');
        }
        
        redirect('office');
    }

}