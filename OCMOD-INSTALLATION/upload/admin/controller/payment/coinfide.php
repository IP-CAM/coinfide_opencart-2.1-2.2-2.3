<?php

class ControllerPaymentCoinfide extends Controller {

	private $error = array();

	public function index() {
		$this->load->language('payment/coinfide');

		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			$this->model_setting_setting->editSetting('coinfide', $this->request->post);

			$this->session->data['complete'] = $this->language->get('text_complete');

			$this->response->redirect($this->url->link('extension/payment', 'token=' . $this->session->data['token'], true));
		}

		$data['heading_title'] = $this->language->get('heading_title');

		$data['entry_username'] = $this->language->get('entry_username');
		$data['entry_api_username'] = $this->language->get('entry_api_username');
		$data['entry_api_password'] = $this->language->get('entry_api_password');
        $data['entry_secret'] = $this->language->get('entry_secret');
		$data['entry_environment'] = $this->language->get('entry_environment');
		$data['entry_total'] = $this->language->get('entry_total');
		$data['entry_order_status'] = $this->language->get('entry_order_status');
		$data['entry_geo_zone'] = $this->language->get('entry_geo_zone');
		$data['entry_status'] = $this->language->get('entry_status');
		$data['entry_sort_order'] = $this->language->get('entry_sort_order');
		$data['entry_debug'] = $this->language->get('entry_debug');

		$data['entry_complete_status'] = $this->language->get('entry_complete_status');
		$data['entry_rejected_status'] = $this->language->get('entry_rejected_status');
		$data['entry_cancelled_status'] = $this->language->get('entry_cancelled_status');
		$data['entry_pending_status'] = $this->language->get('entry_pending_status');
		$data['entry_refunded_status'] = $this->language->get('entry_refunded_status');
		$data['entry_partially_refunded_status'] = $this->language->get('entry_partially_refunded_status');

		$data['coinfide_environment_live'] = $this->language->get('coinfide_environment_live');
		$data['coinfide_environment_test'] = $this->language->get('coinfide_environment_test');

		$data['text_edit'] = $this->language->get('text_edit');
		$data['text_enabled'] = $this->language->get('text_enabled');
		$data['text_disabled'] = $this->language->get('text_disabled');
		$data['text_all_zones'] = $this->language->get('text_all_zones');

		$data['help_username'] = $this->language->get('help_username');
		$data['help_total'] = $this->language->get('help_total');
		$data['help_debug'] = $this->language->get('help_debug');

		$data['tab_settings'] = $this->language->get('tab_settings');
		$data['tab_order_status'] = $this->language->get('tab_order_status');

		$data['button_save'] = $this->language->get('button_save');
		$data['button_cancel'] = $this->language->get('button_cancel');

		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}

		if (isset($this->error['username'])) {
			$data['error_email'] = $this->error['username'];
		} else {
			$data['error_email'] = '';
		}

		if (isset($this->error['api_username'])) {
			$data['error_api_username'] = $this->error['api_username'];
		} else {
			$data['error_api_username'] = '';
		}

		if (isset($this->error['api_pass'])) {
			$data['error_api_pass'] = $this->error['api_pass'];
		} else {
			$data['error_api_pass'] = '';
		}

        if (isset($this->error['secret'])) {
            $data['error_secret'] = $this->error['secret'];
        } else {
            $data['error_secret'] = '';
        }

		if (isset($this->request->post['coinfide_order_status_id'])) {
			$data['coinfide_order_status_id'] = $this->request->post['coinfide_order_status_id'];
		} else {
			$data['coinfide_order_status_id'] = $this->config->get('coinfide_order_status_id');
		}

		if (isset($this->request->post['coinfide_complete_status_id'])) {
			$data['coinfide_complete_status_id'] = $this->request->post['coinfide_complete_status_id'];
		} else {
			$data['coinfide_complete_status_id'] = $this->config->get('coinfide_complete_status_id');
		}

		if (isset($this->request->post['coinfide_rejected_status_id'])) {
			$data['coinfide_rejected_status_id'] = $this->request->post['coinfide_rejected_status_id'];
		} else {
			$data['coinfide_rejected_status_id'] = $this->config->get('coinfide_rejected_status_id');
		}

		if (isset($this->request->post['coinfide_cancelled_status_id'])) {
			$data['coinfide_cancelled_status_id'] = $this->request->post['coinfide_cancelled_status_id'];
		} else {
			$data['coinfide_cancelled_status_id'] = $this->config->get('coinfide_cancelled_status_id');
		}

		if (isset($this->request->post['coinfide_pending_status_id'])) {
			$data['coinfide_pending_status_id'] = $this->request->post['coinfide_pending_status_id'];
		} else {
			$data['coinfide_pending_status_id'] = $this->config->get('coinfide_pending_status_id');
		}

		if (isset($this->request->post['coinfide_refunded_status_id'])) {
			$data['coinfide_refunded_status_id'] = $this->request->post['coinfide_refunded_status_id'];
		} else {
			$data['coinfide_refunded_status_id'] = $this->config->get('coinfide_refunded_status_id');
		}

		if (isset($this->request->post['coinfide_partially_refunded_status_id'])) {
			$data['coinfide_partially_refunded_status_id'] = $this->request->post['coinfide_partially_refunded_status_id'];
		} else {
			$data['coinfide_partially_refunded_status_id'] = $this->config->get('coinfide_partially_refunded_status_id');
		}

		$data['breadcrumbs'] = array();

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_payment'),
			'href' => $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true)
		);

		$data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('payment/coinfide', 'token=' . $this->session->data['token'], true)
		);

		$this->load->model('localisation/order_status');
		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$data['action'] = $this->url->link('payment/coinfide', 'token=' . $this->session->data['token'], true);

		$data['cancel'] = $this->url->link('extension/payment', 'token=' . $this->session->data['token'], true);

		if (isset($this->request->post['coinfide_username'])) {
			$data['coinfide_username'] = $this->request->post['coinfide_username'];
		} else {
			$data['coinfide_username'] = $this->config->get('coinfide_username');
		}

		if (isset($this->request->post['coinfide_api_username'])) {
			$data['coinfide_api_username'] = $this->request->post['coinfide_api_username'];
		} else {
			$data['coinfide_api_username'] = $this->config->get('coinfide_api_username');
		}

		if (isset($this->request->post['coinfide_api_password'])) {
			$data['coinfide_api_password'] = $this->request->post['coinfide_api_password'];
		} else {
			$data['coinfide_api_password'] = $this->config->get('coinfide_api_password');
		}

        if (isset($this->request->post['coinfide_secret'])) {
            $data['coinfide_secret'] = $this->request->post['coinfide_secret'];
        } else {
            $data['coinfide_secret'] = $this->config->get('coinfide_secret');
        }

		if (isset($this->request->post['coinfide_environment'])) {
			$data['coinfide_environment'] = $this->request->post['coinfide_environment'];
		} else {
			$data['coinfide_environment'] = $this->config->get('coinfide_environment');
		}

		if (isset($this->request->post['coinfide_total'])) {
			$data['coinfide_total'] = $this->request->post['coinfide_total'];
		} else {
			$data['coinfide_total'] = $this->config->get('coinfide_total');
		}

		if (isset($this->request->post['coinfide_order_status_id'])) {
			$data['coinfide_order_status_id'] = $this->request->post['coinfide_order_status_id'];
		} else {
			$data['coinfide_order_status_id'] = $this->config->get('coinfide_order_status_id');
		}

		$this->load->model('localisation/order_status');

		$data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		if (isset($this->request->post['coinfide_geo_zone_id'])) {
			$data['coinfide_geo_zone_id'] = $this->request->post['coinfide_geo_zone_id'];
		} else {
			$data['coinfide_geo_zone_id'] = $this->config->get('coinfide_geo_zone_id');
		}

		$this->load->model('localisation/geo_zone');

		$data['geo_zones'] = $this->model_localisation_geo_zone->getGeoZones();

		if (isset($this->request->post['coinfide_status'])) {
			$data['coinfide_status'] = $this->request->post['coinfide_status'];
		} else {
			$data['coinfide_status'] = $this->config->get('coinfide_status');
		}

		if (isset($this->request->post['coinfide_debug'])) {
			$data['coinfide_debug'] = $this->request->post['coinfide_debug'];
		} else {
			$data['coinfide_debug'] = $this->config->get('coinfide_debug');
		}

		if (isset($this->request->post['coinfide_sort_order'])) {
			$data['coinfide_sort_order'] = $this->request->post['coinfide_sort_order'];
		} else {
			$data['coinfide_sort_order'] = $this->config->get('coinfide_sort_order');
		}

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('payment/coinfide', $data));
	}

	public function order() {

		if ($this->config->get('coinfide_status')) {

			$this->load->model('payment/coinfide');

			$coinfide_order = $this->model_payment_coinfide->getOrder($this->request->get['order_id']);

			if (!empty($coinfide_order)) {
				$this->load->language('payment/coinfide');

				$coinfide_order['total_released'] = $this->model_payment_coinfide->getTotalReleased($coinfide_order['coinfide_order_id']);

				$coinfide_order['total_formatted'] = $this->currency->format($coinfide_order['total'], $coinfide_order['currency_code'], false);
				$coinfide_order['total_released_formatted'] = $this->currency->format($coinfide_order['total_released'], $coinfide_order['currency_code'], false);

				$data['coinfide_order'] = $coinfide_order;

				$data['text_payment_info'] = $this->language->get('text_payment_info');
				$data['text_order_ref'] = $this->language->get('text_order_ref');
				$data['text_order_total'] = $this->language->get('text_order_total');
				$data['text_total_released'] = $this->language->get('text_total_released');
				$data['text_refund_status'] = $this->language->get('text_refund_status');
				$data['text_transactions'] = $this->language->get('text_transactions');
				$data['text_yes'] = $this->language->get('text_yes');
				$data['text_no'] = $this->language->get('text_no');
				$data['text_column_amount'] = $this->language->get('text_column_amount');
				$data['text_column_type'] = $this->language->get('text_column_type');
				$data['text_column_date_added'] = $this->language->get('text_column_date_added');
				$data['btn_refund'] = $this->language->get('btn_refund');
				$data['text_confirm_refund'] = $this->language->get('text_confirm_refund');

				$data['order_id'] = $this->request->get['order_id'];
				$data['token'] = $this->request->get['token'];

				return $this->load->view('payment/coinfide_order', $data);
			}
		}
	}

	public function refund() {
		$this->load->language('payment/coinfide');
		$json = array();

		if (isset($this->request->post['order_id']) && !empty($this->request->post['order_id'])) {
			$this->load->model('payment/coinfide');

			$coinfide_order = $this->model_payment_coinfide->getOrder($this->request->post['order_id']);

//			$refund_response = $this->model_payment_coinfide->refund($coinfide_order, $this->request->post['amount']);   //todo???

			$this->model_payment_coinfide->logger($refund_response);

			if ($refund_response == 'ok') {
				$this->model_payment_coinfide->addTransaction($coinfide_order['coinfide_order_id'], 'refund', $this->request->post['amount'] * -1);

				$total_refunded = $this->model_payment_coinfide->getTotalRefunded($coinfide_order['coinfide_order_id']);
				$total_released = $this->model_payment_coinfide->getTotalReleased($coinfide_order['coinfide_order_id']);

				if ($total_released <= 0 && $coinfide_order['release_status'] == 1) {
					$this->model_payment_coinfide->updateRefundStatus($coinfide_order['coinfide_order_id'], 1);
					$refund_status = 1;
					$json['msg'] = $this->language->get('text_refund_ok_order');
				} else {
					$refund_status = 0;
					$json['msg'] = $this->language->get('text_refund_ok');
				}

				$json['data'] = array();
				$json['data']['date_added'] = date("Y-m-d H:i:s");
				$json['data']['amount'] = $this->currency->format(($this->request->post['amount'] * -1), $coinfide_order['currency_code'], false);
				$json['data']['total_released'] = (float)$total_released;
				$json['data']['total_refunded'] = (float)$total_refunded;
				$json['data']['refund_status'] = $refund_status;
				$json['error'] = false;
			} else {
				$json['error'] = true;
				$json['msg'] = 'Unable to refund: ' . $refund_response;
			}
		} else {
			$json['error'] = true;
			$json['msg'] = 'Missing data';
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install() {
		$this->load->model('payment/coinfide');
		$this->model_payment_coinfide->install();
	}

	public function uninstall() {
		$this->load->model('payment/coinfide');
		$this->model_payment_coinfide->uninstall();
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'payment/coinfide')) {
			$this->error['warning'] = $this->language->get('error_permission');
		}

		if (!$this->request->post['coinfide_username']) {
			$this->error['username'] = $this->language->get('error_email');
		}

		if (!$this->request->post['coinfide_api_username']) {
			$this->error['api_username'] = $this->language->get('error_api_username');
		}

		if (!$this->request->post['coinfide_api_password']) {
			$this->error['api_pass'] = $this->language->get('error_api_pass');
		}

        if (!$this->request->post['coinfide_secret']) {
            $this->error['secret'] = $this->language->get('error_secret');
        }


		return !$this->error;
	}

}
