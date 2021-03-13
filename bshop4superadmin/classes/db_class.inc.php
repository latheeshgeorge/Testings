<?php
class db_mysql
{
	var $database;
	var $hostname;
	var $username;
	var $password;
	var $link;
	
	function db_mysql($hostname, $username, $password, $database)
	{
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
	}

	function connect()
	{	
		if(!$this->link = @mysql_connect($this->hostname,$this->username,$this->password) )
		{
			echo "Cannot Connect to host: ".$this->hostname;
			exit;
			return false;
		}
		else
		{
			return true;
		}
	}

	function select_db()
	{
		if(!@mysql_select_db($this->database, $this->link) )
		{
			echo "Cannot select database: ".$this->database;
			exit;
			return false;
		}
		else
		{
			return true;
		}
	}
	
	
	function query($query)
	{	
		$start_parse_time = microtime();
		
		$result = @mysql_query($query, $this->link) or die($query.'--<font color=red>'.mysql_error().'</font>');
		
		return $result;
		
	}
	
	function fetch_one_row($query)
	{
		$result = @mysql_query($query, $this->link) or die($query.'--<font color=red>'.mysql_error().'</font>');
		return @mysql_fetch_array($result);
	}
	function fetch_array($result)
	{	
		return @mysql_fetch_array($result);
		
	}
	function fetch_assoc($result)
	{	
		return @mysql_fetch_assoc($result);
		
	}
	function delete_id($id, $field, $table)
	{
		if( is_array($id) )
		{
			foreach( $id AS $i )
			{
				$this->query("DELETE FROM $table WHERE $field = '$i'");
			}
		}
		else
		{
			$this->query("DELETE FROM $table WHERE $field = '$id'");
		}
	}
	
	function num_rows($result)
	{
		return @mysql_num_rows($result);
	}
	
	function data_seek($result,$row_no)
	{
		return @mysql_data_seek($result,$row_no);
	}
	
	function affected_rows()
	{
		return @mysql_affected_rows();
	}
	
	function insert_id()
	{
		return @mysql_insert_id();
	}
	
	function value_exists($array, $table)
	{
		/*array contains values of the field name and field value*/
		foreach($array as $key => $value)
		{
			$condition .= $key." = '".$value."' and ";
		}
		$condition = substr($condition,0,-5);
		$result = $this->query("SELECT COUNT(*) as cnt FROM $table WHERE $condition");
		$count = $this->fetch_array($result);
		return $count[cnt];
	}
	
	function update_from_array($array, $table, $field, $id=0)
	{
		$query = "UPDATE $table SET ";
		while(@list($key,$value) = @each($array))
		{
			if(($value == 'now()') or ($value == 'date()'))
			{
				$fields[] = "$key=$value";
			}
			else
			{
				$fields[] = "$key='$value'";
			}
		}

		$query .= implode(', ', $fields);
		if(is_array($field)) {
			$query .= " WHERE";
			foreach($field as $k => $v) {
				$query .= " ".$k." = '$v' AND ";
			}
			$query = substr($query,0,-5);
		} else {
			$query .= " WHERE ".$field." = '$id'";
		}
		$this->query($query);
		return true;
	
	}
	
	function insert_from_array($array, $table)
	{
		while( @list($key,$value) = @each($array) )
		{
			$field_names[] = "$key";
			if(($value == 'now()') or ($value == 'date()'))
			$field_values[] = "$value";
			else
			$field_values[] = "'$value'";
		}
		$query = "INSERT INTO $table (";
		$query .= implode(', ', $field_names);

		$query .= ') VALUES (' . implode(',', $field_values) . ')';
		$this->query($query);
 
		return true;
	
	}
	function db_close()
	{
	
		mysql_close($this->link);
	}
}
?>
