<?php
/**
 * Streammanager database API
 *
 * @author Me
 * @version $Id$
 * @copyright Me, 15 May, 2011
 * @package streammanager
 **/

class Database 
{
	/**
	 * The mysqli object
	 *
	 * @var object
	 **/
	var $mysqli;

	/**
	 * This is where we store result from queries
	 *
	 * @var object
	 **/
	var $result;

	/**
	 * The constructor: connect to mysql a return an instance
	 *
	 * @return object::Database
	 * @author Me
	 **/
	public function __construct()
	{
		$this->mysqli = new mysqli("localhost", "root", "", "streammanager");
		if (mysqli_connect_errno()) {
			//printf("Connect failed: %s\n", mysqli_connect_error());
			exit();
		}
	}

	/**
	 * Make a query to MySQL
	 *
	 * @return void
	 * @author Me
	 **/
	private function query($query)
	{
		try
		{
			$this->result = $this->mysqli->query($query);
		} 
		catch(mysqli_mysql_exception $exception)
		{
			// MySQL Exception handler
			// TODO	
		}
	}

	/**
	 * disconnect from database
	 *
	 * @return void
	 * @author Me
	 **/
	public function close()
	{
		$this->mysqli->close();
	}

	/**
	 * Get info about currently selected output
	 *
	 * @return void
	 * @author Me
	 **/
	public function getCurrentOutput()
	{
		$ret = NULL;
		$query = "SELECT * FROM outputs WHERE selected=1";

		$this->query($query);
		if($this->result->num_rows == 1)
		{
			$ret = $this->result->fetch_assoc();
		}
		
		return $ret;
	}

	/**
	 * Get all outputs
	 *
	 * @return void
	 * @author Me
	 **/
	public function getOutputs()
	{
		$ret = NULL;
		$query = "SELECT * FROM outputs";

		$this->query($query);
		if($this->result->num_rows > 0)
		{
			$ret = array();
			while($entry = $this->result->fetch_assoc())
			{
				array_push($ret, $entry);
			}
		}
		
		return $ret;
	}

	/**
	 * Check if the stream with name $name and url $url already exists in database
	 *
	 * @return array with results or NULL
	 * @author Me
	 **/
	public function streamExists($name, $url)
	{
		$ret = NULL;
		if($name == NULL && $url != NULL)
			$query = "SELECT * FROM streams WHERE url='". $url ."'";
		else if($name != NULL && $url == NULL)
			$query = "SELECT * FROM streams WHERE name='". $name ."'";
		else if($name != NULL && $url != NULL)
			$query = "SELECT * FROM streams WHERE name='". $name ."'OR url='". $url ."'";

		$this->query($query);
		if($this->result->num_rows > 0)
		{
			$ret = array();
			while($entry = $this->result->fetch_assoc())
			{
				array_push($ret, $entry);
			}
		}
		
		return $ret;
	}

	/**
	 * Insert a stream into table
	 *
	 * @return id of inserted row or NULL
	 * @author Me
	 **/
	public function addStream($name, $url)
	{
		$ret = NULL;

		$statement = 
			$this->mysqli->prepare("INSERT INTO streams (name, url, created) VALUES (?, ?, now())");
		$statement->bind_param("ss", $name, $url);
		$statement->execute();

		$ret = $this->mysqli->insert_id;
		return $ret;
	}

	/**
	 * Get stream with id $id
	 *
	 * @return array with stream info or NULL
	 * @author Me
	 **/
	public function getStream($id)
	{
		$ret = NULL;

		$query = "SELECT * FROM streams WHERE id=$id";
		$this->query($query);

		if($this->result->num_rows == 1)
		{
			$ret = $this->result->fetch_assoc();
		}
		
		return $ret;
	}

	/**
	 * Return all streams
	 *
	 * @return array with results or NULL
	 * @author Me
	 **/
	public function getStreams()
	{
		$ret = NULL;
		$query = "SELECT * FROM streams ORDER BY name";

		$this->query($query);
		if($this->result->num_rows > 0)
		{
			$ret = array();
			while($entry = $this->result->fetch_assoc())
			{
				array_push($ret, $entry);
			}
		}
		
		return $ret;
	}

	/**
	 * Remove stream with ID
	 *
	 * @return boolean
	 * @author Me
	 **/
	public function removeStream($id)
	{
		$ret = false;
		if(!is_numeric($id))
			return $ret;

		$query = "DELETE FROM streams WHERE id=". $id;
		$this->query($query);

		if($this->result->affected_rows == 1)
		{
			$ret = true;
		}
		
		return $ret;
	}

	/**
	 * Set output to output $id
	 *
	 * @return void
	 * @author Me
	 **/
	public function setOutput($id)
	{
		$ret = NULL;
		if(!is_numeric($id))
			return $ret;

		$query = "UPDATE outputs SET selected=0";
		$this->query($query);

		$query = "UPDATE outputs SET selected=1 WHERE id=$id";
		$this->query($query);

		if($this->mysqli->affected_rows == 1)
		{
			$ret = 1;
		}

		return $ret;
	}

}
