<?php

class ModelExtensionPaymentCoinfide extends Model {

	public function install() {
		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "coinfide_order` (
				`coinfide_order_id` INT(11) NOT NULL AUTO_INCREMENT,
				`order_id` int(11) NOT NULL,
				`coinfide_transaction_id` varchar(255) NOT NULL,
				`date_added` DATETIME NOT NULL,
				`modified` DATETIME NOT NULL,
				`refund_status` INT(1) DEFAULT NULL,
				`currency_code` CHAR(3) NOT NULL,
				`total` DECIMAL( 10, 2 ) NOT NULL,
				KEY `coinfide_transaction_id` (`coinfide_transaction_id`),
				PRIMARY KEY `coinfide_order_id` (`coinfide_order_id`)
			) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
		");

		$this->db->query("
			CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "coinfide_order_transaction` (
			  `coinfide_order_transaction_id` INT(11) NOT NULL AUTO_INCREMENT,
			  `coinfide_order_id` INT(11) NOT NULL,
			  `date_added` DATETIME NOT NULL,
			  `type` ENUM('payment', 'refund') DEFAULT NULL,
			  `amount` DECIMAL( 10, 2 ) NOT NULL,
			  PRIMARY KEY (`coinfide_order_transaction_id`)
			) ENGINE=MyISAM DEFAULT COLLATE=utf8_general_ci;
			");
	}

	public function uninstall() {
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "coinfide_order`;");
		$this->db->query("DROP TABLE IF EXISTS `" . DB_PREFIX . "coinfide_order_transaction`;");
	}

	public function getOrder($order_id) {

		$qry = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coinfide_order` WHERE `order_id` = '" . (int)$order_id . "' LIMIT 1");

		if ($qry->num_rows) {
			$order = $qry->row;
			$order['transactions'] = $this->getTransactions($order['coinfide_order_id'], $qry->row['currency_code']);
			return $order;
		} else {
			return false;
		}
	}

	public function getTotalReleased($coinfide_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "coinfide_order_transaction` WHERE `coinfide_order_id` = '" . (int)$coinfide_order_id . "' AND (`type` = 'payment' OR `type` = 'refund')");

		return (double)$query->row['total'];
	}

	public function refund($coinfide_order, $amount) {
		if (!empty($coinfide_order) && $coinfide_order['refund_status'] != 1) {
			if ($this->config->get('coinfide_environment') == 'prod') {
				$url = 'https://pay.g2a.com/rest/transactions/' . $coinfide_order['coinfide_transaction_id'];
			} else {
				$url = 'https://www.test.pay.g2a.com/rest/transactions/' . $coinfide_order['coinfide_transaction_id'];
			}

			$refunded_amount = round($amount, 2);
            //todo
//			$string = $coinfide_order['coinfide_transaction_id'] . $coinfide_order['order_id'] . round($coinfide_order['total'], 2) . $refunded_amount . html_entity_decode($this->config->get('coinfide_api_username'));
//			$hash = hash('sha256', $string);

//			$fields = array(
//				'action' => 'refund',
//				'amount' => $refunded_amount,
//				'hash' => $hash,
//			);

//			return $this->sendCurl($url, $fields);
		} else {
			return false;
		}
	}

	public function updateRefundStatus($coinfide_order_id, $status) {
		$this->db->query("UPDATE `" . DB_PREFIX . "coinfide_order` SET `refund_status` = '" . (int)$status . "' WHERE `coinfide_order_id` = '" . (int)$coinfide_order_id . "'");
	}

	private function getTransactions($coinfide_order_id, $currency_code) {
		$query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "coinfide_order_transaction` WHERE `coinfide_order_id` = '" . (int)$coinfide_order_id . "'");

		$transactions = array();
		if ($query->num_rows) {
			foreach ($query->rows as $row) {
				$row['amount'] = $this->currency->format($row['amount'], $currency_code, true, true);
				$transactions[] = $row;
			}
			return $transactions;
		} else {
			return false;
		}
	}

	public function addTransaction($coinfide_order_id, $type, $total) {
		$this->db->query("INSERT INTO `" . DB_PREFIX . "coinfide_order_transaction` SET `coinfide_order_id` = '" . (int)$coinfide_order_id . "',`date_added` = now(), `type` = '" . $this->db->escape($type) . "', `amount` = '" . (double)$total . "'");
	}

	public function getTotalRefunded($coinfide_order_id) {
		$query = $this->db->query("SELECT SUM(`amount`) AS `total` FROM `" . DB_PREFIX . "coinfide_order_transaction` WHERE `coinfide_order_id` = '" . (int)$coinfide_order_id . "' AND 'refund'");

		return (double)$query->row['total'];
	}

	public function logger($message) {
		if ($this->config->get('coinfide_debug') == 1) {
			$log = new Log('coinfide.log');
			$backtrace = debug_backtrace();
			$log->write('Origin: ' . $backtrace[6]['class'] . '::' . $backtrace[6]['function']);
			$log->write(print_r($message, 1));
		}
	}

}
