<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Content
 *
 * Class for nanagement of the CMS
 *
 */
class FM_Cms
{
	private $_fieldsOnFilesystem = array(
		  'image_upload'
		, 'file_upload'
		, 'gallery'
	);

	private $_dynamicHTMLtextarea = 'xhtml_textarea';

	/**
	 * Paths for the uploads folders
	 */
	private $_uploadPath                 = '',
			$_tmpUploadPath              = '',
			$_imagesURL                  = '',
			$_contentTableName           = '',
			$_contentFieldTableName      = '',
			$_contentValueTableName      = '',
			$_contentCategoriesTableName = '',
			$_categoriesTableName        = '';

	function __construct()
	{
		$this->ci =& get_instance();

		$this->_uploadPath    = $this->ci->config->item('upload_path',     'factotum');
		$this->_tmpUploadPath = $this->ci->config->item('tmp_upload_path', 'factotum');
		$this->_imagesURL     = $this->ci->config->item('images_url',      'factotum');

		$this->_contentTableName           = $this->ci->config->item('contents_tbl',           'factotum');
		$this->_contentFieldTableName      = $this->ci->config->item('content_fields_tbl',     'factotum');
		$this->_contentValueTableName      = $this->ci->config->item('content_values_tbl',     'factotum');
		$this->_contentCategoriesTableName = $this->ci->config->item('content_categories_tbl', 'factotum');
		$this->_categoriesTableName        = $this->ci->config->item('categories_tbl',         'factotum');
	}









	// ===============  CONTENT TYPE WRAPPER ==================//

	/**
	 * Retrieve a content type by the id
	 *
	 * @param	int    The id of the content type
	 * @return	array  The associative array of the content type
	 */
	public function getContentTypeById($idContentType)
	{
		return $this->ci->content_type->getContentTypeById($idContentType);
	}

	/**
	 * Retrieve a content type by the name
	 *
	 * @param	string The unique name of the content type
	 * @return	array  The associative array of the content type
	 */
	public function getContentTypeByName($name)
	{
		return $this->ci->content_type->getContentTypeByName($name);
	}

	/**
	 * Retrieve the content type list
	 *
	 * @param	null|string The unique name of the content type to exclude from the list
	 * @return	array  The associative array of the content type
	 */
	public function getContentTypes($exclude = null)
	{
		return $this->ci->content_type->getAllContentType($exclude);
	}

	/**
	 * Check if a content type exist
	 *
	 * @param	string The unique name of the content type
	 * @return	array  The associative array of the content type
	 */
	public function isContentTypeAvailable($contentType)
	{
		return $this->ci->content_type->isContentTypeAvailable($contentType);
	}

	/**
	 * Insert a new content type
	 *
	 * @param	string The unique name of the content type
	 * @return	bool   Return TRUE on success
	 */
	public function insertContentType($contentType)
	{
		return $this->ci->content_type->insertContentType($contentType);
	}

	/**
	 * Update a content type by the id
	 *
	 * @param	string The unique name of the content type
	 * @param	int    The id of the content type
	 * @return	bool   Return TRUE on success
	 */
	public function updateContentType($contentType, $idContentType)
	{
		return $this->ci->content_type->updateContentType($contentType, $idContentType);
	}

	public function updateContentTypeOrder($idContentType, $orderNo)
	{
		return $this->ci->content_type->updateContentTypeOrder($idContentType, $orderNo);
	}

	/**
	 * Delete a content type by the id
	 * Before delete all the contents for this content type
	 * Then delete all the content fields for this content type
	 * And finally delete the content type
	 *
	 * @param	int    The id of the content type
	 * @return	bool   Return the id of the content type deleted
	 */
	public function deleteContentTypeById($idContentType)
	{
		$contentType = $this->getContentTypeById($idContentType);
		$contentSearch = new FM_ContentSearch();
		$contentSearch->initialize($contentType['content_type'])
					  ->select('*')
					  ;
		$contentList = $contentSearch->getContentList();

		if ($contentType) {

			if ($contentList && count($contentList) > 0) {

				foreach ($contentList as $content) {

					$this->deleteContentById($content['id']);

				}

			}

			$this->deleteContentFieldsByIdContentType($idContentType);

			$this->ci->fm_users_management->deleteUserCapabilitiesByIdContentType($idContentType);

			$this->deleteCategoryByIdContentType($idContentType);

			$this->ci->content_type->deleteContentTypeById($idContentType);

			return $idContentType;

		} else {

			return null;

		}
	}










	// ===============  CATEGORY WRAPPER ==================//

	/**
	 * Retrieve a content category by the id
	 *
	 * @param	int    The id of the content category
	 * @return	array  The associative array of the content category
	 */
	public function getCategoryById($idContentCategory)
	{
		return $this->ci->category->getCategoryById($idContentCategory);
	}

	/**
	 * Retrieve a category by the name
	 *
	 * @param	string The name of the content category
	 * @return	array  The associative array of the content category
	 */
	public function getCategoryByName($name)
	{
		return $this->ci->category->getCategoryByName($name);
	}

	/**
	 * Retrieve a category by the content type
	 *
	 * @param	intger The id of the content type
	 * @return	array  The associative array of the content category
	 */
	public function getCategoriesByContentType($idContentType)
	{
		return $this->ci->category->getCategoriesByContentType($idContentType);
	}

	/**
	 * Retrieve the categories list
	 *
	 * @return	array  The associative array of the content categories
	 */
	public function getCategories()
	{
		return $this->ci->category->getCategories();
	}

	/**
	 * Check if a category exist
	 *
	 * @param	string The unique name of the content category
	 * @return	array  The associative array of the content category
	 */
	public function isCategoryAvailable($contentCategoryName)
	{
		return $this->ci->category->isCategoryAvailable($contentCategoryName);
	}

	/**
	 * Insert a new category
	 *
	 * @param	int    The id of the content type of the category
	 * @param	string The unique name of the content category
	 * @param	string The label of the content category
	 * @return	bool   Return TRUE on success
	 */
	public function insertCategory($idContentType, $categoryName, $categoryLabel)
	{
		return $this->ci->category->insertCategory($idContentType, $categoryName, $categoryLabel);
	}

	/**
	 * Update a category by the id
	 *
	 * @param	int    The id of the content type of the category
	 * @param	string The unique name of the content category
	 * @param	string The label of the content category
	 * @param	int    The id of the content type
	 * @return	bool   Return TRUE on success
	 */
	public function updateCategory($idContentCategory, $idContentType, $categoryName, $categoryLabel)
	{
		return $this->ci->category->updateCategory($idContentCategory, $idContentType, $categoryName, $categoryLabel);
	}

	public function updateCategoryOrder($idContentCategory, $orderNo)
	{
		return $this->ci->category->updateCategoryOrder($idContentCategory, $orderNo);
	}

	/**
	 * Delete a category by the id
	 *
	 * @param	int    The id of the content category
	 * @return	bool   Return the id of the content type deleted
	 */
	public function deleteCategoryById($id)
	{
		return $this->ci->category->deleteCategoryById($id);
	}

	public function deleteCategoryByIdContentType($idContentType)
	{
		return $this->ci->category->deleteCategoryByIdContentType($idContentType);
	}





	// ===============  CONTENT CATEGORY WRAPPER ==================//
	public function getCategoriesTableName()
	{
		return $this->_categoriesTableName;
	}

	public function getContentCategoriesTableName()
	{
		return $this->_contentCategoriesTableName;
	}

	public function getAllContentCategories()
	{
		return $this->ci->content_category->getCategories();
	}

	public function getAllContentCategoriesByIdContent($idContent)
	{
		return $this->ci->content_category->getAllContentCategoriesByIdContent($idContent);
	}

	public function insertContentCategory($idContent, $idCategory)
	{
		return $this->ci->content_category->insertContentCategory($idContent, $idCategory);
	}

	public function deleteContentCategoryByIdContentAndIdCategory($idContent, $idCategory)
	{
		return $this->ci->content_category->deleteContentCategoryByIdContentAndIdCategory($idContent, $idCategory);
	}

	public function deleteContentCategoryByIdCategory($idCategory)
	{
		return $this->ci->content_category->deleteContentCategoryByIdCategory($idCategory);
	}

	public function deleteContentCategoryByIdContent($idContent)
	{
		return $this->ci->content_category->deleteContentCategoryByIdContent($idContent);
	}




	// ===============  CONTENT MODEL WRAPPER ==================//

	/**
	 * Retrieve the content table name
	 *
	 * @return	string  The content table name
	 */
	public function getContentTableName()
	{
		return $this->_contentTableName;
	}

	/**
	 * Retrieve all the content columns
	 *
	 * @return	array  An array with the content columns
	 */
	public function getContentColumns()
	{
		return $this->ci->content->getContentColumns();
	}

	/**
	 * Retrieve a content by the id
	 *
	 * @param	int    The id of the content
	 * @return	array  The associative array of the content
	 */
	public function getContentById($idContent)
	{
		return $this->ci->content->getContentById($idContent);
	}

	/**
	 * Retrieve a live content by the id
	 *
	 * @param	int    The id of the content
	 * @return	array  The associative array of the content
	 */
	public function getLiveContentById($id)
	{
		return $this->ci->content->getLiveContentById($id);
	}

	/**
	 * Count all the lives content by the id_content_type
	 *
	 * @param	int    The id of the content type
	 * @return	array  The number of rows
	 */
	public function countLiveContentsByIdContentType($idContentType)
	{
		return $this->ci->content->countLiveContentsByIdContentType($idContentType);
	}

// 	/**
// 	 * Retrieve a content by the permalink
// 	 *
// 	 * @param	string The permalink of the content to search
// 	 * @return	array  The associative array of the content
// 	 */
// 	public function getContentByPermalink($permalink)
// 	{
// 		return $this->ci->content->getContentByPermalink($permalink);
// 	}

// 	/**
// 	 * Retrieve a live content by the permalink
// 	 *
// 	 * @param	string The permalink of the content to search
// 	 * @return	array  The associative array of the content
// 	 */
// 	public function getLiveContentByPermalink($permalink)
// 	{
// 		return $this->ci->content->getLiveContentByPermalink($permalink);
// 	}

	/**
	 * Get all the possible content status
	 *
	 * @return	array Return an array with the possible content status
	 */
	public function getContentStatuses()
	{
		return $this->ci->content->status;
	}

	/**
	 * Return the status live
	 *
	 * @return	string Return the status live
	 */
	public function getContentStatusLive()
	{
		return $this->ci->content->statusLive;
	}

	/**
	 * Return the status offline
	 *
	 * @return	string Return the status offline
	 */
	public function getContentStatusOffline()
	{
		return $this->ci->content->statusOffline;
	}

	/**
	 * Return the reverse status
	 *
	 * @param	string   The status from which understand the opposite
	 * @return	string   Return the reverse status
	 */
	public function getReverseContentStatus($status)
	{
		if ($status == $this->getContentStatusLive()) {

			return $this->getContentStatusOffline();

		} else if ($status == $this->getContentStatusOffline()) {

			return $this->getContentStatusLive();

		} else {

			throw new Exception("Status not finded!", 1);

		}
	}

	/**
	 * Insert a content
	 *
	 * @param	array   Content data
	 * @return	string  Insert a new content
	 *
	 */
	public function insertContent(array $data)
	{
		return $this->ci->content->insertContent($data);
	}

	/**
	 * Update a content
	 *
	 * @param	array     Content data
	 * @return	string    Insert a new content
	 *
	 */
	public function updateContent(array $data)
	{
		return $this->ci->content->updateContent($data);
	}

	public function updateContentOrder($idContent, $orderNo)
	{
		return $this->ci->content->updateContentOrder($idContent, $orderNo);
	}

	/**
	 * Set a content live
	 *
	 * @param	int    The id of the content to set live
	 * @return	bool   Return TRUE on success
	 *
	 */
	public function setContentLive($id)
	{
		return $this->ci->content->setContentLive($id);
	}

	/**
	 * Set a content offline
	 *
	 * @param	int    The id of the content to set offline
	 * @return	bool   Return TRUE on success
	 *
	 */
	public function setContentOffline($id)
	{
		return $this->ci->content->setContentOffline($id);
	}

	/**
	 * Set the status content to the opposite
	 *
	 * @param	int    The id of the content to set offline
	 * @return	bool   Return TRUE on success
	 *
	 */
	public function updateReverseContentStatus($idContent)
	{
		$content = $this->getContentById($idContent);

		if ($content['status'] == $this->ci->content->statusLive) {

			$this->setContentOffline($idContent);
			return $this->ci->content->statusOffline;

		} else if ($content['status'] == $this->ci->content->statusOffline) {

			$this->setContentLive($idContent);
			return $this->ci->content->statusLive;

		} else {

			throw new Exception("Error! Status not recognized.", 1);

		}
	}

	/**
	 * Delete a content
	 *
	 * @param	int    The id of the content to delete
	 * @return	bool   Return TRUE on success
	 *
	 */
	public function deleteContentById($id)
	{
		$storeFolder = $this->ci->getUploadPath() . '/' . $id . '/';

		// Delete all the content values
		// 1 - delete all the files stored on the file system
		// 2 - delete the rows
		$contentValues = $this->getAllContentValuesByIdContent($id);

		if (count($contentValues) > 0) {

			foreach ($contentValues as $cValue) {

				$this->deleteContentValueAttachmentById($cValue['id']);

			}

			$this->deleteContentValuesByIdContent($id);
		}


		// Delete all the content attachments
		// 1 - delete all the files stored on the file system
		// 2 - delete the rows
		$contentAttachs = $this->getAllContentAttachmentsByIdContent($id);

		if (count($contentAttachs) > 0) {

			foreach ($contentAttachs as $cAttach) {

				$this->deleteContentAttachmentById($cAttach['id']);

			}

		}

		if (file_exists($storeFolder . 'thumbs/')) {

			delete_files($storeFolder . 'thumbs/', TRUE);
			rmdir($storeFolder . 'thumbs/');

		}

		if (file_exists($storeFolder)) {

			delete_files($storeFolder, TRUE);
			rmdir($storeFolder);

		}

		// Delete content categories by Content ID
		$this->deleteContentCategoryByIdContent($id);

		// Delete the content
		$this->ci->content->deleteContentById($id);

	}









	// ===============  CONTENT FIELD WRAPPER ==================//

	/**
	 * Retrieve the content field table name
	 *
	 * @return	string  The content field table name
	 */
	public function getContentFieldTableName()
	{
		return $this->_contentFieldTableName;
	}

	public function isFilesystemField($field)
	{
		if (in_array($field['type'], $this->_fieldsOnFilesystem)) {

			return TRUE;

		}

		return FALSE;
	}

	/**
	 * Retrieve a content field by the id
	 *
	 * @param	int    The id of the content field
	 * @return	array  The associative array of the content field
	 */
	public function getContentFieldById($idContentField)
	{
		return $this->ci->content_field->getContentFieldById($idContentField);
	}

	/**
	 * Retrieve all the content fields
	 *
	 * @return	array  The associative array of all the content fields
	 */
	public function getAllContentFields()
	{
		return $this->ci->content_field->getAllContentFields;
	}

	/**
	 * Retrieve the content fields by the id content type
	 *
	 * @param	int    The id of the content type
	 * @return	array  The associative array of the content field
	 */
	public function getContentFieldsByIdContentType($idContentType, $orderBy = '')
	{
		return $this->ci->content_field->getContentFieldsByIdContentType($idContentType, $orderBy);
	}


	/**
	 * Retrieve the content fields by the id content type
	 *
	 * @param	int    The id of the content type
	 * @return	array  The associative array of the content field
	 */
	public function getContentFieldsCountByIdContentType($idContentType)
	{
		return $this->ci->content_field->getContentFieldsCountByIdContentType($idContentType);
	}

	/**
	 * Retrieve the content fields by the id content type and an array of the names of fields
	 *
	 * @param	int    The id of the content type
	 * @param   array  The names of the content fields
	 * @return	array  The associative array of the content field
	 */
	public function getContentFieldsByTypeAndNames($idContentType, $names)
	{
		return $this->ci->content_field->getContentFieldsByTypeAndNames($idContentType, $names);
	}

	public function getContentFieldsAndValues($idContentType, $whereConditions, $selectFields = '*')
	{
		return $this->ci->content_field->getContentFieldsAndValues($idContentType, $whereConditions, $selectFields);
	}

	/**
	 * Insert a content field
	 *
	 * @param	int       The id of the content type
	 * @param	string    The field name (unique for all the cms)
	 * @param	string    The type of the field (input, textarea, xhtml_textarea, radio, select, checkbox, etc.)
	 * @param   string    The label of the field
	 * @param   bool      Field mandatory or not
	 * @param   null|int  The order of the field
	 * @param   array     The extra params for the fields (hint, file/image managemente, etc.)
	 * @return	int       The id of the insertion
	 */
	public function insertContentField($idContentType, $name, $type, $label, $mandatory, $order_no = '', $extra = array())
	{
		return $this->ci->content_field->insertContentField($idContentType, $name, $type, $label, $mandatory, $order_no, $extra);
	}

	/**
	 * Update a content field
	 *
	 * @param	int       The id of the content field to update
	 * @param	string    The field name (unique for all the cms)
	 * @param	string    The type of the field (input, textarea, xhtml_textarea, radio, select, checkbox, etc.)
	 * @param   string    The label of the field
	 * @param   bool      Field mandatory or not
	 * @param   null|int  The order of the field
	 * @param   array     The extra params for the fields (hint, file/image managemente, etc.)
	 * @return	int       The id of the insertion
	 */
	public function updateContentField($id, $name, $type, $label, $mandatory, $order_no = '', $extra = array())
	{
		return $this->ci->content_field->updateContentField($id, $name, $type, $label, $mandatory, $order_no, $extra);
	}

	public function updateContentFieldOrder($idContentField, $orderNo)
	{
		return $this->ci->content_field->updateContentFieldOrder($idContentField, $orderNo);
	}

	/**
	 * Delete a content field by the id content type
	 *
	 * @param	int    The id of the content type
	 * @return	array  Return TRUE on success
	 */
	public function deleteContentFieldById($id)
	{
		// Retrieve the content field
		$cField = $this->getContentFieldById($id);

		// The field impact on file system?
		if ($this->isFilesystemField($cField)) {

			// Get all the files saved on the content values and delete
			$cValues = $this->getContentValuesByIdContentField($cField['id']);

			if (count($cValues) > 0) {

				foreach ($cValues as $contentValue) {

					$storeFolder = $this->ci->getUploadPath() . '/' . $contentValue['id_content'] . '/';

					// Delete the images and files
					if (file_exists($storeFolder . $contentValue['value'])) {

						unlink($storeFolder . $contentValue['value']);

					}

					if (file_exists($storeFolder . 'thumbs/' . $contentValue['value'])) {

						unlink($storeFolder . 'thumbs/' . $contentValue['value']);

					}

				}

			}

		}

		$this->deleteContentValuesByIdContentField($id);

		return $this->ci->content_field->deleteContentFieldById($id);
	}

	/**
	 * Delete a content field by the id content type
	 *
	 * @param	int    The id of the content type
	 * @return	array  Return TRUE on success
	 */
	public function deleteContentFieldsByIdContentType($idContentType)
	{
		$cFields = $this->getContentFieldsByIdContentType($idContentType);

		if (count($cFields) > 0) {

			foreach ($cFields as $contentField) {

				$this->deleteContentFieldById($contentField['id']);

			}

			return $this->ci->content_field->deleteContentFieldsByIdContentType($idContentType);

		}

		return NULL;
	}

	public function checkContentFieldName($name)
	{
		$columns = $this->getContentColumns();
		if (!in_array($name, $columns)) {
			return TRUE;
		}
		return FALSE;
	}

	public function isContentFieldNameAvailable($name)
	{
		return $this->ci->content_field->isContentFieldNameAvailable($name);
	}



	// ===============  CONTENT VALUE WRAPPER ==================//

	/**
	 * Retrieve the content value table name
	 *
	 * @return	string  The content value table name
	 */
	public function getContentValueTableName()
	{
		return $this->_contentValueTableName;
	}

	/**
	 * Retrieve a content value by the id
	 *
	 * @param	int    The id of the content value
	 * @return	array  The associative array of the content value
	 */
	public function getContentValueById($id)
	{
		return $this->ci->content_value->getContentValueById($id);
	}

	/**
	 * Retrieve all the content values by the id content
	 *
	 * @param	int    The id of the content
	 * @return	array  The associative array of the content values
	 */
	public function getAllContentValuesByIdContent($idContent)
	{
		return $this->ci->content_value->getAllContentValuesByIdContent($idContent);
	}

	/**
	 * Retrieve all the content values by the id content field
	 *
	 * @param	int    The id of the content field
	 * @return	array  The associative array of the content values
	 */
	public function getContentValuesByIdContentField($idContentField)
	{
		return $this->ci->content_value->getContentValuesByIdContentField($idContentField);
	}

	/**
	 * Retrieve all the content values by the id content field and the id content
	 *
	 * @param	int    The id of the content field
	 * @param	int    The id of the content
	 * @return	array  The associative array of the content values
	 */
	public function getContentValueByIdContentFieldAndIdContent($idContentField, $idContent)
	{
		return $this->ci->content_value->getContentValueByIdContentFieldAndIdContent($idContentField, $idContent);
	}

	/**
	 * Insert a content value
	 *
	 * @param	int       The id of the content
	 * @param	int       The id of the content field
	 * @param	string    The value
	 * @return	int       The id of the insertion
	 */
	public function insertContentValue($idContent, $idContentField, $value)
	{
		return $this->ci->content_value->insertContentValue($idContent, $idContentField, $value);
	}

	/**
	 * Insert a content value
	 *
	 * @param	int       The id of the content
	 * @param	string    The value
	 * @return	bool      Return TRUE on success
	 */
	public function updateContentValue($id, $value)
	{
		return $this->ci->content_value->updateContentValue($id, $value);
	}

	/**
	 * Delete a content value by the id
	 *
	 * @param	int    The id of the content value
	 * @return	array  Return TRUE on success
	 */
	public function deleteContentValueById($id)
	{
		return $this->ci->content_value->deleteContentValueById($id);
	}

	/**
	 * Delete the content value attachment by the id content value
	 *
	 * @param	int    The id of the content
	 * @return	array  Return TRUE on success
	 */
	public function deleteContentValueAttachmentById($idContentValue)
	{
		$contentValue = $this->getContentValueById($idContentValue);
		if ($contentValue['value'] != '') {

			$storeFolder  = $this->ci->getUploadPath() . '/' . $contentValue['id_content'] . '/';

			// Delete the images and files
			if (file_exists($storeFolder . $contentValue['value'])) {

				unlink($storeFolder . $contentValue['value']);

			}

			if (file_exists($storeFolder . 'thumbs/' . $contentValue['value'])) {

				unlink($storeFolder . 'thumbs/' . $contentValue['value']);

			}

			return $this->ci->content_value->deleteContentValueById($idContentValue);
		}
	}

	/**
	 * Delete all the content values by the id content
	 *
	 * @param	int    The id of the content
	 * @return	array  Return TRUE on success
	 */
	public function deleteContentValuesByIdContent($idContent)
	{
		return $this->ci->content_value->deleteContentValuesByIdContent($idContent);
	}

	/**
	 * Delete all the content values by the id content field
	 *
	 * @param	int    The id of the content field
	 * @return	array  Return TRUE on success
	 */
	public function deleteContentValuesByIdContentField($idContentField)
	{
		return $this->ci->content_value->deleteContentValuesByIdContentField($idContentField);
	}










	// ===============  CONTENT ATTACHMENT WRAPPER ==================//

	/**
	 * Retrieve a content attachment by the id
	 *
	 * @param	int    The id of the content attachment
	 * @return	array  The associative array of the content attachment
	 */
	public function getContentAttachmentById($idContentAttachment)
	{
		return $this->ci->content_attachment->getContentAttachmentById($idContentAttachment);
	}

	/**
	 * Retrieve all the content attachments by the id content
	 *
	 * @param	int    The id of the content
	 * @return	array  The associative array of the content attachments
	 */
	public function getAllContentAttachmentsByIdContent($idContent)
	{
		return $this->ci->content_attachment->getAllContentAttachmentsByIdContent($idContent);
	}

	/**
	 * Insert a content attachment
	 *
	 * @param	int       The id of the content
	 * @param	int       The id of the content field
	 * @param	string    The title of the attachment
	 * @param	string    The filename
	 * @return	int       The id of the insertion
	 */
	public function insertContentAttachment($idContent, $idContentField, $title, $filename)
	{
		return $this->ci->content_attachment->insertContentAttachment($idContent, $idContentField, $title, $filename);
	}

	/**
	 * Update a content attachment by the id
	 *
	 * @param	int       The id of the content attachment
	 * @param	string    The title of the attachment
	 * @param	string    The filename
	 * @param	null|int  The order of the content attachment
	 * @return	int       Return TRUE on success
	 */
	public function updateContentAttachment($id, $title, $filename, $order = '')
	{
		return $this->ci->content_attachment->updateContentAttachment($id, $title, $filename, $order);
	}

	/**
	 * Update the order of a content attachment by the id
	 *
	 * @param	int  The id of the content attachment
	 * @param	int  The order of the content attachment
	 * @return	int  Return TRUE on success
	 */
	public function updateContentAttachmentOrder($id, $order)
	{
		return $this->ci->content_attachment->updateContentAttachmentOrder($id, $order);
	}

	/**
	 * Delete a content attachment by the id
	 *
	 * @param	int  The id of the content attachment to delete
	 * @return	int  Return TRUE on success
	 */
	public function deleteContentAttachmentById($id)
	{
		$contentAttachment = $this->getContentAttachmentById($id);

		if ($contentAttachment) {

			$storeFolder  = $this->ci->getUploadPath() . '/' . $contentAttachment['id_content'] . '/';

			// Delete the images and files
			if (file_exists($storeFolder . $contentAttachment['filename'])) {

				unlink($storeFolder . $contentAttachment['filename']);

			}

			if (file_exists($storeFolder . 'thumbs/' . $contentAttachment['filename'])) {

				unlink($storeFolder . 'thumbs/' . $contentAttachment['filename']);

			}

		}

		return $this->ci->content_attachment->deleteContentAttachmentById($id);

	}

	/**
	 * Delete a list of content attachments by the content id
	 *
	 * @param	int  The id of the content
	 * @return	int  Return TRUE on success
	 */
	public function deleteContentAttachmentsByIdContent($idContent)
	{
		return $this->ci->content_attachment->deleteContentAttachmentsByIdContent($idContent);
	}







	/**
	 * HOW IT WORKS
	 * 1 - If the idContent is set retrieve all the fields and values for this ID, otherwise retrieve only the fields to show
	 * 2 - On save check the errors on the "normal fields"; for the attachments save everything into the tmp folder and check for errors
	 * 3 - If there aren't errors save the content (insert or update), save the values and moving the attachments from the tmp folder to the content folder
	 * 4 - Process the images, if any
	 * 5 - Empty the TMP folder
	 * 6 - Redirect to the result page
	 *
	 */
	public function saveContent($contentType, $content = null)
	{
		$result     = array();
		$uploadData = array();

		$redirectURL = '/admin/contents/';

		$oldValues = array();
		$errors    = array();

		$result['data_saved'] = FALSE;

		$contentTypeName = $contentType;
		$contentType     = $this->getContentTypeByName($contentType);
		$fields          = $this->getContentFieldsByIdContentType($contentType['id']);
		$categories      = $this->getCategoriesByContentType($contentType['id']);

		$oldValues['title']      = '';
		$oldValues['relative_path']  = '';
		$absolutePath = '';
		$oldValues['categories'] = array();

		if ($content) {

			$idContent = $content['id'];

		} else {

			$idContent = null;

		}

		if ($contentTypeName === 'pages') {
			$menu = $this->getMenu(true);
			$flatPagesTree = $this->getFlatPagesTree($menu);
			$flatPagesTitles = array();
			foreach ($flatPagesTree as $id => $page) {
				$flatPagesTitles[$id] = ltrim(str_repeat('--', $page['level']) . ' ', ' ') . "{$page['title']}";
			}

			// TODO another day
			// if ($idContent && isset($flatPagesTitles[$idContent])) {
			// 	unset($flatPagesTitles[$idContent]);
			// }
		}

		// ================ 1 - RETRIEVING ALL THE CONTENT COMPONENTS (FIELDS, VALUES, ATTACHS, ETC.) ===============

		// Retrieve the content if the idContent is set
		if ($idContent) {

			$this->data['uploadUrl'] = '/uploads/' . $idContent . '/';

			$content           = $this->getContentById($idContent);
			$contentValues     = $this->getAllContentValuesByIdContent($idContent);
			$contentCategories = $this->getAllContentCategoriesByIdContent($idContent);

			$oldValues['title']      = $content['title'];
			$oldValues['relative_path']  = $content['relative_path'];
			$oldValues['categories'] = $contentCategories;

			if (count($oldValues['categories']) > 0) {

				$tmp = array();
				foreach ($oldValues['categories'] as $category) {

					$tmp[] = $category['id_category'];

				}
				$oldValues['categories'] = $tmp;

			}

			$tmp = array();
			if (count($contentValues) > 0) {

				foreach ($contentValues as $value) {

					$tmp[$value['id_content_field']] = $value;

				}
			}
			$contentValues = $tmp;

		} else {

			$content            = array();
			$contentValues      = array();
			$contentCategories  = array();

		}

		$contentAttachments = array();

		// Fields transformations (options, images, etc.)
		if (count($fields) > 0) {

			foreach ($fields as $index => $field) {

				if (isset($contentValues[$field['id']]['value']) && $contentValues[$field['id']]['value'] != '') {

					$field['value'] = $contentValues[$field['id']]['value'];

				} else {

					$field['value'] = '';

				}

				if (in_array($field['type'], array('radio', 'select', 'multiselect', 'checkbox', 'multicheckbox'))) {

					$field['options'] = FM_Utility::convertOptionsTextToArray($field['options']);

				}

				if (in_array($field['type'], array('multiselect', 'multicheckbox', 'multiple_linked_content')) && $field['value'] != '') {

					$field['value'] = FM_Utility::convertMultiOptionsTextToArray($contentValues[$field['id']]['value']);

				}

				if ($field['type'] == 'file_upload') {

					if (isset($contentValues[$field['id']])) {

						$field['attach'] = array(
							  'id_content_value' => $contentValues[$field['id']]['id']
							, 'file'             => $this->data['uploadUrl'] . $contentValues[$field['id']]['value']
						);

					} else {

						$field['attach'] = array();

					}

				}

				if ($field['type'] == 'image_upload') {

					if (isset($contentValues[$field['id']])) {

						$field['attach'] = array(
							  'id_content_value' => $contentValues[$field['id']]['id']
							, 'zoom_image'       => $this->data['uploadUrl'] . $contentValues[$field['id']]['value']
							, 'thumb_image'      => $this->data['uploadUrl'] . 'thumbs/' . $contentValues[$field['id']]['value']
						);

					} else {

						$field['attach'] = array();

					}

				}

				if ($field['type'] == 'gallery') {

					$contentAttachments[$field['name']] = $this->getAllContentAttachmentsByIdContent($idContent);

					$field['attachs'] = array();
					if (count($contentAttachments[$field['name']]) > 0) {

						foreach ($contentAttachments[$field['name']] as $att) {

							array_push($field['attachs'], array(
								  'id_content_attachment' => $att['id']
								, 'zoom_image'            => $this->data['uploadUrl'] . $att['filename']
								, 'thumb_image'           => $this->data['uploadUrl'] . 'thumbs/' . $att['filename']
							));

						}

					}

				}


				if ($field['type'] == 'linked_content' || $field['type'] == 'multiple_linked_content') {

					$ctypeToSelect = $this->getContentTypeById($field['linked_id_content_type']);

					$contentSearch = new FM_ContentSearch();
					$contentSearch->initialize($ctypeToSelect['content_type'])
								  ->select('*')
								  ->fullInfo(TRUE)
								  ;
					$contentList = $contentSearch->getContentList();

					$options = array();
					foreach ($contentList as $cont) {

						$options[$cont['id']] = $cont['title'];

					}
					$field['options'] = $options;

				}


				if ($field['name'] === 'parent_page_id' && $contentTypeName === 'pages' && $field['type'] == 'select') {
					$field['options'] = $flatPagesTitles;
				}


				// PAGE FIELD MANAGEMENT
				if ($field['name'] == 'content_types_list' && $contentTypeName == 'pages' && $field['type'] == 'select') {

					$cTypes = $this->getContentTypes('pages');

					$options = array();
					if (count($cTypes) > 0) {

						foreach ($cTypes as $ct) {

							$options[$ct['content_type']] = $ct['content_type'];

						}

					}
					$field['options'] = $options;

					// If the value exist, I load the options for the content list
					if ($field['value'] != '') {

						$contentListOptions = array();

						$contentSearch = new FM_ContentSearch();
						$contentSearch->initialize($field['value'])
									  ->select('*')
									  ->fullInfo(TRUE)
									  ;
						$tmpContentList = $contentSearch->getContentList();

						if (count($tmpContentList) > 0) {

							foreach ($tmpContentList as $tmpContent) {

								$contentListOptions[$tmpContent['id']] = $tmpContent['title'];

							}

						}

					}

				}

				if ($field['name'] === 'content_list_categories' && $contentTypeName === 'pages' && in_array($field['type'], array('multiselect', 'multicheckbox'))) {
					$field['options'] = (array) $field['value'];
					$field['options'] = array_combine($field['options'], $field['options']);
				}


				if ($field['name'] == 'content_list' && $contentTypeName == 'pages' && ($field['type'] == 'select' || $field['type'] == 'multiselect')) {

					// Check if the operation value exist
					if (isset($contentListOptions) && count($contentListOptions) > 0) {

						$field['options'] = $contentListOptions;

					}

				}

				$fields[$index] = $field;

			}

		}

		if ($this->ci->input->post('save') || $this->ci->input->post('save_list')) {

			$redirectURL .= ($this->ci->input->post('save') ? 'edit/' : 'index/' . $contentType['content_type']);

			$tmpFolder = $this->getTmpFolder();

			// Generic saving of the values
			foreach ($this->ci->input->post() as $name => $value) {

				$oldValues[$name] = $value;

			}

			if (count($fields) > 0) {

				foreach ($fields as $index => $field) {

					if ($this->ci->input->post($field['name'])) {

						$field['value'] = $this->ci->input->post($field['name']);

					}

					$fields[$index] = $field;
				}

			}

			// Basic content checking
			if ($this->ci->input->post('title') == '') {

				$errors['title'] = $this->ci->lang->line('incorrect_content_title');

			}

			if ($this->ci->input->post('relative_path') == '') {

				$errors['relative_path'] = sprintf($this->ci->lang->line('incorrect_field'), 'relative path');

			} else {
				$_POST['relative_path'] = $relativePath = url_title(convert_accented_characters($this->ci->input->post('relative_path')), '-', true);
			}

			// Check for page absolute_path uniqueness
			if ($contentTypeName === 'pages' && !isset($errors['relative_path']) && isset($flatPagesTitles)) {
				$absolutePath = "{$relativePath}/";

				$parentPageId = $this->ci->input->post('parent_page_id');
				if ($parentPageId) {
					$absolutePath = "{$flatPagesTree[$parentPageId]['absolute_path']}{$absolutePath}";
				}

				$contentSearch = new FM_ContentSearch();
				$contentSearch->initialize($contentType['id'])
					->select('id')
					->addWhereCondition('absolute_path', '=', $absolutePath)
					->addWhereCondition('lang', '=', $this->ci->input->post('lang'))
					;

				$content = current($contentSearch->getContentList());
				if ($content && (!$idContent || $content['id'] !== $idContent)) {
					$errors['relative_path'] = $this->ci->lang->line('non_unique_absolute_path');
				}

			} else {
				$absolutePath = url_title(convert_accented_characters($oldValues['title']));
			}



			// ================ 2 - CHECKING THE ERRORS ========================

			if (count($fields) > 0) {

				foreach ($fields as $index => $field) {

					if (!$this->isFilesystemField($field)) {

						if ($field['mandatory'] == TRUE && $this->ci->input->post($field['name']) == '') {

							$field['showError'] = TRUE;
							$field['error']     = sprintf($this->ci->lang->line('incorrect_field'), $field['label']);

						}

						if ($field['type'] == 'date' || $field['type'] == 'datetime') {

							$value = $this->ci->input->post($field['name']);

							if ($value) {

								$field['value'] = $value;

								if ($field['type'] == 'datetime') {

									list($date, $time) = explode(' ', $value);

								} else {

									$date = $value;

								}

								list($day, $month, $year) = explode('/', $date);
								if(!checkdate($month, $day, $year)) {

									$field['showError'] = TRUE;
									$field['error']     = sprintf($this->ci->lang->line('incorrect_date_field'), $field['label']);

								}

							}

						}

					} else {

						$hidden = $this->ci->input->post($field['name']);

						if ($field['mandatory'] == TRUE && $_FILES[$field['name']]['error'] != 0 && !$hidden) {

							$field['showError'] = TRUE;
							$field['error']     = sprintf($this->ci->lang->line('incorrect_field'), $field['label']);

						}

						if ($_FILES[$field['name']]['error'] == 0) {

							$temp = $this->saveTemporaryAttachments($field);

							if (isset($temp[$field['name']]['error'])) {

								$field['showError'] = TRUE;
								$field['error']     = $temp[$field['name']]['error'];

							} else {

								$field['value'] = $temp[$field['name']]['file_name'];
								$uploadData = array_merge($uploadData, $temp);

							}

						}

					}

					$fields[$index] = $field;

				}
			}

			$values = array();

			$checkErrors = FALSE;

			// The title and permalink are correct
			// (??)
			if (count($errors) == 0) {

				if (count($fields) > 0) {

					foreach ($fields as $field) {

						if (isset($field['error']) && $field['error'] != '') {

							$checkErrors = TRUE;

						}

					}

				}

			}



			// ================ 3 - SAVE THE CONTENT VALUES ========================

			if (count($errors) == 0 && !$checkErrors) {

				$contentData = array(
					'absolute_path' => $absolutePath
				) + array_intersect_key($this->ci->input->post(), array_flip(array(
					'title',
					'relative_path',
					'lang'
				)));

				// Save the basic content
				if (!$idContent) {

					$contentData['id_content_type'] = $contentType['id'];
					$contentData['id_user'] = $this->ci->fm_users_management->getLoggedUserId();

					$idContent = $this->insertContent($contentData);

				} else {

					$contentData['id'] = $idContent;

					$this->updateContent($contentData);

					if ($contentTypeName === 'pages' && isset($contentData['absolute_path']) && $contentData['absolute_path']) {
						$this->ci->content->updateSonsPath($contentData['absolute_path'], $idContent);
					}

				}

				$storeFolder = $this->_uploadPath . '/' . $idContent . '/';

				// I check if the directory of the content exists or not
				if (file_exists($storeFolder) == FALSE) {

					mkdir($storeFolder, 0777);

				}

				// Create the thumb folder if image
				if (file_exists($storeFolder . 'thumbs/') == FALSE) {

					mkdir($storeFolder . 'thumbs/', 0777);

				}

				$redirectURL .= ($this->ci->input->post('save') ? $idContent : '');

				// Save the content values
				if (count($fields) > 0) {

					// Move everything from the tmp folder to the content folder
					$this->moveTemporaryAttachments($idContent);

					foreach ($fields as $field) {

						// ================ 4 - PROCESS THE IMAGES ===============
						if ($field['type'] == 'image_upload' || $field['type'] == 'gallery') {

							$hidden = $this->ci->input->post($field['name']);

							if (isset($uploadData[$field['name']])) {

								$processResult = $this->processImage($idContent, $field, $uploadData);

								if (isset($processResult['error'])) {

									log_message($processResult['error']);

								}

							}

						}

						// Insert the value
						if (!isset($contentValues[$field['id']])) {

							if (!$this->isFilesystemField($field)) {

								$value = $this->ci->input->post($field['name']);

								if ($field['type'] == 'xhtml_textarea' && $tmpFolder) {

									$tmpFolder = $this->ci->config->item('txt_upl_img_path', 'factotum') . '/' . $tmpFolder;
									$value = str_replace($tmpFolder, $this->_imagesURL . '/' . $idContent, $value);

								}

								if ($field['type'] == 'multiselect' || $field['type'] == 'multicheckbox' || $field['type'] == 'multiple_linked_content') {

									$value = $value ? FM_Utility::convertMultiOptionsArrayToText($value) : '';

								}

								$this->insertContentValue($idContent, $field['id'], $value);

							} else {

								// Saving the Image/File Uploaded
								if (($field['type'] == 'image_upload' || $field['type'] == 'file_upload') && isset($uploadData[$field['name']]['file_name'])) {

									if (isset($uploadData[$field['name']]['file_name'])) {

										$value = $uploadData[$field['name']]['file_name'];

									} else {

										// Retrieve the hidden value
										$value = $this->ci->input->post($field['name']);

									}

									$this->insertContentValue($idContent, $field['id'], $value);

								}

								// Saving the gallery
								if ($field['type'] == 'gallery' && isset($uploadData[$field['name']])) {

									$idContentAttachment = $this->insertContentAttachment($idContent, $field['id'], $uploadData[$field['name']]['file_name'], $uploadData[$field['name']]['file_name']);
									$this->insertContentValue($idContent, $field['id'], $idContentAttachment);

								}

							}

						} else {

							// Update the existing rows
							$cValue = $contentValues[$field['id']];

							// Normal field type (text/textarea)
							if (!$this->isFilesystemField($field)) {

								$value = $this->ci->input->post($field['name']);

								if ($field['type'] == 'xhtml_textarea' && $tmpFolder) {

									$tmpFolder = $this->ci->config->item('txt_upl_img_path', 'factotum') . '/' . $tmpFolder;
									$value = str_replace($tmpFolder, $this->_imagesURL . '/' . $idContent, $value);

								}

								if ($field['type'] == 'multiselect' || $field['type'] == 'multicheckbox' || $field['type'] == 'multiple_linked_content') {

									$value = FM_Utility::convertMultiOptionsArrayToText($value);

								}

								$this->updateContentValue($cValue['id'], $value);

							} else {

								// Saving the Image/File Uploaded
								if (($field['type'] == 'image_upload' || $field['type'] == 'file_upload') && isset($uploadData[$field['name']]['file_name'])) {

									$value = $uploadData[$field['name']]['file_name'];

									$this->updateContentValue($cValue['id'], $value);

								}

								if ($field['type'] == 'gallery' && isset($uploadData[$field['name']])) {

									$idContentAttachment = $this->insertContentAttachment($idContent, $field['id'], $uploadData[$field['name']]['file_name'], $uploadData[$field['name']]['file_name']);

									$this->insertContentValue($idContent, $field['id'], $idContentAttachment);

								}

							}

						}

					}

					// ================ 5 - EMPTY THE TMP FOLDER ===============
					$this->deleteTmpFolder();
				}

				// 6 - SAVE THE CONTENT CATEGORIES
				$tmp = array();
				if (count($contentCategories) > 0) {
					foreach ($contentCategories as $contentCategory) {
						$tmp[] = $contentCategory['id_category'];
					}
				}
				$contentCategories = $tmp;

				$postedCategories = $this->ci->input->post('categories');
				if ($postedCategories && count($postedCategories) > 0) {

					if ($postedCategories) {

						$tmp = array();
						foreach ($postedCategories as $idContentCategory) {
							$tmp[] = $idContentCategory;
						}
						$postedCategories = $tmp;

					}

					foreach ($categories as $cat) {

						if (!in_array($cat['id'], $postedCategories)) {

							$this->deleteContentCategoryByIdContentAndIdCategory($idContent, $cat['id']);

						} else if (!in_array($cat['id'], $contentCategories)) {

							$this->insertContentCategory($idContent, $cat['id']);

						}

					}

				} else {

					foreach ($contentCategories as $idCategory) {

						$this->deleteContentCategoryByIdContentAndIdCategory($idContent, $idCategory);

					}

				}


				// ================ 7 - REDIRECT TO THE RESULT ===============
				$result['redirectURL'] = $redirectURL;
				$result['data_saved']  = TRUE;

			} else {

				$result['redirectURL'] = '';

			}

		}

		$result['fields']    = $fields;
		$result['errors']    = $errors;
		$result['oldValues'] = $oldValues;

		return $result;
	}


	public function processImage($idContent, $field, $uploadData)
	{
		$result = array();
		$storeFolder = $this->_uploadPath . '/' . $idContent . '/';

		// I check if the directory of the content exists or not
		if (file_exists($storeFolder) == FALSE) {

			$result[$field['name']]['error'] = 'Error during creation of the store folder, check save attachments.';
			return $result;

		}

		// Defining the config for the image manipulation
		$config = array();
		$config['image_library']  = 'gd2';
		$srcImage                 = $storeFolder . $uploadData[$field['name']]['file_name'];
		$config['source_image']   = $srcImage;
		$config['new_image']      = $storeFolder . 'thumbs/' . $uploadData[$field['name']]['file_name'];
		$config['create_thumb']   = FALSE;

		list($imgWidth, $imgHeight, $imgType, $attr) = getimagesize($config['source_image']);
		$imgRatio = $imgWidth / $imgHeight;

		switch ($imgType) {

			case 1:
				$imgType = 'gif';
			break;

			case 2:
				$imgType = 'jpeg';
			break;

			case 3:
				$imgType = 'png';
			break;

		}

		// Get the sizes and the ratios
		list($maxWidth, $maxHeight) = explode('x', $field['max_image_size']);
		$config['max_width']  = $maxWidth;
		$config['max_height'] = $maxHeight;

		list($thumbWidth, $thumbHeight) = explode('x', $field['thumb_size']);
		$config['width']  = $thumbWidth;
		$config['height'] = $thumbHeight;
		$thumbRatio = $thumbWidth / $thumbHeight;

		if ($field['image_operation'] == 'resize_crop') {

			if ($imgRatio > $thumbRatio) {

				$config['master_dim'] = 'height';

			} else if ($imgRatio < $thumbRatio) {

				$config['master_dim'] = 'width';

			} else {

				$config['master_dim'] = 'auto';

			}

		}

		if ($field['image_operation'] == 'resize' || $field['image_operation'] == 'resize_crop') {

			$config['maintain_ratio'] = TRUE;

		} else {

			$config['maintain_ratio'] = FALSE;

		}

		$this->ci->load->library('image_lib');

		if ($field['image_operation'] == 'resize' || $field['image_operation'] == 'resize_crop') {

			$this->ci->image_lib->initialize($config);

			if (!$this->ci->image_lib->resize()) {

				$result[$field['name']]['error'] = $this->ci->image_lib->display_errors();

			}

		}

		// Reset some variables and clear
		if ($field['image_operation'] == 'resize_crop') {

			$this->ci->image_lib->clear();
			$config['master_dim'] = 'auto';
			$config['maintain_ratio'] = FALSE;
			$config['source_image']   = $config['new_image'];

			// Recalculate the correct axis for the crop
			list($imgWidth, $imgHeight) = getimagesize($config['new_image']);

		}

		if ($field['image_operation'] == 'crop' || $field['image_operation'] == 'resize_crop') {

			$config['x_axis'] = round(($imgWidth - $thumbWidth) / 2);
			$config['y_axis'] = round(($imgHeight - $thumbHeight) / 2);

			$this->ci->image_lib->initialize($config);

			if (!$this->ci->image_lib->crop()) {

				$result[$field['name']]['error'] = $this->ci->image_lib->display_errors();

			}

		}

		$this->ci->image_lib->clear();

		// For the Black and White I'll use directly the php GD (image filter)
		if ($field['image_bw']) {

			$imgFunc = "imagecreatefrom" . $imgType;
			$createFunc = "image" . $imgType;

			$img = $imgFunc($srcImage);
			imagefilter($img, IMG_FILTER_GRAYSCALE);
			$createFunc($img, $srcImage, $this->ci->config->item('image_quality', 'factotum'));

			$img = $imgFunc($config['new_image']);
			imagefilter($img, IMG_FILTER_GRAYSCALE);
			$createFunc($img, $config['new_image'], $this->ci->config->item('image_quality', 'factotum'));

		}

		return $result;
	}

	/**
	 * If the tmp folder variable doesn't exist, attempt to create the variable and the folder
	 * then store everything in the session
	 */
	public function getTmpFolder()
	{
		$tmpFolder = uniqid();

		if ($this->ci->session->userdata('tmp_folder')) {

			$tmpFolder = $this->ci->session->userdata('tmp_folder');

		} else {

			$sessionData = $this->ci->session->all_userdata();
			$sessionData['tmp_folder'] = $tmpFolder;
			$this->ci->session->set_userdata($sessionData);

			if (!is_dir($this->_tmpUploadPath)) {
				mkdir($this->_tmpUploadPath);
			}
			mkdir($this->_tmpUploadPath . '/' . $tmpFolder);

		}

		return $tmpFolder;
	}


	public function saveTemporaryAttachments($field)
	{
		// Move temporary file saved
		$tmpFolder = $this->getTmpFolder();

		if ($tmpFolder) {

			$hidden = $this->ci->input->post($field['name']);

			$config['upload_path'] = $this->_tmpUploadPath . '/' . $tmpFolder;

			if (file_exists($config['upload_path']) == FALSE) {

				mkdir($config['upload_path'], 0777);

			}

			$config['allowed_types']    = ($field['allowed_types'] != '*' ? implode('|', explode(',', $field['allowed_types'])) : '*');
			$config['max_size']         = $field['max_file_size'];

			// Basic image requirements
			if ($field['type'] == 'image_upload' || $field['type'] == 'gallery') {

				$config['quality']          = $this->ci->config->item('image_quality', 'factotum');

				list($maxWidth, $maxHeight) = explode('x', $field['max_image_size']);
				$config['max_width']        = $maxWidth;
				$config['max_height']       = $maxHeight;

			}


			$this->ci->load->library('upload');
			$this->ci->upload->initialize($config);

			if (!$this->ci->upload->do_upload($field['name'])) {

				$result[$field['name']]['error'] = $this->ci->upload->display_errors();

				return $result;

			} else {

				$uploadData = array($field['name'] => $this->ci->upload->data());

				return $uploadData;

			}

		}

	}

	/*
	 * Copy all the files from the temporary folder to the content folder
	 * and remove the tmp folder
	 */
	public function moveTemporaryAttachments($idContent)
	{
		$storeFolder = $this->_uploadPath . '/' . $idContent . '/';
		$tmpFolder   = $this->_tmpUploadPath . '/' . $this->getTmpFolder();

		if ($tmpFolder) {

			directory_copy($tmpFolder, $storeFolder);
			$this->deleteTmpFolder();

		}
	}

	/**
	 * Delete all the files from the temporary folder
	 * Delete the folder
	 * Remove the variable from the session
	 */
	public function deleteTmpFolder()
	{
		$tmpFolder = $this->_tmpUploadPath . '/' . $this->getTmpFolder();

		delete_files($tmpFolder, TRUE);
		rmdir($tmpFolder);
		$this->ci->session->set_userdata('tmp_folder', NULL);
	}

	public function getUploadPath()
	{
		return $this->_uploadPath;
	}

	public function getTmpUploadPath()
	{
		return $this->_tmpUploadPath;
	}

	public function getImagesURL()
	{
		return $this->_imagesURL;
	}


	/**
	 * From {@link http://stackoverflow.com/a/22020668}
	 * _menuBranch is createBranch
	 * getMenu includes createTree code
	 */
	private function _menuBranch(&$parents, $children)
	{
		$tree = array();
		foreach ($children as $child) {
			if (isset($parents[$child['id']])) {
				$child['children'] = $this->_menuBranch($parents, $parents[$child['id']]);
			}

			$tree[$child['id']] = $child;
		}

		return $tree;
	}

	public function getMenu($isAuthenticated, $currentPageId = null)
	{
		$contentSearch = new FM_ContentSearch();
		$contentSearch->initialize('pages')
			->onlyLiveContent(!$isAuthenticated)
			->fullInfo(true)
			->withAllFieldsAndValues(true)
			->addWhereCondition('main_menu', '=', 1)
			->order('order_no');

		$pages = $contentSearch->getContentList();

		$pageParents = array();
		if ($currentPageId && isset($pages[$currentPageId])) {
		    $pageId = $currentPageId;
		    while ($pageId && isset($pages[$pageId]) && isset($pages[$pageId]['parent_page_id']) && $pages[$pageId]['parent_page_id'] && isset($pages[$pages[$pageId]['parent_page_id']])) {
		        $pageParents[] = $pageId = $pages[$pageId]['parent_page_id'];
		    }
		}

		$parents = array();
		foreach ($pages as $page) {
			$parentId = intval(isset($page['parent_page_id']) ? $page['parent_page_id'] : 0);
			if (!isset($parents[$parentId])) {
				$parents[$parentId] = array();
			}

			if ($page['id'] === $currentPageId) {
			    $page['current'] = true;
			    $page['active'] = true;
			}

			if (in_array($page['id'], $pageParents)) {
			    $page['active'] = true;
			}

			$parents[$parentId][] = $page;
		}

		return $this->_menuBranch($parents, $parents[0]);
	}


	public function getFlatPagesTree(array $tree, $level = 0)
	{
		$flattened = array();
		foreach ($tree as $id => $page) {
			$flattened[$id] = $page + array('level' => $level);
			if (isset($page['children']) && is_array($page['children'])) {
				$flattened += $this->getFlatPagesTree($page['children'], $level + 1);
			}
		}

		return $flattened;
	}


}

/* End of file FM_Cms.php */
/* Location: ./application/libraries/FM_Cms.php */
