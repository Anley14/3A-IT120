<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Shop extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Shop_model', 'shop');
        $this->shop->ensure_tables();
    }

    public function index() {
        $this->load->view('shop_index', array('products'=>$this->shop->catalog(),'cart'=>$this->cart_data(),'success'=>$this->session->flashdata('shop_success'),'error'=>$this->session->flashdata('shop_error')));
    }

    public function add_to_cart() {
        if ($this->input->method(TRUE) !== 'POST') { show_404(); return; }
        $id = $this->input->post('product_id', TRUE); $products = $this->shop->catalog();
        if (!isset($products[$id])) { $this->session->set_flashdata('shop_error','That product is not available.'); redirect('shop'); return; }
        $cart = $this->cart_data(); $cart[$id] = min(10, (isset($cart[$id]) ? (int)$cart[$id] : 0) + 1);
        $this->session->set_userdata('sunson_cart',$cart);
        $this->session->set_flashdata('shop_success',$products[$id]['name'].' added to your cart.'); redirect('shop');
    }

    public function cart() {
        $cart = $this->cart_data(); $this->load->view('shop_cart',array('cart'=>$cart,'products'=>$this->shop->catalog()));
    }

    public function update_cart() {
        if ($this->input->method(TRUE) !== 'POST') { show_404(); return; }
        $quantities = $this->input->post('quantity'); $cart = $this->cart_data(); $products = $this->shop->catalog();
        if (is_array($quantities)) foreach ($quantities as $id=>$qty) {
            if (!isset($cart[$id]) || !isset($products[$id])) continue;
            $qty = max(0,min(10,(int)$qty)); if ($qty === 0) unset($cart[$id]); else $cart[$id] = $qty;
        }
        $this->session->set_userdata('sunson_cart',$cart); redirect('shop/cart');
    }

    public function checkout() {
        if (!$this->require_customer()) return;
        $cart=$this->cart_data(); if (!$cart) { redirect('shop'); return; }
        $this->load->view('shop_checkout',array('cart'=>$cart,'products'=>$this->shop->catalog(),'error'=>$this->session->flashdata('shop_error')));
    }

    public function place_order() {
        if ($this->input->method(TRUE) !== 'POST') { show_404(); return; }
        if (!$this->require_customer()) return;
        $this->load->library('form_validation');
        $this->form_validation->set_rules('service_address','Service address','trim|required|max_length[255]');
        $this->form_validation->set_rules('contact_phone','Contact phone','trim|required|max_length[30]');
        $this->form_validation->set_rules('notes','Notes','trim|max_length[1000]');
        $cart=$this->cart_data();
        if (!$cart) { redirect('shop'); return; }
        if (!$this->form_validation->run()) { $this->session->set_flashdata('shop_error','Add a valid service address and phone number to continue.'); redirect('shop/checkout'); return; }
        $id=$this->shop->place_order((int)$this->session->userdata('sunson_account')['id'],$cart,array('service_address'=>trim($this->input->post('service_address',TRUE)),'contact_phone'=>trim($this->input->post('contact_phone',TRUE)),'notes'=>trim($this->input->post('notes',TRUE))));
        if (!$id) { $this->session->set_flashdata('shop_error','We could not save the order. Please try again.'); redirect('shop/checkout'); return; }
        $this->session->unset_userdata('sunson_cart'); $this->session->set_flashdata('shop_success','Your order request is in. Our team will confirm pricing and next steps.'); redirect('shop/order/'.$id);
    }

    public function order($id=0) {
        $account=$this->session->userdata('sunson_account');
        if (!is_array($account) || !isset($account['role'],$account['id'])) { redirect('auth/login/customer'); return; }
        $order=$this->shop->find_order((int)$id);
        if (!$order || ($account['role']==='customer' && (int)$order['customer_id']!==(int)$account['id']) || !in_array($account['role'],array('customer','admin'),TRUE)) { show_404(); return; }
        $this->load->view('shop_order',array('order'=>$order,'items'=>$this->shop->order_items($id),'success'=>$this->session->flashdata('shop_success')));
    }

    public function update_order_status() {
        $account=$this->session->userdata('sunson_account');
        if ($this->input->method(TRUE)!=='POST' || !is_array($account) || !isset($account['role']) || $account['role']!=='admin') { show_404(); return; }
        $id=(int)$this->input->post('order_id',TRUE); $status=$this->input->post('status',TRUE);
        if (!$this->shop->find_order($id) || !in_array($status,array('New','Confirmed','Scheduled','Completed','Cancelled'),TRUE)) { $this->session->set_flashdata('dashboard_error','Choose a valid order and status.'); redirect('auth/dashboard'); return; }
        $saved=$this->db->where('id',$id)->update('shop_orders',array('status'=>$status));
        $this->session->set_flashdata($saved?'dashboard_success':'dashboard_error',$saved?'Order status updated.':'The order could not be updated.'); redirect('auth/dashboard');
    }

    private function cart_data() { $cart=$this->session->userdata('sunson_cart'); return is_array($cart)?$cart:array(); }
    private function require_customer() {
        $account=$this->session->userdata('sunson_account');
        if (!is_array($account) || !isset($account['role'],$account['id']) || $account['role']!=='customer') { $this->session->set_flashdata('login_success','Sign in or create a customer account to place an order.'); redirect('auth/login/customer'); return FALSE; }
        return TRUE;
    }
}
