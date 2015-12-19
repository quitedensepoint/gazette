<?php

/**
 * A Database helper class to make things a little simpler
 *
 * @author Jason Rout
 */
class dbhelper {
	
	
	private $dbconn;
	
	public function __construct($dbconn)
	{
		$this->dbconn = $dbconn;
	}
	
	/**
	 * Prepare a SQL statement
	 * 
	 * @param string $sql
	 * @param array $params Parameters for the prepared statement
	 * @return mysqli_stmt
	 */
	public function prepare($sql, array $params = [])
	{
		/* @var $query mysqli_stmt */		
		$query = $this->dbconn->stmt_init();

		$query->prepare($sql);

		if($query->param_count > 0)
		{
			$args[] = $this->getParameterTypes($params);
			foreach($params as $key => $value)
			{
				$args[] = &$params[$key];
			}

			call_user_func_array([$query, "bind_param"], $args);
		}
		return $query;
	}
	
	/**
	 * Returns the results of the statement as an associative array
	 * 
	 * @param mysqli_stmt $query
	 * @return array
	 */
	public function getAsArray(mysqli_stmt $query)
	{
		if($query->execute())
		{
			$meta = $query->result_metadata();
			while ($field = $meta->fetch_field()) {
				$params[] = &$row[$field->name];
			}
			
			call_user_func_array(array($query, 'bind_result'), $params);
			$result = [];
			while ($query->fetch()) {

					$temp = array();
					foreach($row as $key => $val) {
						$temp[$key] = $val;
					} 
					$result[] = $temp;
			}

			$meta->free();
			$query->close(); 
			
			return $result;
		}
	}
	
	/**
	 * Turns the parameters into a statement compatible string
	 * 
	 * @param array $params
	 * @return string
	 */
	public function getParameterTypes(array $params = array())
	{
		
		/**
		 * Loop through each parameter and decide what type it is
		 */
		$types = "";
		
		foreach($params as $param)
		{			
			if(is_numeric($param))
			{
				$types .= "i"; // integer
			}
			else if(is_string($param))
			{
				$types .= "s"; // string
			}
			else if(is_double($param)) {
				$types .= "d"; // double
			}
			else
			{
				$types .="b"; // blob
			}			
		}
		return $types;
	}
	
}
