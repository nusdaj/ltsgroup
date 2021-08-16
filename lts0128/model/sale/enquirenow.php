<?php
// AJ Aug 15: copied from Enquiry module and tailored.
class ModelSaleEnquirenow extends Model {
	
	public function deleteEnquirenow($id) {
        debug($id);
		$this->db->query("DELETE FROM `" . DB_PREFIX . "enquirenow` WHERE id = '" . (int)$id . "'");
    }	
    
	public function getEnquirenow($id) {
        $order_query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquirenow WHERE id = '" . (int)$id . "'");

        return array(
            'id'             => $order_query->row['id'],
            'name'           => $order_query->row['name'],
            'email'          => $order_query->row['email'],
            'telephone'      => $order_query->row['telephone'],
            'message'        => $order_query->row['message'],
            'product_name'   => $order_query->row['product_name'],
            'product_id'     => $order_query->row['product_id'],
            'date_added'     => $order_query->row['date_added']
        );
    }
    
    public function getNumEnquirenow() {
        $count = $this->db->query("SELECT COUNT(*) As total FROM " . DB_PREFIX . "enquirenow");
        return $count->row['total'];
    }

	// AJ Aug 15: this does not match the caller from controller
	public function getAllEnquirenow($start, $limit) {
		if ($start < 0) $start = 0;
		if ($limit < 1) $limit = 20;
		$query = $this->db->query("SELECT * FROM " . DB_PREFIX . "enquirenow LIMIT " . (int)$start . "," . (int)$limit);

		return $query->rows;
	}
}