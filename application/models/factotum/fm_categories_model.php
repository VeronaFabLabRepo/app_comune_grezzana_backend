<?php
class FM_Categories_Model extends CI_Model {

	private $_tableName                 = '',
			$_contentTypeTable          = '',
			$_joinConditionCategoryType = '';

    public function __construct()
    {
        parent::__construct();
		$ci =& get_instance();

		$this->_tableName        = $ci->config->item('categories_tbl', 'factotum');
		$this->_contentTypeTable = $ci->config->item('content_types_tbl',     'factotum');
		$this->_joinConditionCategoryType = $this->_tableName . '.id_content_type = ' . $this->_contentTypeTable . '.id';
    }

	// Selections
	public function getCategoryById($id)
	{
		$query = $this->db->get_where($this->_tableName, array('id' => $id), 1);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

	public function getCategoryByName($name)
	{
		$query = $this->db->get_where($this->_tableName, array('category_name' => $name), 1);
        return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

	public function getCategoriesByContentType($idContentType)
	{
		$query = $this->db->get_where($this->_tableName, array('id_content_type' => $idContentType));
        return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}



	public function getCategories($orderBy = 'category_name ASC')
	{
		$query = $this->db->select($this->_tableName . '.id, ' . $this->_tableName . '.id_content_type, category_name, category_label, content_type')
						  ->from($this->_tableName)
						  ->join($this->_contentTypeTable, $this->_joinConditionCategoryType)
						  ->order_by($this->_tableName . '.' . $orderBy)
						  ->get();

		return ($query->num_rows() > 0 ? $query->result_array() : NULL);

	}

	public function isCategoryAvailable($contentCategoryName)
	{
		$this->db->where('LOWER(category_name)', $contentCategoryName);

		$query = $this->db->get($this->_tableName);
		return ($query->num_rows() == 0 ? TRUE : FALSE);
	}

	// Insert
    public function insertCategory($idContentType, $categoryName, $categoryLabel)
    {
		$data = array(
			  'id_content_type' => $idContentType
			, 'category_name'   => $categoryName
			, 'category_label'  => $categoryLabel
		);
		$this->db->insert($this->_tableName, $data);
    }


	// Update
    public function updateCategory($id, $idContentType, $categoryName, $categoryLabel)
    {
		$data = array(
			  'id_content_type' => $idContentType
			, 'category_name'   => $categoryName
			, 'category_label'  => $categoryLabel
		);
		$this->db->where('id', $id);
		$this->db->update($this->_tableName, $data);
    }

	public function updateCategoryOrder($id, $orderNo)
	{
		$data = array('order_no' => $orderNo);
		$this->db->where('id', $id);
		$this->db->update($this->_tableName, $data);
	}

	// Delete
	public function deleteCategoryById($id)
	{
		return $this->db->delete($this->_tableName, array('id' => $id));
	}

	public function deleteCategoryByIdContentType($idContentType)
	{
		return $this->db->delete($this->_tableName, array('id_content_type' => $idContentType));
	}

}

?>