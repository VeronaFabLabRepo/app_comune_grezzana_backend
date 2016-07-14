<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Content
 *
 * Class for nanagement of the CMS
 *
 */
class FM_ContentSearch
{

	/**
	 * Paths for the uploads folders
	 */
	private $_fastIdContent              = '',
			$_idContentType              = '',
			$_contentTableName           = '',
			$_contentFieldTableName      = '',
			$_contentValueTableName      = '',
			$_categoriesTableName        = '',
			$_contentStatus              = '',
			$_contentColumns             = array(),
			$_fullInfo                   = false,
			$_allFields                  = false,
			$_allCategories              = false,
			$_select                     = '',
			$_basicWhereConds            = array(),
			$_whereConditions            = array(),
			$_order                      = 'order_no ASC, id ASC',
			$_limit                      = '',
			$_offset                     = '';

	private $_byCategories;


	public function __construct()
	{
		$this->ci =& get_instance();

		// Get the content columns
		$this->_contentColumns = $this->ci->config->item('content_columns', 'factotum');

		$this->_contentTableName           = $this->ci->fm_cms->getContentTableName();
		$this->_contentFieldTableName      = $this->ci->fm_cms->getContentFieldTableName();
		$this->_contentValueTableName      = $this->ci->fm_cms->getContentValueTableName();
		$this->_categoriesTableName        = $this->ci->fm_cms->getCategoriesTableName();

		$this->_select = $this->_contentTableName . '.id, '
					   . $this->_contentTableName . '.id_content_type, '
					   . $this->_contentTableName . '.title, '
					   . $this->_contentTableName . '.relative_path, '
					   . $this->_contentTableName . '.absolute_path, '
					   . $this->_contentTableName . '.order_no, '
					   ;
	}

	public function initialize($contentType)
	{
		// Get the content_type by the name
		if (is_numeric($contentType)) {

			$this->_idContentType = $contentType;

		} else {

			$contentType          = $this->ci->fm_cms->getContentTypeByName($contentType);
			$this->_idContentType = $contentType['id'];

		}

		if (!$this->_idContentType) {

			throw new Exception("Specify Content Type!", 1);

		}

		return $this;
	}

// 	public function initializeByPermalink($permalink)
// 	{
// 		$content = $this->ci->fm_cms->getContentByPermalink($permalink);
// 		if ($content) {
// 			$this->_idContentType = $content['id_content_type'];
// 			$this->_fastIdContent = $content['id'];
// 		}
// 		return $this;
// 	}

	public function onlyLiveContent($imSure = true)
	{
		$this->_contentStatus = ($imSure ? $this->ci->fm_cms->getContentStatusLive() : null);
		return $this;
	}

	public function fullInfo($getAllInfo = FALSE)
	{
		$this->_fullInfo = ($getAllInfo ? TRUE : FALSE);
		return $this;
	}

	public function withAllFieldsAndValues($allFields = FALSE)
	{
		$this->_allFields = ($allFields ? TRUE : FALSE);
		return $this;
	}

	public function withAllCategories($allCategories = FALSE)
	{
		$this->_allCategories = ($allCategories ? TRUE : FALSE);
		return $this;
	}

	public function select($select)
	{
		if ($select == '*') {

			$this->fullInfo(TRUE);

		} else {

			$this->_select = $select;

		}

		return $this;
	}

	public function addWhereCondition($fieldName, $operator, $value)
	{
		if (in_array($fieldName, $this->_contentColumns)) {

			$this->_basicWhereConds[$fieldName] = array(
				  'operator' => $operator
				, 'value'    => $value
			);

		} else {

			$whereConditions = array(
				  'operator' => $operator
				, 'value'    => $value
			);
			$this->_whereConditions[$fieldName] = $whereConditions;

		}

		return $this;
	}

	public function byCategories(array $categories = null)
	{
		if (!$categories) {
			$this->_byCategories = null;
		}

		$this->_byCategories = $categories;

		return $this;
	}

	public function order($order)
	{
		$this->_order = $order;
		return $this;
	}

	public function limit($limit)
	{
		$this->_limit = $limit;
		return $this;
	}

	public function offset($offset)
	{
		$this->_offset = $offset;
		return $this;
	}

	private function _setBasicWhereConditions()
	{
		$this->_basicWhereConds['id_content_type'] = array(
			'operator' => '='
		  , 'value'    => $this->_idContentType
		);

		if ($this->_fastIdContent != '') {

			$this->_basicWhereConds['id'] = array(
				'operator' => '='
			  , 'value'    => $this->_fastIdContent
			);

		}

		if ($this->_contentStatus != '') {

			$this->_basicWhereConds['status'] = array(
				'operator' => '='
			  , 'value'    => $this->_contentStatus
			);

		}

		if ($this->_byCategories) {

			$idsByCategories = $this->ci->content_category->getContentIdsByCategories($this->_byCategories);

			if (!isset($this->_basicWhereConds['id'])) {

				$this->_basicWhereConds['id'] = array(
					  'operator' => 'IN'
					, 'value'    => join(',', $idsByCategories)
				);

			} else if (is_array($this->_basicWhereConds['id']['value'])) {

				$this->_basicWhereConds['id']['value'] = array_intersect($this->_basicWhereConds['id']['value'], $idsByCategories);

			} else if (!in_array($this->_basicWhereConds['id']['value'], $idsByCategories)) {

				$this->_basicWhereConds['id']['value'] = false;

			}

			if (!$this->_basicWhereConds['id']['value']) {

				$this->_basicWhereConds['id']['operator'] = '=';
				$this->_basicWhereConds['id']['value'] = -1;

			}

		}

		return $this;
	}

	/**
	 * Retrieve the content list by content_type and some where condition
	 *
	 * @param	array          The where conditions array
	 * @return	array          Return an array of content list
	 */
	public function getContentList()
	{
		if (!$this->_idContentType) {
			return array();
		}

		try {

			// If the flag for the full info is setted to true, I retrieve also the data about the user, the date of last update, etc.
			if ($this->_fullInfo) {

				$this->_select .= $this->_contentTableName . '.id_user, '
								. $this->_contentTableName . '.status, '
								. $this->_contentTableName . '.data_insert, '
								. $this->_contentTableName . '.data_last_update, '
								;

			}

			if ($this->_select != '*') {

				$this->_select = substr($this->_select, 0, -2);

			}

			if ($this->_allFields) {

				$this->_select .= ', '
								. $this->_contentFieldTableName . '.name, '
								. $this->_contentFieldTableName . '.type, '
								. $this->_contentFieldTableName . '.linked_id_content_type, '
								. $this->_contentValueTableName . '.value, '
								;
				$this->_select = substr($this->_select, 0, -2);

			}

			if ($this->_allCategories) {

				$this->_select .= ', '
								. $this->_categoriesTableName . '.category_name, '
								. $this->_categoriesTableName . '.category_label, '
								;
				$this->_select = substr($this->_select, 0, -2);

			}

			$this->_setBasicWhereConditions();

			$contentList = $this->ci->content->getContentList( $this->_basicWhereConds
															 , $this->_whereConditions
															 , $this->_select
															 , $this->_order
															 , $this->_limit
															 , $this->_offset
															 , $this->_allFields
															 , $this->_allCategories);

			if ($this->_allFields) {

				$contentList = $this->_parseContentFields($contentList);

			}

			if ($this->_allCategories) {


			}

			return $contentList;

		} catch (Exception $ex) {

			FM_Utility::debug($ex);die;

		}
	}

	public function getContentListCount()
	{
		return $this->ci->content->getContentListCount($this->_basicWhereConds, $this->_whereConditions, $this->_allFields, $this->_allCategories);
	}

	public function getContent()
	{
		// Applying a limit prevents external fields load.. Why?!
		// $this->limit(1);
		$content = current($this->getContentList());

		return $content ? $content : null;
	}

	private function _parseContentFields($contentFieldsList)
	{
		if (count($contentFieldsList) > 0) {

			$contentList = array();

			foreach ($contentFieldsList as $contentField) {

				// Set a limit into the PHP code
				if ($this->_limit && count($this->_whereConditions) == 0) {

					if (count($contentList) == $this->_limit) {

						return $contentList;

					}

				}

				if (!isset($contentList[$contentField['id']])) {

					$contentList[$contentField['id']] = array(
						  'id'               => $contentField['id']
						, 'id_content_type'  => $contentField['id_content_type']
						, 'title'            => $contentField['title']
						, 'relative_path'    => $contentField['relative_path']
						, 'absolute_path'    => $contentField['absolute_path']
					);

					if ($this->_fullInfo) {

						$contentList[$contentField['id']]['id_user']          = $contentField['id_user'];
						$contentList[$contentField['id']]['status']           = $contentField['status'];
						$contentList[$contentField['id']]['data_insert']      = $contentField['data_insert'];
						$contentList[$contentField['id']]['data_last_update'] = $contentField['data_last_update'];
						$contentList[$contentField['id']]['order_no']         = $contentField['order_no'];

					}

				}

				// Check content field type
				if ($contentField['type'] == 'gallery') {

					// Load the gallery for this content
					$gallery = $this->ci->fm_cms->getAllContentAttachmentsByIdContent($contentField['id']);
					$contentList[$contentField['id']][$contentField['name']] = $gallery;

				} elseif ($contentField['type'] == 'linked_content') {

					// Load the linked content
					$contentSearch = new FM_ContentSearch();
					$contentLinked = $this->ci->fm_cms->getContentById($contentField['value']);

					$contentSearch->initialize($contentLinked['id_content_type'])
								  ->fullInfo(TRUE)
								  ->addWhereCondition('id', '=', $contentField['value']);

					$contentLinked = current($contentSearch->getContentList());
					$contentList[$contentField['id']][$contentField['name']] = $contentLinked;

				} elseif ($contentField['type'] == 'multiple_linked_content') {

					// Load the linked content
					$contentSearch = new FM_ContentSearch();
					$multipleLinkedContent = $this->ci->fm_cms->getContentById($contentField['value']);

					$contentSearch->initialize($multipleLinkedContent['id_content_type'])
								  ->fullInfo(TRUE)
								  ->withAllFieldsAndValues(TRUE)
								  ->addWhereCondition('id', 'IN', $contentField['value']);

					$multipleLinkedContent = $contentSearch->getContentList();
					$contentList[$contentField['id']][$contentField['name']] = $multipleLinkedContent;

				} else {

					$contentList[$contentField['id']][$contentField['name']] = $contentField['value'];

				}

			}

			return $contentList;

		} else {

			return array();

		}
	}

	//TODO complete this part
	private function _parseContentCategories($contentCategoriesList)
	{
		if (count($contentFieldsList) > 0) {

			//$contentList = array();

			return $contentList;

		} else {

			return array();

		}
	}
}