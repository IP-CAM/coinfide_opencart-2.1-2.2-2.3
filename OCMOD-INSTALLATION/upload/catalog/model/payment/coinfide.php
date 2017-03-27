<?php
class ModelPaymentCoinfide extends Model {

	public function getMethod($address, $total) {
		$this->load->language('payment/coinfide');

		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "zone_to_geo_zone WHERE geo_zone_id = '" . (int)$this->config->get('coinfide_geo_zone_id') . "' AND country_id = '" . (int)$address['country_id'] . "' AND (zone_id = '" . (int)$address['zone_id'] . "' OR zone_id = '0')");

		if ($this->config->get('coinfide_total') > 0 && $this->config->get('coinfide_total') > $total) {
			$status = false;
		} elseif (!$this->config->get('coinfide_geo_zone_id')) {
			$status = true;
		} elseif ($query->num_rows) {
			$status = true;
		} else {
			$status = false;
		}

		$method_data = array();

		if ($status) {
			$method_data = array(
				'code' => 'coinfide',
				'title' => $this->language->get('text_title'),
				'terms' => '',
				'sort_order' => $this->config->get('coinfide_sort_order')
			);
		}

		return $method_data;
	}

	public function addCoinfideOrder($order_info) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "coinfide_order` SET `order_id` = '" . (int)$order_info['order_id'] . "', `date_added` = now(), `modified` = now(), `currency_code` = '" . $this->db->escape($order_info['currency_code']) . "', `total` = '" . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false) . "'");
	}

	public function updateOrder($coinfide_order_id, $coinfide_transaction_id, $type, $order_info) {
		$this->db->query("UPDATE `" . DB_PREFIX . "coinfide_order` SET `coinfide_transaction_id` = '" . $this->db->escape($coinfide_transaction_id) . "', `modified` = now() WHERE `order_id` = '" . (int)$order_info['order_id'] . "'");

		$this->addTransaction($coinfide_order_id, $type, $order_info);

	}

	public function addTransaction($coinfide_order_id, $type, $order_info) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "coinfide_order_transaction` SET `coinfide_order_id` = '" . (int)$coinfide_order_id . "', `date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . $this->currency->format($order_info['total'], $order_info['currency_code'], false, false) . "'");
	}

	public function getCoinfideOrder($order_id) {
		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coinfide_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			return $qry->row;
		} else {
			return false;
		}
	}

	public function logger($message) {
		if ($this->config->get('coinfide_debug') == 1) {
			$log = new Log('coinfide.log');
			$backtrace = debug_backtrace();
            if (isset($backtrace[6])) {
                $log->write('Origin: ' . $backtrace[6]['class'] . '::' . $backtrace[6]['function']);
			}
			$log->write(print_r($message, 1));
		}
	}
}