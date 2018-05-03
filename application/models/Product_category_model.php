<?php 

/**
* 
*/
class Product_category_model extends MY_Model{
	
	public $table = 'product_category';

	public function get_menu($parent_id, $order = 'desc',$lang = ''){
		$this->db->select($this->table .'.*, '. $this->table_lang .'.title');
        $this->db->from($this->table);
        $this->db->join($this->table_lang, $this->table_lang .'.'. $this->table .'_id = '. $this->table .'.id');
        $this->db->where($this->table .'.is_deleted', 0);
        if($lang != ''){
            $this->db->where($this->table_lang .'.language', $lang);
        }
        if(is_numeric($parent_id)){
            $this->db->where($this->table .'.parent_id', $parent_id);
        }
    	
        $this->db->group_by($this->table_lang .'.'. $this->table .'_id');
        $this->db->order_by($this->table .".sort", $order);

        return $result = $this->db->get()->result_array();
	}

	public function update_sort($sort, $id){
        $this->db->set(array('sort' => $sort))
            ->where('id', $id)
            ->update('product_category');

        if($this->db->affected_rows() == 1){
            return true;
        }

        return false;
    }
}