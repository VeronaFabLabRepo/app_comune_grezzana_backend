<?php
class FM_Contents_Model extends CI_Model {

	private $_tableName = '';


	// Possible status
	// live
	// offline
	public $status        = array(),
		   $statusLive    = 'live',
		   $statusOffline = 'offline',
		   $ci;


	public function __construct()
	{
		parent::__construct();

		$this->ci =& get_instance();

		$this->status = array(
				$this->statusLive     => $this->statusLive
			  , $this->statusOffline  => $this->statusOffline
		);

		$this->_tableName = $this->ci->config->item('contents_tbl', 'factotum');
	}

	/**
	 *  Prepare the query object
	 *
	 * @param string|array  The select part
	 * @param string|array  The where part
	 * @param string        The order by
	 * @param int           The limit
	 * @param int           The offset
	 * @return mixed        The query object
	 *
	 */
	private function _prepareQuery($select, $where = array(), $orderBy = '', $limit = '', $offset = '')
	{
		if (is_array($select)) {

			$this->db->select(join(',', $select));

		} elseif (is_string($select)) {

			$this->db->select($select);

		}

		if (is_array($where)) {

			if (count($where) > 0) {

				foreach ($where as $field => $value) {

					if (is_array($value)) {

						$this->db->where_in($field, $value);

					} else {

						$this->db->where($field, $value);

					}

				}
			}

		} else if (is_string($where)) {

			$this->db->where($where);

		}

		$this->db->order_by(($orderBy ? $orderBy : 'order_no ASC'));

		if ($limit && $offset) {

			$this->db->limit($limit, $offset);

		} else if ($limit && !$offset) {

			$this->db->limit($limit);

		}

		return $this->db->get($this->_tableName);

	}


	// ===============  SELECTIONS ================== //

	public function getContentColumns()
	{
		return $this->db->list_fields($this->_tableName);
	}

	public function getContentById($id)
	{
		$query = $this->db->get_where($this->_tableName, array('id' => $id), 1);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

	public function getLiveContentById($id)
	{
		$conds = array(
			  'id'     => $id
			, 'status' => $this->statusLive
		);
		$query = $this->db->get_where($this->_tableName, $conds, 1);
		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
	}

// 	public function getContentByPermalink($relativePath)
// 	{
// 		$query = $this->db->get_where($this->_tableName, array('relative_path' => $relativePath), 1);
// 		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
// 	}

// 	public function getLiveContentByPermalink($relativePath)
// 	{
// 		$conds = array(
// 			  'relative_path' => $relativePath
// 			, 'status'    => $this->statusLive
// 		);
// 		$query = $this->db->get_where($this->_tableName, $conds, 1);
// 		return ($query->num_rows() == 1 ? $query->row_array() : NULL);
// 	}


	private function _setJoinsContentList($allFields, $allCategories) {

		if ($allFields) {
			$contentFieldTable = $this->ci->fm_cms->getContentFieldTableName();
			$contentValueTable = $this->ci->fm_cms->getContentValueTableName();
			$contentsValuesFieldsJoinCondition = $this->_tableName . '.id = ' . $contentValueTable . '.id_content'
											   . ' AND ' . $contentValueTable . '.id_content_field = ' . $contentFieldTable . '.id'
											   ;

			$this->db->join($contentFieldTable, $this->_tableName . '.id_content_type = ' . $contentFieldTable . '.id_content_type');
			$this->db->join($contentValueTable, $contentsValuesFieldsJoinCondition);
		}

		if ($allCategories) {
			$categoriesTable        = $this->ci->fm_cms->getCategoriesTableName();
			$contentCategoriesTable = $this->ci->fm_cms->getContentCategoriesTableName();
//			$contentCategoriesCategoriesJoinCondition = $contentCategoriesTable . '.id_category = ' . $categoriesTable . '.id'
//											   . ' AND ' . $categoriesTable . '.id = ' . $contentCategoriesTable . '.id_category'
//											   ;

			$this->db->join($contentCategoriesTable, $this->_tableName . '.id = ' . $contentCategoriesTable . '.id_content');
			$this->db->join($categoriesTable, $categoriesTable . '.id = ' . $contentCategoriesTable . '.id_category');
		}
	}

	private function _getBasicWhereStringContentList(array $basicWhereConditions)
	{
		$tmp = array();
		foreach ($basicWhereConditions as $field => $valueAndOperator) {

			$operator = $valueAndOperator['operator'];
			$value    = $valueAndOperator['value'];

			if (is_array($value)) {

				$tmp[] = $this->_tableName . '.' . $field . ' IN ("' . join('","', $value) . '")';

			} else {

				if ($operator == '=') {

					$tmp[] = $this->_tableName . '.' . $field . ' = ' . (is_string($value) ? '"' . $value . '"' : $value);

				} else if (strtolower($operator) == 'like' || strtolower($operator) == 'ilike') {

					$tmp[] = $this->_tableName . '.' . $field . ' ' . strtoupper($operator) . ' ' . '"%' . $value . '%"';

	            } else if (strtolower($operator) == 'in') {

					$tmp[] = $this->_tableName . '.' . $field . ' ' . strtoupper($operator) . ' ('. $value . ')';

	            } else {

					$tmp[] = $this->_tableName . '.' . $field . ' ' . $operator . ' ' . (is_string($value) ? '"' . $value . '"' : $value);

				}

			}

		}

		return join(' AND ', $tmp);
	}

	private function _getDeeperFilterResultIdsContentList($basicWhereConditions, array $whereConditions)
	{
		$contentFieldTable = $this->ci->fm_cms->getContentFieldTableName();
		$contentValueTable = $this->ci->fm_cms->getContentValueTableName();

		$subquery = 'SELECT ' . $this->_tableName . '.id AS id'
				  . '  FROM ' . $this->_tableName
				  . '  JOIN ' . $contentFieldTable . ' ON ' . $this->_tableName . '.id_content_type = ' . $contentFieldTable . '.id_content_type'
				  . '  JOIN ' . $contentValueTable . ' ON ' . $this->_tableName . '.id = ' . $contentValueTable . '.id_content'
				  . '   AND ' . $contentValueTable . '.id_content_field = ' . $contentFieldTable . '.id'
				  . ' WHERE ' . $basicWhereConditions
				  ;

		// Where conditions for Fields And Values
		$subquery .= ' AND';

		foreach ($whereConditions as $fieldName => $valueAndOperator) {

			if (is_array($valueAndOperator['value'])) {

				$value = '"' . join('","', $valueAndOperator['value']) . '"';

				$subquery .= ' (' . $contentFieldTable . '.name = "' . $fieldName . '" AND '
						   . $contentValueTable . '.value IN (' . $value . ')' . ') AND';

			} else {

				$subquery .= ' (' . $contentFieldTable . '.name = "' . $fieldName . '" AND '
						   . $contentValueTable . '.value ';

				if ($valueAndOperator['operator'] == '=') {

					$subquery .= ' = "' . $valueAndOperator['value'] . '"';

				} else if (strtolower($valueAndOperator['operator']) == 'like' || strtolower($valueAndOperator['operator']) == 'ilike') {

					$subquery .= strtoupper($valueAndOperator['operator']) . ' "%' . $valueAndOperator['value'] . '%"';

				} else if (strtolower($valueAndOperator['operator']) == 'in') {

					$subquery .= strtoupper($valueAndOperator['operator']) . ' (' . $valueAndOperator['value'] . ')';

				} else {

					$subquery .= $valueAndOperator['operator'] . ' '
							   . (is_string($valueAndOperator['value']) ? '"' . $valueAndOperator['value'] . '"' : $valueAndOperator['value']) ;

				}

				$subquery .= ') AND';

			}

		}

		$subquery = substr($subquery, 0, -4);

		$subquery .= ' GROUP BY ' . $this->_tableName . '.id';

		$subquery = $this->db->query($subquery);
		$tmp = array();
		foreach ($subquery->result_array() as $row) {
			array_push($tmp, $row['id']);
		}

		return (count($tmp) > 0 ? $this->_tableName . '.id IN (' . join(',', $tmp) . ')' : NULL);
	}

	/**
	 * Retrieve the content list by content_type and some where condition
	 *
	 * @param	array          The basic where conditions that contains the content type id and (not mandatory) if the status is live or not
	 * @param	array          The where conditions array
	 * @param	array|string   The array (or string) of which field to select
	 * @param   string         The order string
	 * @param   int            The limit
	 * @param	int            The offset
	 * @return	array          Return an array of content list
	 */
	public function getContentList($basicWhereConditions
								 , $whereConditions = array()
								 , $select = '*'
								 , $orderBy = 'order_no ASC'
								 , $limit = ''
								 , $offset = ''
								 , $withAllFields = FALSE
								 , $withAllCategories = FALSE)
	{

		// 4 casi:
		//   A - ricerca per fields and values, tutti i campi e i valori (joins, subquery)
		//   B - ricerca per fields and values, nessun campo e valore (no joins, subquery)
		//   C - ricerca per colonna in contents, tutti i campi e i valori (joins, no subquery)
		//   D - ricerca per colonna in contents, nessun campo e valore (no joins, no subquery)

		// Prepare Order By
		if ($orderBy) {

			$orders  = explode(',', $orderBy);
			$orderBy = '';
			foreach ($orders as $ord) {
				$orderBy .= $this->_tableName . '.' . trim($ord) . ', ';
			}
			$orderBy = substr($orderBy, 0, -2);

		} else {

			$orderBy = $this->_tableName . '.' . 'order_no ASC';

		}

		// Prepare select
		$this->db->select($select);
		$this->db->from($this->_tableName);

		// Joins
		$this->_setJoinsContentList($withAllFields, $withAllCategories);

		// Prepare Basic Where Conditions
		$basicWhereString = $this->_getBasicWhereStringContentList($basicWhereConditions);
		$this->db->where($basicWhereString);

		// SUBQUERY TO SELECT content IDs
		if (count($whereConditions)) {

			$subqueryResults = $this->_getDeeperFilterResultIdsContentList($basicWhereString, $whereConditions);
			if ($subqueryResults) {
				$this->db->where($subqueryResults);
			} else {
				$this->db->where($this->_tableName . '.id IS NULL');
			}

		}

		$this->db->order_by($orderBy);

		if ($limit) {

			$this->db->limit($limit);

		}

		if ($offset) {

			$this->db->offset($offset);

		}

		$query = $this->db->get();
		//FM_Utility::debug($this->db->last_query());echo '<br><br><br>';
		return $query->result_array();

	}

	public function getContentListCount(array $basicWhereConditions = null, array $whereConditions = null,
		$withAllFields = false, $withAllCategories = false)
	{

		$this->db->select('COUNT(*) AS count');
		$this->db->from($this->_tableName);

		// Joins
		$this->_setJoinsContentList($withAllFields, $withAllCategories);

		$basicWhereString = $this->_getBasicWhereStringContentList($basicWhereConditions);
		$this->db->where($basicWhereString);

		// SUBQUERY TO SELECT content IDs
		if (count($whereConditions)) {
			$this->db->where($this->_getDeeperFilterResultIdsContentList($basicWhereString, $whereConditions));
		}

		$this->db->group_by($this->_tableName . '.id');

		return $this->db->get()->num_rows();
	}

	public function getContentListByIdContentType($idContentType, $orderBy = '', $limit = '', $offset = '')
	{
		$query = $this->_prepareQuery(
					  '*'
					, array('id_content_type' => $idContentType)
					, $orderBy
					, $limit
					, $offset);
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

	public function getLiveContentListByIdContentType($idContentType, $orderBy = '', $limit = '', $offset = '')
	{
		$query = $this->_prepareQuery(
					  '*'
					, array(
						  'id_content_type' => $idContentType
						, 'status'          => $this->statusLive
					)
					, $orderBy
					, $limit
					, $offset);
		return ($query->num_rows() > 0 ? $query->result_array() : NULL);
	}

	public function countLiveContentsByIdContentType($idContentType)
	{
		$this->db->from($this->_tableName)
				 ->where('id_content_type', $idContentType)
				 ->where('status', $this->statusLive);
		return $this->db->count_all_results();
	}

	// ===============  INSERTIONS ================== //

	public function insertContent(array $data)
	{
		$data['status'] = $this->statusOffline;
		$data['data_insert'] = $data['data_last_update'] = date('Y-m-d H:i:s');

		$this->db->insert($this->_tableName, $data);
		return $this->db->insert_id();
	}

	// ===============  UPDATES ================== //

	public function updateContent(array $data)
	{
		$id = $data['id'];
		unset($data['id']);

		$data['data_last_update'] = date('Y-m-d H:i:s');

		$this->db->where('id', $id);
		$this->db->update($this->_tableName, $data);
	}

	public function updateContentOrder($id, $orderNo)
	{
		$data = array('order_no' => $orderNo);
		$this->db->where('id', $id);
		$this->db->update($this->_tableName, $data);
	}

	public function setContentOffline($id)
	{
		$data = array('status' => $this->statusOffline);
		$this->db->where('id', $id);
		$this->db->update($this->_tableName, $data);
	}

	public function setContentLive($id)
	{
		$data = array('status' => $this->statusLive);
		$this->db->where('id', $id);
		$this->db->update($this->_tableName, $data);
	}

	public function updateSonsPath($absolutePath, $parentId)
	{
		$contentSearch = new FM_ContentSearch();
		$contentSearch->initialize('pages')
			->select('id', 'relative_path')
			->addWhereCondition('parent_page_id', '=', $parentId);

		$contentList = $contentSearch->getContentList();

		foreach ($contentList as $content) {
			$content['absolute_path'] = "{$absolutePath}{$content['relative_path']}/";

			$this->db->where('id', $content['id']);
			$this->db->update($this->_tableName, array(
				'absolute_path' => $content['absolute_path']
			));

			$this->updateSonsPath($content['absolute_path'], $content['id']);
		}
	}



	// ===============  DELETIONS ================== //

	public function deleteContentById($id)
	{
		return $this->db->delete($this->_tableName, array('id' => $id));
	}


}

?>