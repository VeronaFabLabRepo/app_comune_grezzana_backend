<?php
class FM_Content_Fields_Model extends CI_Model {

	private $_tableName               = '',
			$_contentValueTable       = '',
			$_joinConditionFieldValue = '';

    public function __construct()
    {
        parent::__construct();
		$ci =& get_instance();

		$this->_tableName               = $ci->config->item('content_fields_tbl', 'factotum');
		$this->_contentValueTable       = $ci->config->item('content_values_tbl', 'factotum');
		$this->_joinConditionFieldValue = $this->_contentValueTable . '.id_content_field = ' . $this->_tableName . '.id';
    }

	// ===============  SELECTIONS ================== //

	public function getContentFieldById($id)
	{
		$query = $this->db->get_where($this->_tableName, array('id' => $id), 1);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

	public function getAllContentFields()
	{
		$query = $this->db->get($this->_tableName);
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

	public function getContentFieldsByIdContentType($idContentType, $orderBy = '')
	{
		$this->db->select('*')
				 ->from($this->_tableName)
				 ->where('id_content_type', $idContentType)
				 ->order_by(($orderBy ? $orderBy : 'order_no ASC'));

		$query = $this->db->get();
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

	public function getContentFieldsCountByIdContentType($idContentType)
	{
		$this->db->select('COUNT(*) AS count')
				 ->from($this->_tableName)
				 ->where('id_content_type', $idContentType);

		$query  = $this->db->get();
		$result = $query->row_array();
		return $result['count'];
	}

	public function getContentFieldsByTypeAndNames($idContentType, $names)
	{
		$this->db->select('*')
				 ->from($this->_tableName)
				 ->where('id_content_type', $idContentType)
				 ->where_in('name', $names);
		$query = $this->db->get();
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

	public function getContentFieldsAndValues($idContentType, $whereConditions, $selectFields = '*')
	{
		if (is_array($selectFields) && count($selectFields) > 0) {

			$this->db->select(join(',', $selectFields));

		} else {

			$this->db->select($selectFields);

		}

		// Join the content field with the content value
		$this->db->from($this->_tableName)
				 ->join($this->_contentValueTable, $this->_joinConditionFieldValue)
				 ->where('id_content_type', $idContentType);
		
		if (count($whereConditions) > 0) {

			foreach ($whereConditions as $fieldName => $value) {

				$where = "(" . $this->_tableName . ".name = '" . $fieldName . "' AND ";

				// Check if the where contains the work LIKE
				if (strpos(strtolower($value), 'like') !== FALSE) {

					// if there is the word LIKE, the value will contain the like(%word%) 
					$where .= $this->_contentValueTable . ".value " . $value . ")";

				} else {

					$where .= $this->_contentValueTable . ".value = '" . $value . "')";

				}

				$this->db->where($where);

			}

		}

		$this->db->group_by($this->_contentValueTable . '.id_content, ' . $this->_contentValueTable . '.value');

		$query = $this->db->get();

		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

	public function isContentFieldNameAvailable($fieldName)
	{
		$this->db->where('LOWER(name)', $fieldName);

		$query = $this->db->get($this->_tableName);
		return ($query->num_rows() == 0 ? TRUE : FALSE);
	}

	// ===============  INSERTIONS ================== //

    public function insertContentField($idContentType, $name, $type, $label, $mandatory, $order_no = '', $extra = array())
    {
		$data = array(
			  'id_content_type' => $idContentType
			, 'name'            => $name
			, 'type'            => $type
			, 'label'           => $label
			, 'mandatory'       => $mandatory
		);

		if ($order_no) {

			$data['order_no'] = $order_no;

		}

		if (count($extra) > 0) {

			if (isset($extra['hint']) && $extra['hint'] != '') {

				$data['hint'] = $extra['hint'];

			}

			if (isset($extra['options']) && $extra['options'] != '') {

				$data['options'] = $extra['options'];

			}

			if (isset($extra['max_file_size']) && $extra['max_file_size'] != '') {

				$data['max_file_size'] = $extra['max_file_size'];

			}

			if (isset($extra['max_image_size']) && $extra['max_image_size'] != '') {

				$data['max_image_size'] = $extra['max_image_size'];

			}

			if (isset($extra['thumb_size']) && $extra['thumb_size'] != '') {

				$data['thumb_size'] = $extra['thumb_size'];

			}

			if (isset($extra['image_operation']) && $extra['image_operation'] != '') {

				$data['image_operation'] = $extra['image_operation'];

			}

			if (isset($extra['allowed_types']) && $extra['allowed_types'] != '') {

				$data['allowed_types'] = $extra['allowed_types'];

			}

			if (isset($extra['image_bw']) && $extra['image_bw'] != '') {

				$data['image_bw'] = $extra['image_bw'];

			}

			if (isset($extra['linked_id_content_type']) && $extra['linked_id_content_type'] != '') {

				$data['linked_id_content_type'] = $extra['linked_id_content_type'];

			}
		}

		$this->db->insert($this->_tableName, $data); 
		return $this->db->insert_id();
    }

	// ===============  UPDATES ================== //

	public function updateContentField($id, $name, $type, $label, $mandatory, $order_no = '', $extra = array())
	{
		$data = array(
			  'name'      => $name
			, 'type'      => $type
			, 'label'     => $label
			, 'mandatory' => $mandatory
		);

		if ($order_no) {

			$data['order_no'] = $order_no;

		}

		if (count($extra) > 0) {

			if (isset($extra['hint']) && $extra['hint'] != '') {

				$data['hint'] = $extra['hint'];

			}

			if (isset($extra['options']) && $extra['options'] != '') {

				$data['options'] = $extra['options'];

			}

			if (isset($extra['max_file_size']) && $extra['max_file_size'] != '') {

				$data['max_file_size'] = $extra['max_file_size'];

			}

			if (isset($extra['max_image_size']) && $extra['max_image_size'] != '') {

				$data['max_image_size'] = $extra['max_image_size'];

			}

			if (isset($extra['thumb_size']) && $extra['thumb_size'] != '') {

				$data['thumb_size'] = $extra['thumb_size'];

			}

			if (isset($extra['image_operation']) && $extra['image_operation'] != '') {

				$data['image_operation'] = $extra['image_operation'];

			}

			if (isset($extra['allowed_types']) && $extra['allowed_types'] != '') {

				$data['allowed_types'] = $extra['allowed_types'];

			}

			if (isset($extra['image_bw']) && $extra['image_bw'] != '') {

				$data['image_bw'] = $extra['image_bw'];

			}

			if (isset($extra['linked_id_content_type']) && $extra['linked_id_content_type'] != '') {

				$data['linked_id_content_type'] = $extra['linked_id_content_type'];

			}
		}

		$this->db->where('id', $id);
		return $this->db->update($this->_tableName, $data);

	}

	public function updateContentFieldOrder($id, $orderNo)
	{
		$data = array('order_no' => $orderNo);
		$this->db->where('id', $id);
		$this->db->update($this->_tableName, $data);
	}

	// ===============  DELETIONS ================== //

	public function deleteContentFieldById($id)
	{
		return $this->db->delete($this->_tableName, array('id' => $id));
	}

	public function deleteContentFieldsByIdContentType($idContentType)
	{
		return $this->db->delete($this->_tableName, array('id_content_type' => $idContentType)); 
	}


}

?>