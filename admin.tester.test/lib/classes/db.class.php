<?php
class db
{
	function db()
	{
		$this->name = '';
		$this->user = '';
		$this->password = '';
		$this->host = 'localhost';
		$this->prefix = '';
		
		$this->handle = false;
		$this->mysql_error = false;
		$this->tableAlias_columName_seperator = '|';
		$this->colums = array();
		$this->select = 'all';
		
		$this->convertions = array(
			'day' => 'days',
			'sheep' => 'sheep'
		);
	}
	
	function connect()
	{
		if(!$this->handle)
		{
			$this->handle = @mysql_pconnect($this->host, $this->user, $this->password);
			if(!$this->handle || !@mysql_select_db($this->name))
				return false;
		}
		return true;
	}
	function disconnect()
	{
		@mysql_close($this->handle);
	}
	function query($query)
	{
		if(!$this->connect())
			$this->trowError('query: Failed to connect to DB');
		$result = @mysql_query($query);
		//pr($query);
		if(!$result)
		{
			$this->mysql_error = mysql_error();
			$this->trowError('query: Failed to run query "' . $query . '"', $this->mysql_error);
		}
		return $result;
	}
	
	function select($params)
	{
		$actions = array();
		$conditions = array();
		
		if(is_string($params))
			$this->table = (string) $params;
		else
		{
			$first_key = reset(array_keys($params));
			$this->table = (string) $first_key;
			if(!is_string($first_key))
				$this->table = (string) $params[$first_key];
			if(is_string($params))
				$this->table = (string) $params;
		}
		
		$this->table_alias = $this->tableToAlias($this->table);
		$this->colums = $this->describe($this->table);
		
		//start select colums
		$select_colums = array();
		foreach($this->colums as $colum => $colum_data)
			$select_colums[] = $colum;
		//end select colums
		
		//pr($select_colums);
		
		//start get actions
		if(is_array($params))
		{
			$actions = reset(array_values($params));
			if(!is_array($actions))
				$actions = array();
		}
		//end get actions
		
		if(!empty($actions['fields']))
		{
			$select_colums = $this->getFields($select_colums, $actions['fields']);
		}
		
		$order_by = null;
		if(!empty($actions['order']))
		{
			$order_by = $actions['order'];
		}
		
		$limit = null;
		if(!empty($actions['limit']))
		{
			$limit = $actions['limit'];
		}
		
		$this->select = 'all';
		
		if(!empty($actions['select']))
			$this->select = $actions['select'];
		
		if($this->select == 'first')
			$limit = 1;
		
		$conditions = $this->getConditions($actions['conditions']);
		$clean_results = $this->selectFromData($this->table, $select_colums, $conditions, $order_by, $limit);
		
		//pr($clean_results);
		
		foreach($clean_results as $key => $clean_result)
		{
			$actionsResult = $this->parseActions($clean_result[$this->table_alias]['id'], $actions);
			if(is_array($actionsResult))
			{
				foreach($actionsResult as $k => $v)
				{
					$table_alias_for_sub_result = reset(array_keys($actionsResult[$k]));
					$actionsResult[$k] = $actionsResult[$k][$table_alias_for_sub_result];
				}
			}
			
			if(!is_null($table_alias_for_sub_result))
				$clean_results[$key][$table_alias_for_sub_result] = $actionsResult;
		}
		
		if($this->select == 'first')
			return $clean_results[0];
		return $clean_results;
	}
	
	function save($data)
	{
		$this->table_alias = reset(array_keys($data));
		$this->table = $this->aliasToTable($this->table_alias);
		
		$colum_data = $data[$this->table_alias];
		
		$this->colums = $this->describe($this->table);
		
		//start select colums
		$primary_keys = array();
		$select_colums = array();
		foreach($this->colums as $colum => $colum_info)
		{
			$select_colums[] = $colum;
			if($colum_info['primary'])
				$primary_keys[] = $colum;
		}
		
		$insert = false;
		foreach($primary_keys as $primary_key)
		{
			if(empty($colum_data[$primary_key]))
				$insert = true;
		}
		
		if($insert)
			return $this->insert($data);
		else
			return $this->update($data);
	}
	
	function insert($data)
	{
		$this->table_alias = reset(array_keys($data));
		$this->table = $this->aliasToTable($this->table_alias);
		
		$colum_data = $data[$this->table_alias];
		
		$colums = array();
		$values = array();
		foreach($colum_data as $colum => $value)
		{
			$colums[] = $colum;
			$values[] = $value;
		}
		
		$colum_string = '(';
		foreach($colums as $colum)
		{
			$colum_string .= '`' . $this->table . '`.`' . $colum . '`,';
		}
		$colum_string = substr($colum_string, 0, -1);
		$colum_string .= ')';
		
		$value_string = '(';
		foreach($values as $value)
		{
			$value_string .= '\'' . $value . '\',';
		}
		$value_string = substr($value_string, 0, -1);
		$value_string .= ')';
		
		$query = "
			INSERT INTO `" . $this->table . "`
			" . $colum_string . "
			VALUES
			" . $value_string . "
		";
		
		return $this->query($query);
	}
	
	function update($data)
	{
		$this->table_alias = reset(array_keys($data));
		$this->table = $this->aliasToTable($this->table_alias);
		
		$colum_data = $data[$this->table_alias];
		
		$set = '';
		foreach($colum_data as $colum => $value)
		{
			$set .= '`' . $this->table . '`.' . $colum . '=\'' . $value . '\',';
		}
		$set = substr($set, 0, -1);
		
		$where = '';
		
		$this->colums = $this->describe($this->table);
		
		foreach($this->colums as $colum => $colum_info)
		{
			if($colum_info['primary'])
			{
				$where .= '`' . $this->table . '`.' . $colum . '=\'' . $colum_data[$colum_info['colum']] . '\' AND ';
			}
		}
		$where = substr($where, 0, -5);
		
		if(strlen($where) > 0)
		{
			$query = "
				UPDATE `" . $this->table . "` 
				SET " . $set . "
				WHERE " . $where . "
			";
			
			return $this->query($query);
		}
		return false;
	}
	
	private function parseActions($parent_id, $actions, $from_table = null)
	{
		if(is_null($from_table))
			$from_table = $this->table;
		
		//start actions
		$return = array();
		
		foreach($actions as $action_name => $action_value)
		{
			switch($action_name)
			{
				case 'child':
					$child_tables = $action_value;
					if(!is_array($child_tables))
						$child_tables = array((string) $child_tables);
					foreach($child_tables as $child_table => $data)
					{
						if($child_table === 0)
							$child_table = $data;
						
						$this->colums = $this->describe($child_table);
						$select_colums = array();
						foreach($this->colums as $colum => $colum_data)
							$select_colums[] = $colum;
						
						if(is_array($data['conditions']))
							$conditions = $this->getConditions($data['conditions']);
						else
							$conditions = array();
						$conditions[$this->tableToForeignKey($from_table)] = array(
							'colum' => $this->tableToForeignKey($from_table),
							'search_char' => '=',
							'value' => $parent_id
						);
						$clean_results = $this->selectFromData($child_table, $select_colums, $conditions);
						
						foreach($clean_results as $key => $clean_result)
						{
							if(is_array($data))
							{
								
								//pr($clean_result[$this->tableToAlias($child_table)]['project_id']);
								
								$parent = false;
								foreach($data as $next_action_name => $next_action_value)
								{
									if($next_action_name == 'parent')
									{
										$parent = true;
										
										if(is_array($next_action_value))
											$parent_table = $this->tableToForeignKey(reset(array_keys($next_action_value)));
										else
											$parent_table = $this->tableToForeignKey($next_action_value);
										$parent_table_id = $clean_result[$this->tableToAlias($child_table)][$parent_table];
									}
								}
								$actionsResult = $this->parseActions(($parent ? (int) $parent_table_id : $clean_result[$this->tableToAlias($child_table)]['id']), $data, $child_table);
								
								foreach($actionsResult as $k => $v)
								{
									$table_alias_for_sub_result = reset(array_keys($actionsResult[$k]));
									$actionsResult[$k] = $actionsResult[$k][$table_alias_for_sub_result];
								}
								
								$head_table_alias_for_sub_result = reset(array_keys($clean_results[$key]));
								
								$addative = ($parent ? $actionsResult[0] : $actionsResult);
								if(!empty($addative))
									$clean_results[$key][$head_table_alias_for_sub_result][$table_alias_for_sub_result] = $addative;
							}
						}
						
					}
				break;
				case 'parent':
					$parent_tables = $action_value;
					if(!is_array($parent_tables))
						$parent_tables = array((string) $parent_tables);
					
					foreach($parent_tables as $parent_table => $data)
					{
						if($parent_table === 0)
							$parent_table = $data;
						
						$this->colums = $this->describe($parent_table);
						$select_colums = array();
						foreach($this->colums as $colum => $colum_data)
							$select_colums[] = $colum;
						
						if(is_array($data['conditions']))
							$conditions = $this->getConditions($data['conditions']);
						else
							$conditions = array();
						$conditions['id'] = array(
							'colum' => 'id',
							'search_char' => '=',
							'value' => $parent_id
						);
						
						$clean_results = $this->selectFromData($parent_table, $select_colums, $conditions, null, 1);
					}
					
				break;
				case 'children':
					
				break;
			}
		}
		//end actions
		return $clean_results;
	}
	
	private function selectFromData($table, $fields = array(), $conditions = array(), $order_by, $limit)
	{
		$table_alias = $this->tableToAlias($table);
		
		//start select
		$select = 'SELECT ';
		foreach($fields as $field)
		{
			if($colums[$field]['encryption'] == 'AES')
				$select .= 'AES_DECRYPT(' . $this->columToQueryColum($field) . ', \'kaas\')';
			else
				$select .= $this->columToQueryColum($field);
			
			$select .= ' AS `' . $table_alias . $this->tableAlias_columName_seperator . $field . '`,';
		}
		$select = substr($select, 0, -1);
		//end select
		
		//start from
		$from = 'FROM ' . $this->tableToQueryTable($table) . ' AS ' . '`' . $table_alias . '`';
		//end from
		
		//start where
		$where = '';
		if(count($conditions) > 0)
		{
			$where = 'WHERE ';
			foreach($conditions as $condition)
			{
				if($condition['type'] == 'string')
				{
					$where .= $condition['condition'];
				}else
				{
					$where .= $this->columToQueryColum($condition['colum']) . ' ' . $condition['search_char'];
					
					$needs_quotes = !($colums[$condition['colum']]['type'] == 'int');
					if($needs_quotes)
						$where .= ' \'' . $condition['value'] . '\'';
					else
						$where .= $condition['value'];
				}
					$where .= ' AND ';
			}
			$where = substr($where, 0, -5);
		}
		//end where
		
		//start limit
		if(!is_null($limit))
			$limit = 'LIMIT ' . $limit;
		
		$order = "";
		if(!is_null($order_by))
			$order = 'ORDER BY ' . $order_by;
		
		$query = $select . '
		' . $from . '
		' . $where . '
		' . $order . '
		' . $limit . '
		';
		
		$query_array = $this->toArray($this->query($query));
		return $this->parseRawQueryArray($query_array);
	}
	
	private function getConditions($conditions)
	{
		if(is_array($conditions))
		{
			foreach($conditions as $condition_colum => $condition_value)
			{
				
				if(is_int($condition_colum))
				{
					$return[$condition_colum] = array(
						'type' => 'string',
						'condition' => $condition_value
					);
				}else
				{
					$search_char = '=';
					if(strstr($condition_colum, ' '))
					{
						$parts = explode(' ', $condition_colum);
						$condition_colum = $parts[0];
						$search_char = $parts[1];
					}
					if(!isset($this->colums[$condition_colum]))
						$this->trowError('conditions: the colum `' . $condition_colum . '` doesn\'t exsists in table `' . $from_table . '`');
					else
					{
						$return[$condition_colum] = array(
							'colum' => $condition_colum,
							'search_char' => $search_char,
							'value' => $condition_value
						);
					}
				}
			}
			return $return;
		}else
		{
			//$this->trowError('conditions: must be an array', $conditions);
			return array();
		}
	}
	
	private function getFields($fields, $field_conditions)
	{
		if(empty($field_conditions))
			return $fields;
		
		$return_fields = $fields;
		
		if(!is_array($field_conditions))
			$field_conditions = array($field_conditions);
		
		foreach($field_conditions as $key => $field_condition)
		{
			//if(!is_array($field_condition))
			//	$field_condition = array($field_condition);
			
			if(is_int($key))
			{
				if($field_condition == '!')
				{
					$return_fields = array();
				}else
				{
					$return_fields[] = $field_condition;
				}
			}else
			{
				foreach($field_condition as $cond)
				{
					if($key == '!')
					{
						unset($return_fields[array_search($cond, $return_fields)]);
					}
				}
			}
		}
		
		return $return_fields;
	}
	
	private function parseRawQueryArray($query_array)
	{
		$i = 0;
		$return = array();
		foreach($query_array as $query_row)
		{
			foreach($query_row as $key => $value)
			{
				$parts = explode($this->tableAlias_columName_seperator, $key);
				$return[$i][$parts[0]][$parts[1]] = $value;
			}
			$i++;
		}
		return $return;
	}
	
	private function aliasToTable($table_alias)
	{
		$table_alias = strtolower($table_alias);
		
		$parts = explode('_', $table_alias);
		$last_word = end($parts);
		
		if(!empty($this->convertions[$last_word]))
		{
			$table = '';
			unset($parts[count($parts) - 1]);
			foreach($parts as $part)
				$table .= $part . '_';
			$table .= $this->convertions[$last_word];
			return $table;
		}
		
		if(substr($table_alias, -1, 1) == 'y')
			$table_alias = substr($table_alias, 0, -1) . 'ie';
		return $table_alias . 's';
	}
	private function tableToAlias($table)
	{
		$table = strtolower($table);
		$table = substr($table, 0, -1);
		if(substr($table, -2, 2) == 'ie')
			$table = substr($table, 0, -2) . 'y';
		return ucfirst($table);
	}
	private function tableToQueryTable($table)
	{
		return '`' . $this->prefix . $table . '`';
	}
	private function columToQueryColum($colum)
	{
		return '`' . $colum . '`';
	}
	private function tableToForeignKey($table)
	{
		return strtolower($this->tableToAlias($table)) . '_id';
	}
	private function trowError($text, $params = '')
	{
		
		if(!empty($params))
			pr($params);
		pr($text);
		
	}
	private function describe($table)
	{
		$return = array();
		$query_table = $this->tableToQueryTable($table);
		$query = 'DESCRIBE ' . $query_table;
		$query_result = $this->query($query);
		$result_array = $this->toArray($query_result);
		
		foreach($result_array as $describe_row)
		{
			if(strtolower(substr($describe_row['Type'], 0, 3)) == 'int')
				$type = 'int';
			if(strtolower(substr($describe_row['Type'], 0, 7)) == 'varchar')
				$type = 'text';
			if(strtolower(substr($describe_row['Type'], 0, 7)) == 'text')
				$type = 'text';
			if(strtolower(substr($describe_row['Type'], 0, 4)) == 'date')
				$type = 'date';
			if(strtolower(substr($describe_row['Type'], 0, 8)) == 'datetime')
				$type = 'datetime';
			if(strtolower(substr($describe_row['Type'], 0, 8)) == 'varbinary')
				$type = 'varbinary';
			
			$encryption = 'none';
			if($type == 'varbinary' && Config::read('Db.encryption') == 'AES')
				$encryption = 'AES';
			
			$return[$describe_row['Field']] = array(
				'colum' => $describe_row['Field'],
				'type' => $type,
				'encryption' => $encryption,
				'primary' => ($describe_row['Key'] == 'PRI')
			);
		}
		
		return $return;
	}
	private function toArray($mysql_resource)
	{
		$return = array();
		while($row = @mysql_fetch_array($mysql_resource, MYSQL_ASSOC))
			$return[] = $row;
		return $return;
	}
}
