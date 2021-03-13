<?php
class db_mysql
{
	var $database;
	var $hostname;
	var $username;
	var $password;
	var $link;
	
	function __construct($hostname, $username, $password, $database)
	{
		$this->hostname = $hostname;
		$this->username = $username;
		$this->password = $password;
		$this->database = $database;
	}

	function connect()
	{	
		if(!$this->link = @mysqli_connect($this->hostname,$this->username,$this->password,$this->database) )
		{
			//echo "Cannot Connect to host: ".$this->hostname;
			echo "We will be back shortly, We are undergoing maintenance";
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
		if(!@mysqli_select_db($this->link,$this->database) )
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
	/*	echo '<br><br>';
		echo $query;
		echo '<br><br>';*/
		$result = @mysqli_query($this->link,$query) or die($query.'--<font color=red>'.mysqli_error($this->link).'</font>');
		return $result;
		
	}
	
	function fetch_one_row($query)
	{
		$result = @mysqli_query($query, $this->link) or die($query.'--<font color=red>'.mysqli_error($this->link).'</font>');
		return @mysqli_fetch_array($result);
	}
	function fetch_array($result)
	{	
		return @mysqli_fetch_array($result);
		
	}
	function fetch_assoc($result)
	{	
		return @mysqli_fetch_assoc($result);
		
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
	
	function delete_from_array($array, $table)
	{
		foreach($array as $key => $value)
		{
			$condition .= $key." = '".$value."' and ";
		}
		
		if($condition) {
			$condition = substr($condition,0,-5);
			$this->query("DELETE FROM $table WHERE $condition");
			
		}
	}
	
	function num_rows($result)
	{
		return @mysqli_num_rows($result);
	}
	
	function data_seek($result,$row_no)
	{
		return @mysqli_data_seek($result,$row_no);
	}
	
	function affected_rows()
	{
		return @mysqli_affected_rows();
	}
	
	function insert_id()
	{
		return @mysqli_insert_id($this->link);
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
			if(($value == 'now()') or ($value == 'date()')or ($value == 'curdate()'))
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
			if(($value == 'now()') or ($value == 'date()') or ($value == 'curdate()'))
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
	
		mysqli_close($this->link);
	}
}
?>
