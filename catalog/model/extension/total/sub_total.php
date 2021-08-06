<?php
class ModelExtensionTotalSubTotal extends Model {
	public function getTotal($total) {
		$this->load->language('extension/total/sub_total');

		$sub_total = $this->cart->getSubTotal();

		if (!empty($this->session->data['vouchers'])) {
			foreach ($this->session->data['vouchers'] as $voucher) {
				$sub_total += $voucher['amount'];
			}
		}

		if ($this->config->get('gst_inclusive_status')) {
			$total['total'] += $sub_total;

            $new_sub_total = $total['total']/1.07;
	                
			$total['totals'][] = array(
				'code'       => 'sub_total',
				'title'      => $this->language->get('text_sub_total'),
				'value'      => $new_sub_total,
				'sort_order' => $this->config->get('sub_total_sort_order')
			);

		} else {
			$total['totals'][] = array(
				'code'       => 'sub_total',
				'title'      => $this->language->get('text_sub_total'),
				'value'      => $sub_total,
				'sort_order' => $this->config->get('sub_total_sort_order')
			);

			$total['total'] += $sub_total;
		}
	}
}
