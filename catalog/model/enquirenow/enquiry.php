<?php
class ModelEnquirenowEnquiry extends Model {
    // AJ Aug 14: this model is added to save "enquire now" into database
	public function saveEnquiry($params) {
        $query = "INSERT INTO `" . DB_PREFIX . "enquirenow` SET `name` = '" . $params['name'] . "', `email` = '" . $params['email'] . "', `telephone` = '" . $params['telephone'] . "', `message` = '" . $params['message'] . "', `product_name` = '" . $params['product_name'] . "', `product_id` = '" . $params['product_id'] . "', `date_added` = NOW()";
        $this->db->query($query);
    }
}