<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop_model extends CI_Model {
    private $products = array();

    public function __construct() {
        parent::__construct();
        $this->config->load('shop_products', TRUE);
        $this->products = $this->config->item('shop_products', 'shop_products');
    }

    public function catalog() { return $this->products; }

    public function ensure_tables() {
        if ($this->db->table_exists('shop_orders') && $this->db->table_exists('shop_order_items')) return;
        $this->load->dbforge();
        if (!$this->db->table_exists('shop_orders')) {
            $this->dbforge->add_field(array(
                'id' => array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE,'auto_increment'=>TRUE),
                'customer_id' => array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE),
                'total' => array('type'=>'DECIMAL','constraint'=>'12,2','default'=>0),
                'status' => array('type'=>'VARCHAR','constraint'=>30,'default'=>'New'),
                'service_address' => array('type'=>'VARCHAR','constraint'=>255),
                'contact_phone' => array('type'=>'VARCHAR','constraint'=>30),
                'notes' => array('type'=>'TEXT','null'=>TRUE),
                'created_at' => array('type'=>'DATETIME')
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->create_table('shop_orders', TRUE);
        }
        if (!$this->db->table_exists('shop_order_items')) {
            $this->dbforge->add_field(array(
                'id' => array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE,'auto_increment'=>TRUE),
                'order_id' => array('type'=>'INT','constraint'=>10,'unsigned'=>TRUE),
                'product_id' => array('type'=>'VARCHAR','constraint'=>80),
                'product_name' => array('type'=>'VARCHAR','constraint'=>160),
                'quantity' => array('type'=>'INT','constraint'=>5,'unsigned'=>TRUE),
                'unit_price' => array('type'=>'DECIMAL','constraint'=>'12,2'),
                'line_total' => array('type'=>'DECIMAL','constraint'=>'12,2')
            ));
            $this->dbforge->add_key('id', TRUE);
            $this->dbforge->add_key('order_id');
            $this->dbforge->create_table('shop_order_items', TRUE);
        }
    }

    public function place_order($customer_id, $cart, $details) {
        $this->ensure_tables();
        $items = array(); $total = 0;
        foreach ($cart as $id => $qty) {
            if (!isset($this->products[$id])) continue;
            $qty = max(1, min(10, (int)$qty)); $product = $this->products[$id];
            $line = round($product['price'] * $qty, 2); $total += $line;
            $items[] = array('product_id'=>$id,'product_name'=>$product['name'],'quantity'=>$qty,'unit_price'=>$product['price'],'line_total'=>$line);
        }
        if (!$items) return FALSE;
        $this->db->trans_start();
        $this->db->insert('shop_orders', array('customer_id'=>(int)$customer_id,'total'=>$total,'status'=>'New','service_address'=>$details['service_address'],'contact_phone'=>$details['contact_phone'],'notes'=>$details['notes'] ?: NULL,'created_at'=>date('Y-m-d H:i:s')));
        $order_id = (int)$this->db->insert_id();
        foreach ($items as $item) { $item['order_id'] = $order_id; $this->db->insert('shop_order_items', $item); }
        $this->db->trans_complete();
        return $this->db->trans_status() ? $order_id : FALSE;
    }

    public function customer_orders($customer_id) {
        $this->ensure_tables();
        return $this->db->where('customer_id',(int)$customer_id)->order_by('created_at','DESC')->get('shop_orders')->result_array();
    }

    public function all_orders() {
        $this->ensure_tables();
        return $this->db->select('shop_orders.*, users.first_name, users.last_name, users.email')
            ->from('shop_orders')->join('users','users.id = shop_orders.customer_id','left')
            ->order_by('shop_orders.created_at','DESC')->get()->result_array();
    }

    public function find_order($id) {
        $this->ensure_tables();
        return $this->db->where('id',(int)$id)->get('shop_orders')->row_array();
    }

    public function order_items($id) {
        $this->ensure_tables();
        return $this->db->where('order_id',(int)$id)->get('shop_order_items')->result_array();
    }
}
