<?php
class ControllerPaymentCoinfide extends Controller {
	public function index() {
		$this->load->language('payment/coinfide');

		$data['button_confirm'] = $this->language->get('button_confirm');

		$data['action'] = $this->url->link('payment/coinfide/checkout', '', true);

        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/coinfide.tpl')) {
            return $this->load->view($this->config->get('config_template') . '/template/payment/coinfide.tpl', $data);
        } else {
            return $this->load->view('default/template/payment/coinfide.tpl', $data);
        }
	}

	public function checkout() {

		$this->load->model('checkout/order');
		$this->load->model('account/order');
        $this->load->model('localisation/country');
		$this->load->model('payment/coinfide');

		$order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $class = 'Coinfide\\Client';
//        $client = new Coinfide\Client(array('trace'=>true));
        if (class_exists($class)){
//            $client = new $class()
//            $client = new Coinfide\Client(array('trace'=>true));
            $client = new Coinfide\Client();
        }  else {
             throw new \Exception('Error: load Coinfide\Client!');
         }
//        echo $client;
        $client->setMode($this->config->get('coinfide_environment'));
        $client->setCredentials($this->config->get('coinfide_api_username'),$this->config->get('coinfide_api_password'));


//		$order_total = number_format($order_info['total'], 2);

		        //order
                $corder = new Coinfide\Entity\Order();
                //seller
                $seller = new Coinfide\Entity\Account();
                $seller->setEmail($this->config->get('coinfide_username'));
                $corder->setSeller($seller);

                //buyer
                $buyer = new Coinfide\Entity\Account();
                $buyer->setEmail($order_info['email']);
                $buyer->setName($order_info['payment_firstname']);
                $buyer->setSurname($order_info['payment_lastname']);
                $phone = new Coinfide\Entity\Phone();
                $phone ->setFullNumber((string)$order_info['telephone']);
//                $buyer->setPhone($phone);
        $baddress = new \Coinfide\Entity\Address();
        $baddress->setCity($order_info['payment_city']);
        $baddress->setFirstAddressLine($order_info['payment_address_1']);
        $baddress->setPostalCode($order_info['payment_postcode']);

        $payment_country = $this->model_localisation_country->getCountry($order_info['payment_country_id']);

        $baddress->setCountryCode($payment_country['iso_code_2']);
        $buyer->setAddress($baddress);
        $corder->setBuyer($buyer);


        //order misc
        if($this->config->get('coinfide_api_username')=='dLxMpCxBBymfulAu'){
            $corder->setCurrencyCode('TS1');
        }else {
            $corder->setCurrencyCode($order_info['currency_code']);
        }

        $corder->setExternalOrderId((string)($this->session->data['order_id']));
        $corder->setSuccessUrl($this->url->link('payment/coinfide/success'));
        $corder->setFailUrl($this->url->link('checkout/failure'));

        //items
		$ordered_products = $this->model_account_order->getOrderProducts($this->session->data['order_id']);
		foreach ($ordered_products as $product) {
//		    echo implode($product);
            $citem = new \Coinfide\Entity\OrderItem();
            $citem->setName($product['name'] ?: 'unknown');
            $citem->setType('I');
            $citem->setQuantity($product['quantity']);

            $citem->setPriceUnit($this->currency->format($product['price'], $order_info['currency_code'], $order_info['currency_value'], false));
            $corder->addOrderItem($citem);
        }

        $this->load->model('extension/extension');
        $order_data = array();
        $totals = array();
        $taxes = $this->cart->getTaxes();
        $total = 0;
        $results = $this->model_extension_extension->getExtensions('total');

        foreach ($results as $result) {
            if ($this->config->get($result['code'] . '_status')) {
                $this->load->model('total/' . $result['code']);

                // We have to put the totals in an array so that they pass by reference.
                $this->{'model_total_' . $result['code']}->getTotal($order_data['totals'], $total, $taxes);
            }
        }
        foreach ($order_data['totals'] as $total) {
            if (strstr(strtolower($total['code']), 'total') === false) {
                $citem = new \Coinfide\Entity\OrderItem();
                $citem->setName($total['title'] ?: 'unknown');
                $citem->setType('S');
                $citem->setQuantity(1);
                $citem->setPriceUnit($this->currency->format($total['value'], $order_info['currency_code'], $order_info['currency_value'], false));
                $corder->addOrderItem($citem);
            }
        }

//        echo $corder;
        $corder->validate();

        $response_data = $client->submitOrder($corder);

		$this->model_payment_coinfide->logger($corder);
		$this->model_payment_coinfide->addCoinfideOrder($order_info);

        $this->response->redirect($response_data->getRedirectUrl());
	}

	public function success() {
        $this->load->model('payment/coinfide');
        $this->load->model('checkout/order');

		$post = $this->request->post;
        $order_id = $this->request->post['externalOrderId'];

        $checksum = $this->request->post['checksum'];

        unset($post['checksum']);

        $order_info = $this->model_checkout_order->getOrder($order_id);
        if ($order_info) {

        if (md5(http_build_query($post) . $this->config->get('coinfide_secret')) == $checksum) {
//            echo 'Callback valid! You may process the order. Order data: ';
            $this->model_payment_coinfide->logger('Callback valid! You may process the order. Order data: '.serialize($this->request->post));

			if (isset($this->request->post['transactionUid'])) {
				$coinfide_transaction_id = $this->request->post['transactionUid'];
			} else {
				$coinfide_transaction_id = '';
			}

				$coinfide_order_info = $this->model_payment_coinfide->getCoinfideOrder($order_id);

				$this->model_payment_coinfide->updateOrder($coinfide_order_info['coinfide_order_id'], $coinfide_transaction_id, 'payment', $order_info);

				$this->model_checkout_order->addOrderHistory($order_id, $this->config->get('coinfide_order_status_id'));
            $this->response->redirect($this->url->link('checkout/success'));
			}else{
                $this->model_checkout_order->addOrderHistory($order_id, $this->config->get('coinfide_cancelled_status_id'));
            $this->response->redirect($this->url->link('checkout/failure', '', true));
        }

		}
//        echo 'Callback invalid!';
        $this->response->redirect($this->url->link('checkout/failure', '', true));
	}


    public function ipn() {
        $this->load->model('payment/coinfide');
        $this->model_payment_coinfide->logger('ipn');

//        $order_id = $this->request->post['externalOrderId'];
//        if (isset($this->request->get['checksum']) && hash_equals($this->config->get('coinfide_secret_token'), $this->request->get['token'])) {
//            $this->model_payment_coinfide->logger('token success');

//            if (isset($this->request->post['externalOrderId'])) {
//                $order_info = $this->model_checkout_order->getOrder($order_id);

//                $string = $coinfide_order['coinfide_transaction_id'] . $coinfide_order['order_id'] . round($coinfide_order['total'], 2) . html_entity_decode($this->config->get('coinfide_secret'));
//                $hash = hash('sha256', $string);
//                if($hash != $this->request->post['hash']){
//                    $this->model_payment_coinfide->logger('Hashes do not match, possible tampering!');
//                    return;
//                }

//                switch ($this->request->post['status']) {
//                    case 'complete':
//                        $order_status_id = $this->config->get('coinfide_complete_status_id');
//                        break;
//                    case 'rejected':
//                        $order_status_id = $this->config->get('coinfide_rejected_status_id');
//                        break;
//                    case 'canceled':
//                        $order_status_id = $this->config->get('coinfide_cancelled_status_id');
//                        break;
//                    case 'partial_refunded':
//                        $order_status_id = $this->config->get('coinfide_partially_refunded_status_id');
//                        break;
//                    case 'refunded':
//                        $order_status_id = $this->config->get('coinfide_refunded_status_id');
//                        break;
//                }

//                $this->load->model('checkout/order');
//                $this->model_checkout_order->addOrderHistory($order_id, $order_status_id);
//            }
//        }
    }
}