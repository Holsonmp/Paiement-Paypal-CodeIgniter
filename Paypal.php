<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Cart_controller extends Home_Core_Controller
{
    public function paypal_payment_post()
    {
        $payment_id = $this->input->post('payment_id', true);
        $this->load->library('paypal');

        //Commande valide
        if ($this->paypal->get_order($payment_id)) {
            $data_transaction = array(
                'payment_method' => "PayPal",
                'payment_id' => $payment_id,
                'currency' => $this->input->post('currency', true),
                'payment_amount' => $this->input->post('payment_amount', true),
                'payment_status' => $this->input->post('payment_status', true),
            );

            $sombalt_payment_type = $this->input->post('sombalt_payment_type', true);
            if ($sombalt_payment_type == 'sale') {
                //execute sale payment
                $this->execute_sale_payment($data_transaction, 'json_encode');
            } elseif ($sombalt_payment_type == 'promote') {
                //execute promote payment
                $this->execute_promote_payment($data_transaction, 'json_encode');
            }
        } else {
            $this->session->set_flashdata('error', trans("msg_error"));
            $data = array(
                'status' => 0,
                'redirect' => generate_url("cart", "payment")
            );
            echo json_encode($data);
        }
    }
}
?>