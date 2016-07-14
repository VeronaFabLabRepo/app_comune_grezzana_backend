<?php
class FM_Content_Values_Model extends CI_Model {

	private $_tableName = '';

    public function __construct()
    {
        parent::__construct();
		$ci =& get_instance();

		$this->_tableName = $ci->config->item('content_values_tbl', 'factotum');
    }

	// ===============  SELECTIONS ================== //

	public function getContentValueById($id)
	{
		$query = $this->db->get_where($this->_tableName, array('id' => $id), 1);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

	public function getAllContentValuesByIdContent($idContent)
	{
		$query = $this->db->get_where($this->_tableName, array('id_content' => $idContent));
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

	public function getContentValuesByIdContentField($idContentField)
	{
		$query = $this->db->get_where($this->_tableName, array('id_content_field' => $idContentField));
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

	public function getContentValueByIdContentFieldAndIdContent($idContentField, $idContent)
	{
		$conds = array(
			  'id_content_field' => $idContentField
			, 'id_content'       => $idContent
		);
		$query = $this->db->get_where($this->_tableName, $conds, 1);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

	// ===============  INSERTIONS ================== //
	
    public function insertContentValue($idContent, $idContentField, $value)
    {
		$data = array(
			  'id_content'       => $idContent
			, 'id_content_field' => $idContentField
			, 'value'            => $value
		);
		$this->db->insert($this->_tableName, $data); 
		return $this->db->insert_id();
    }

	// ===============  UPDATES ================== //

    public function updateContentValue($id, $value)
    {
		$data = array('value' => $value);
		$this->db->where('id', $id);
		return $this->db->update($this->_tableName, $data);
    }

	// ===============  DELETIONS ================== //

	public function deleteContentValueById($id)
	{
		return $this->db->delete($this->_tableName, array('id' => $id)); 
	}

	public function deleteContentValuesByIdContent($idContent)
	{
		return $this->db->delete($this->_tableName, array('id_content' => $idContent));
	}

	public function deleteContentValuesByIdContentField($idContentField)
	{
		return $this->db->delete($this->_tableName, array('id_content_field' => $idContentField));
	}
}

?>