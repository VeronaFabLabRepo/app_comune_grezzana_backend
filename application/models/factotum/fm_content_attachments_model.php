<?php
class FM_Content_Attachments_Model extends CI_Model {

	private $_tableName = '';

    public function __construct()
    {
        parent::__construct();
		$ci =& get_instance();

		$this->_tableName = $ci->config->item('content_attachments_tbl', 'factotum');
    }

	// ===============  SELECTIONS ================== //

	public function getContentAttachmentById($id)
	{
		$query = $this->db->get_where($this->_tableName, array('id' => $id), 1);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

	public function getAllContentAttachmentsByIdContent($idContent)
	{
		$this->db->order_by('order_no', 'ASC');
		$query = $this->db->get_where($this->_tableName, array('id_content' => $idContent));
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

	// ===============  INSERTIONS ================== //

    public function insertContentAttachment($idContent, $idContentField, $title, $filename)
    {
		$data = array(
			  'id_content'       => $idContent
			, 'id_content_field' => $idContentField
			, 'title'            => $title
			, 'filename'         => $filename
		);
		$this->db->insert($this->_tableName, $data); 
		return $this->db->insert_id();
    }

	// ===============  UPDATES ================== //
    public function updateContentAttachment($id, $title, $filename, $order = '')
    {
		$data = array(
			  'title'    => $title
			, 'filename' => $filename
		);

		if ($order) {
			$data['order_no'] = $order;
		}

		$this->db->where('id', $id);
		return $this->db->update($this->_tableName, $data);
    }

    public function updateContentAttachmentOrder($id, $order)
    {
		$data = array('order_no' => $order);
		$this->db->where('id', $id);
		return $this->db->update($this->_tableName, $data);
    }

	// ===============  DELETIONS ================== //

	public function deleteContentAttachmentById($id)
	{
		return $this->db->delete($this->_tableName, array('id' => $id)); 
	}

	public function deleteContentAttachmentsByIdContent($idContent)
	{
		return $this->db->delete($this->_tableName, array('id_content' => $idContent)); 
	}



}

?>