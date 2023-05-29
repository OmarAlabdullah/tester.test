<?php

class dbi
{
	function dbi()
	{
		$this->handle = false;
		$this->name = false;
		$this->user = false;
		$this->password = false;
		$this->parent_tables = array();
	}
	
	function connect()
	{
		//if($this->handle)
		//	if($this->handle->ping())
		//		return true;
		
		if($this->user && $this->password && $this->name)
			$this->handle = mysqli_connect('localhost', $this->user, $this->password, $this->name);
        
		if($this->handle !== false)
			return true;
		
		$this->handle = false;
		return false;
	}
	
	function select($params)
	{
		$this->parent_tables = array();
		
		if(is_string($params))
			return $this->_select($params);
  	
  	if(is_array($params))
  	{
			foreach($params as $table => $args)
			{
				return $this->_select($table, $args);
			}
  	}
  	
  	return false;
	}
	function _select($table = null, $args = null)
	{
		$select_string = '*';
		$where_string = '1=1';
		$order_string = false;
		
		if(is_array($args))
		{
			foreach($args as $arg => $arg_args)
			{
				switch($arg)
				{
					case 'conditions':
						$where_string = '';
						foreach($arg_args as $colum => $value)
						{
							if(is_int($colum))
								$where_string .= ' AND ' . $value;
							else
							{
								$where_string .= ' AND ';
								
								$where_parts = explode(' ', $colum);
								
								$w = 0;
								foreach($where_parts as $where_part)
								{
									if($w == 0)
										$where_string .= '`' . str_replace('`', '', $where_part) . '`';
									else
										$where_string .= ' ' . $where_part;
									$w++;
								}
								
								if(count($where_parts) == 1)
									$where_string .= '=';
								$where_string .= ' \'' . $value . '\'';
							}
						}
						$where_string = substr($where_string, 5);
					break;
					case 'select':
						switch($arg_args)
						{
							case 'first':
								$select_type = 'first';
							break;
						}
					break;
					case 'order':
						$order_by_parts = explode(' ', $arg_args);
						$order_string = ' ORDER BY ';
						$o = 0;
						foreach($order_by_parts as $order_by_part)
						{
							if($o == 0)
								$order_string .= '' . $order_by_part . '';
							else
								$order_string .= ' ' . $order_by_part;
							$o++;
						}
					break;
					case 'parent':
						foreach($arg_args as $parent_table)
						{
							$this->parent_tables[] = $parent_table;
						}
					break;
				}
			}
		}
		
		
		$query = "SELECT " . $select_string . " FROM `" . $table . "`";
		if($where_string)
			$query .= " WHERE " . $where_string;
		if($order_string)
			$query .= $order_string;
		
		if($this->connect())
		{
			//pr($query);
			$result = mysqli_query($this->handle, $query);
			
			if($result)
			{
				$alias = $this->_table_name_to_alias($table);
				
				$r = 0;
				while($row = $result->fetch_assoc())
				{
					$return[$r][$alias] = $row;
					
					
					foreach($this->parent_tables as $parent_table)
					{
						$parent_colum_name = $this->_table_name_to_single($parent_table) . '_id';
						$parent_id = $return[$r][$alias][$parent_colum_name];
						$parent_result = $this->first($parent_table, $parent_id);
						$parent_alias = $this->_table_name_to_alias($parent_table);
						if($parent_result)
							$return[$r][$parent_alias] = $parent_result[$parent_alias];
					}
					
					$r++;
				}
				
				if($select_type == 'first')
				{
					$return = $return[0];
				}
				
				return $return;
			}
			mysqli_close($this->handle);
		}
		
		return false;
	}
	
	function first($table_name = false, $id = 0)
	{
		if($table_name && $id > 0)
		{
			$params = array(
				$table_name => array(
					'conditions' => array(
						'id' => $id
					),
					'select' => 'first'
				)
			);
			return $this->select($params);
		}
		return false;
	}
	
	function insert($db_format = false)
	{
		if($db_format)
		{
			$alias = key($db_format);
			$table_name = $this->_alias_to_table_name($alias);
			
			if($table_name)
			{
				$query = "INSERT INTO `" . $table_name . "`";
				$colums = '(';
				$values = '(';
				foreach($db_format[$alias] as $colum => $value)
				{
					$colums .= '`' . $colum . '`,';
					$values .= '\'' . mysqli_real_escape_string($this->handle, $value) . '\',';
				}
				$colums = substr($colums, 0, -1) . ')';
				$values = substr($values, 0, -1) . ')';
				
				$query .= $colums . " VALUES " . $values;
				
				if($this->connect())
				{
					//pr($query);
					mysqli_query($this->handle, $query);
					//pr(mysqli_error($this->handle));
					
					$insert_id = $this->handle->insert_id;
					if($insert_id > 0)
						return $insert_id;
					
					return false;
				}
			}
		}
		return false;
	}
	
	function update($db_format = false)
	{
		if($db_format)
		{
			$alias = key($db_format);
			$table_name = $this->_alias_to_table_name($alias);
			
			if($table_name)
			{
				$where_string = " WHERE 1=2 ";
				
				$query = "UPDATE `" . $table_name . "` SET ";
				
				foreach($db_format[$alias] as $colum => $value)
				{
					if($colum == 'id')
						$where_string = " WHERE `id`=" . $value . " ";
					else
						$query .= "`" . $colum . "`='" . $value . "',";
				}
				$query = substr($query, 0, -1);
				$query .= $where_string;
				$query .= " LIMIT 1";
				
				if($this->connect())
				{
					mysqli_query($this->handle, $query);
					//pr(mysqli_error($this->handle));
					return ($this->handle->affected_rows >= 0);
				}
			}
		}
		return false;
	}
	
	function query($query)
	{
		if($this->connect())
		{
			mysqli_query($this->handle, $query);
			mysqli_close($this->handle);
		}
	}
	
	
	function _table_name_to_alias($table_name)
	{
		return ucfirst($this->_table_name_to_single($table_name));
	}
	function _table_name_to_single($table_name)
	{
		return substr($table_name, 0, -1);
	}
	function _alias_to_table_name($alias)
	{
		return strtolower($this->_table_name_to_multiple($alias));
	}
	function _table_name_to_multiple($table_name)
	{
		return $table_name . 's';
	}
}
?>