<?php 
    /* AJ Apr 22: enhanced version. Add a few handlers to enable operations on individual records.
       only the function getUrlaliases is original
    */
    class ModelSettingUrlAlias extends Model {
        
        public function getUrlaliases() {
            
			$sql = "SELECT * FROM ".DB_PREFIX."url_alias  ORDER BY query ASC";
			$query = $this->db->query($sql);
            
			return $query->rows;
        }

        public function addAlias($query, $keyword) {
            $this->db->query("INSERT INTO " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($query) . "', `keyword` = '" . $this->db->escape($keyword) . "'");
        }
        
        public function editAlias($url_alias_id, $query, $keyword) {
            $this->db->query("UPDATE " . DB_PREFIX . "url_alias SET query = '" . $this->db->escape($query) . "', `keyword` = '" . $this->db->escape($keyword) . "' WHERE url_alias_id = '" . $url_alias_id . "'");
        }
        
        public function deleteAlias($url_alias_id) {
            $this->db->query("DELETE FROM " . DB_PREFIX . "url_alias WHERE url_alias_id = '" . (int)$url_alias_id . "'");
        } 
    
        public function getAlias($url_alias_id) {
            $query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "url_alias WHERE " . DB_PREFIX . "url_alias.url_alias_id = '" . (int)$url_alias_id . "'");
            
            return $query->row;
        } 
        
    }