<?php
class FM_Content_Categories_Model extends CI_Model {

	private $_tableName                    = '',
			$_contentTable                 = '',
			$_joinConditionCategoryContent = '';

    public function __construct()
    {
        parent::__construct();
		$ci =& get_instance();

		$this->_tableName    = $ci->config->item('content_categories_tbl', 'factotum');
		$this->_contentTable = $ci->config->item('contents_tbl',           'factotum');
		$this->_joinConditionCategoryContent = $this->_tableName . '.id_content = ' . $this->_contentTable . '.id';
    }

	// Selections
	public function getAllContentCategories($orderBy = 'id ASC')
	{
		$query = $this->db->from($this->_tableName)
						  ->order_by($this->_tableName . '.' . $orderBy)
						  ->get();

		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

	public function getAllContentCategoriesByIdContent($idContent, $orderBy = 'id ASC')
	{
		$query = $this->db->from($this->_tableName)
						  ->where($this->_tableName . '.id_content', $idContent)
						  ->order_by($this->_tableName . '.' . $orderBy)
						  ->get();

		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

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
		$query = $this->db->get_where($this->_tableName, array('id_content_type' => $idContentType), 1);
        return ($query->num_rows() == 1 ? $query->result_array() : NULL);
	}

    public function getContentIdsByCategories($ids)
    {
        $query = $this->db->select('id_content')->distinct(true)
            ->from($this->_tableName)
            ->where_in('id_category', (array) $ids)
            ->get();

        if (!$query->num_rows()) {
            return null;
        }

        $return = $query->result_array();
        foreach ($return as $i => $row) {
            $return[$i] = $row['id_content'];
        }

        return $return;
    }


	public function isCategoryAvailable($contentCategoryName)
	{
		$this->db->where('LOWER(category_name)', $contentCategoryName);

		$query = $this->db->get($this->_tableName);
		return ($query->num_rows() == 0 ? TRUE : FALSE);
	}

	// Insert
    public function insertContentCategory($idContent, $idCategory)
    {
		$data = array(
			  'id_content'  => $idContent
			, 'id_category' => $idCategory
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

	public function deleteContentCategoryByIdContent($idContent)
	{
		return $this->db->delete($this->_tableName, array('id_content' => $idContent));
	}

	public function deleteContentCategoryByIdCategory($idCategory)
	{
		return $this->db->delete($this->_tableName, array('id_category' => $idCategory));
	}

	public function deleteContentCategoryByIdContentAndIdCategory($idContent, $categoryID)
	{
		return $this->db->delete($this->_tableName, array('id_content' => $idContent, 'id_category' => $categoryID));
	}
}

?>