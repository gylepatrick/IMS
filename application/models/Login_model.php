<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Login_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    // Check if the credentials are correct
    public function check_login($username, $password)
    {
        // Query the database to check the username and password
        $this->db->where('username', $username);
        $this->db->where('password', $password  );  // Assuming passwords are stored as MD5 hash
        $query = $this->db->get('users');  // Assuming 'users' is your table

        // If the query returns a row, return the user's data (including full name)
        if ($query->num_rows() > 0) {
            return $query->row();  // This will return an object with the user data
        } else {
            return false;
        }
    }
}
