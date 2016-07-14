<?php
class FM_Content_Types_Model extends CI_Model {

	private $_tableName = '';

    public function __construct()
    {
        parent::__construct();
		$ci =& get_instance();

		$this->_tableName = $ci->config->item('content_types_tbl', 'factotum');
    }

	// Selections
	public function getContentTypeById($id)
	{
		$query = $this->db->get_where($this->_tableName, array('id' => $id), 1);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

	public function getContentTypeByName($name)
	{
		$query = $this->db->get_where($this->_tableName, array('content_type' => $name), 1);
        return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

	public function getAllContentType($butNot = null, $orderBy = 'order_no ASC')
	{
		$query = $this->db->select('*')
						  ->from($this->_tableName);

		if ($butNot) {

			if (is_string($butNot)) {
				$query->where('content_type !=', $butNot);
			}

			if (is_array($butNot)) {
				$query->where_in('content_type', $butNot);
			}

		}

		$this->db->order_by($orderBy);
		$query = $this->db->get();

		return ($query->num_rows() > 0 ? $query->result_array() : NULL);

	}

	public function isContentTypeAvailable($contentType)
	{
		$this->db->where('LOWER(content_type)', $contentType);

		$query = $this->db->get($this->_tableName);
		return ($query->num_rows() == 0 ? TRUE : FALSE);
	}

	// Insert
    public function insertContentType($contentType)
    {
		$data = array('content_type' => $contentType);
		$this->db->insert($this->_tableName, $data); 
    }


	// Update
    public function updateContentType($contentType, $id)
    {
		$data = array('content_type' => $contentType);
		$this->db->where('id', $id);
		$this->db->update($this->_tableName, $data); 
    }

	public function updateContentTypeOrder($id, $orderNo)
	{
		$data = array('order_no' => $orderNo);
		$this->db->where('id', $id);
		$this->db->update($this->_tableName, $data);
	}

	// Delete
	public function deleteContentTypeById($id)
	{
		$this->db->where('id', $id);
		$this->db->delete($this->_tableName);
	}

}

?>