<?php defined('DOCROOT') or die('No direct access allowed.');
/**
 * Database helps connecting to a database and run queries securely.
 *
 * @package   PHPMVC
 * @category  Core
 * @author    T. Zengerink
 */
class Database {

	/**
	 * @var  Database  instance
	 */
	protected static $_instance;

	/**
	 * Returns a database instance if no instance is set an
	 * instance will be created.
	 *
	 * @return  Database  instance
	 */
	public static function instance()
	{
		// Set instance
		if ( ! Database::$_instance)
		{
			Database::$_instance = new Database;
		}

		// Return the instance
		return Database::$_instance;
	}

	/**
	 * @var  config
	 */
	protected $_config;

	/**
	 * @var  connection
	 */
	protected $_connection;

	/**
	 * @var  database
	 */
	protected $_database;

	/**
	 * The instance method should be used to get the database
	 * instance.
	 *
	 * @return  Database
	 * @uses    Config
	 */
	public function __construct()
	{
		$this->_connection = mysql_connect(Config::load('database')->get('hostname'), Config::load('database')->get('username'), Config::load('database')->get('password'), TRUE);
		$this->_database   = mysql_select_db(Config::load('database')->get('database'));
	}

	/**
	 * Disconnect from the database when object is distroyed.
	 */
	public function __destruct()
	{
		$this->disconnect();
	}

	/**
	 * Close the database connection.
	 *
	 * @return  boolean  succesfully disconnected
	 */
	public function disconnect()
	{
		// Try to disconnect and unset the instance
		if (mysql_close($this->_connection))
		{
			$this->_connection = NULL;
			$this->_database   = NULL;
			self::$_instance   = FALSE;
			return TRUE;
		}

		return FALSE;
	}

	/**
	 * Run a query on the database.
	 *
	 * @param   string  query
	 * @param   array   parameters
	 * @return  mixed   result or boolean whether successful
	 */
	public function query($str, array $params = NULL)
	{
		// Loop parameters
		foreach ($params as $key => $value)
		{
			$str = str_replace($key, mysql_real_escape_string($value), $str);
		}

		// Loop results and create an array
		$query  = mysql_query($str);

		// Return query is a boolean
		if (is_bool($query))
		{
			return $query;
		}

		// Create results array
		$result = array();
		while ($arr = mysql_fetch_assoc($query))
		{
			$result[] = $arr;
		}

		// Clear and return the result
		mysql_free_result($query);
		return $result;
	}

	/**
	 * Select all records in a given table.
	 *
	 * @param  string   table name
	 * @param  integer  limit
	 * @param  integer  offset
	 */
	public function select($table_name, $limit = NULL, $offset = NULL)
	{
		return $this->select_where($table_name, array(), $limit, $offset);
	}

	/**
	 * Select all record where given keys have given values.
	 *
	 * @param  string   table_name
	 * @param  array    where (key/value pairs)
	 * @param  integer  limit
	 * @param  integer  offset
	 */
	public function select_where($table_name, array $where = array(), $limit = NULL, $offset = NULL)
	{
		$query  = 'SELECT * FROM `:table_name`';
		$values = array(
			':table_name' => $table_name,
			':limit'      => $limit,
			':offset'     => $offset,
		);

		// Loop where array
		$count = 0;
		foreach ($where as $key => $value)
		{
			$query .= ($count ? ' AND' : ' WHERE').' `'.mysql_real_escape_string($key).'` = \':'.$key.'\'';	
			$values[':'.$key] = $value;
			$count++;
		}

		// Set limit
		if ($limit != NULL)
		{
			$query .= ' LIMIT :limit';
		}

		// Set offset
		if ($limit != NULL AND $offset != NULL)
		{
			$query .= ', :offset';
		}

		// Return the results
		return $this->query($query, $values);
	}

	/**
	 * Insert a record where given keys are to contain given values.
	 *
	 * @param   string   table name
	 * @param   array    data (key/value pairs)
	 * @return  boolean  inserted successful   
	 */
	public function insert($table_name, array $data = NULL)
	{
		// Set query and values
		$query  = 'INSERT INTO `:table_name`';
		$keys   = '';
		$values = '';
		$array  = array(':table_name' => $table_name);

		// Loop data
		$count = 0;
		foreach ($data as $key => $value)
		{
			$keys   .= ($count ? ', ' : '').'`'.$key.'`';
			$values .= ($count ? ', ' : '').'\''.$value.'\'';
			$array[':'.$key] = $value;
			$count++;
		}
		$query .= ' ('.$keys.') VALUES ('.$values.');';

		// Run the query and return if successful
		$result = $this->query($query, $array);
		return ($result !== FALSE);
	}

	/**
	 * Update a record where given keys match given values.
	 *
	 * @param   string   table name
	 * @param   array    data (key/value pairs)
	 * @param   array    where (key/value pairs)
	 * @return  boolean  updated successful
	 */
	public function update($table_name, array $data = NULL, array $where = NULL)
	{
		// Check if all necessary data is given
		if ( ! $where)
		{
			throw new Exception ('Not providing any \'where\' data will cause all records to be changed.');
		}

		// Set query and values array
		$query  = 'UPDATE `:table_name` SET';
		$values = array(':table_name' => $table_name);

		// Loop data array
		$count = 0;
		foreach ($data as $key => $value)
		{
			$query .= ($count ? ',' : '').' `'.$key.'` = \':'.$key.'\'';
			$values[':'.$key] = $value; 
			$count++;
		}

		// Loop where array
		$count = 0;
		foreach ($where as $key => $value)
		{
			$query .= ($count ? ' AND' : ' WHERE').' `'.mysql_real_escape_string($key).'` = \':'.$key.'\'';	
			$values[':'.$key] = $value;
			$count++;
		}

		// Run the query and return TRUE
		$result = $this->query($query, $values);
		return ($result !== FALSE);
	}
	
} // End Database
