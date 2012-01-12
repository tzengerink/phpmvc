<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Model returns an instance of the requested model name.
 *
 * Example usage:
 * <code>
 * Model::factory('model_name');
 * </code>
 *
 * @package   PHPMVC
 * @category  Model
 * @author    T. Zengerink
 */
class Model {

	/**
	 * Method to create a new Model.
	 *
	 * @param   string   name
	 * @param   integer  id
	 * @return  Model
	 */
	public static function factory($name, $id = NULL)
	{
		// Set class name
		$model = 'Model_'.$name;

		// Return new instance of model
		return new $model($name);
	}

	/**
	 * @var  string  table name
	 */
	protected $_table_name;

	/**
	 * Construct a new model instance.
	 *
	 * @return  Model  instance
	 */
	public function __construct()
	{
		// Set table name if it is not set
		if ( ! $this->_table_name)
		{
			$this->_table_name = strtolower(str_replace('Model_', '', get_class($this))).'s';
		}
	}

	/**
	 * Array containing regular expressions for validation.
	 * 
	 * @return  array  validation regexes
	 */
	public function validation()
	{
		return array();
	}

	/**
	 * Get all records.
	 *
	 * @param   integer  limit
	 * @param   integer  offset
	 * @return  array    all records
	 * @uses    Database
	 */
	public function all($limit = NULL, $offset = NULL)
	{
		return Database::instance()
			->select($this->_table_name, $limit, $offset);
	}

	/**
	 * Get all records where given keys have given values.
	 *
	 * @param  array    key-value-pairs
	 * @param  integer  limit
	 * @param  integer  offset
	 * @retun  array    records
	 * @uses   Database
	 */
	public function where(array $where = NULL, $limit = NULL, $offset = NULL)
	{
		return Database::instance()
			->select_where($this->_table_name, $where, $limit, $offset);
	}

	/**
	 * Get a record by providing an id.
	 *
	 * @param   integer  id
	 * @return  array    associative array of record
	 */
	public function id($id)
	{
		$model = $this->where(array('id' => $id), 1, 0);
		return array_pop($model);
	}

	/**
	 * Insert a model.
	 *
	 * @param   array  data
	 * @return  boolean  successfully inserted
	 * @throws  Exception
	 * @uses    Database
	 */
	public function insert(array $data = NULL)
	{
		// Check if data meets the validation rules
		if ($this->_validate($data))

		// Insert the data
		return Database::instance()
			->insert($this->_table_name, $data);
	}

	/**
	 * Update a model.
	 *
	 * @param  array  data (key/value pairs)
	 * @param  array  where (key/value pairs)
	 * @uses   Database
	 */
	public function update(array $data = NULL, array $where = NULL)
	{
		// Check if the data meets the validation rules
		$this->_validate($data);

		// Update the record
		return Database::instance()
			->update($this->_table_name, $data, $where);
	}

	/**
	 * Validate an array of data against the validation rules.
	 *
	 * @param   array  data
	 * @return  boolean  data is valid
	 * @throw   Exception
	 */
	protected function _validate($data)
	{
		$rules = $this->validation();
		foreach ($data as $key => $value)
		{
			if (array_key_exists($key, $rules) AND ! preg_match($rules[$key], $value))
			{
				throw new Exception('Given \''.$key.'\' is not valid');
			}
		}

		return TRUE;
	}

} // End Model
