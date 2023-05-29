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
			$this->session_nr = 0;
			$this->tableAlias_columName_seperator = '|';
			$this->debug = array();
			$this->errors = array();
			$this->qurytimes = array();
			$this->cache = 1;
			$this->cached_querys = array();
			$this->convertions = array(
				'category' => 'categories',
				'deliver_status' => 'deliver_status',
				'payment_status' => 'payment_status'
			);
		}

		function test()
		{
			var_dump('test');
		}

		function connect()
		{
			$this->session_nr++;
			$connected = true;
			if(!$this->handle)
			{
				$this->_notify('Start connection');
		        $this->handle = @mysql_pconnect($this->host, $this->user, $this->password);
		        if(!$this->handle || !@mysql_select_db($this->name))
		        {
		        	$connected = false;
		        }
				if($connected)
					$this->_notify('connected to DB');
				else
					$this->_error('failed to connect to ' . $this->name . ' on ' . $this->host);
			}
			return $connected;
		}

		function query($query = null, $convert = false)
		{
			$start_time = microtime(true);

			if($convert && $this->cache >= 1 && isset($this->cached_querys[$query]))
			{
				$this->qurytimes[] = array(
					'query' => $query,
					'time' => (microtime(true) - $start_time),
					'cached' => true
				);
				return $this->cached_querys[$query];
			}

			$this->session_nr++;
			$this->connect();
			if(is_null($query))
				$this->_error('Missing argument, query(String $query)');
			$result = @mysql_query($query);
			if($result)
				$return = $result;
			else
				$this->_error($query . "<br/>" . mysql_error());
			$this->qurytimes[] = array(
				'query' => $query,
				'time' => (microtime(true) - $start_time),
				'cached' => false
			);

			if($convert)
			{
				if($this->cache >= 2)
				{

					$result = $this->_to_array($return);

					//cache($query, $result, 'querys');


				}elseif($this->cache >= 1 && !isset($this->cached_querys[$query]))
				{
					$result = $this->_to_array($return);
					$this->cached_querys[$query] = $result;
					return $result;
				}
				return $this->_to_array($return);
			}

			return $return;
		}

		function select($data)
		{
			$this->session_nr++;
			$tables = array();
			$tables_alias = array();
			$return = array();
			if(!is_array($data))
			{
				//$tables[] = $data;
				//$tables_alias[] = $this->_to_alias($table);
				$data = array($data);
			}
			//else
				foreach($data as $table => $table_data)
				{
					if($table === 0)
					{
						$table = $table_data;
						$table_data = array();
					}
					$table_alias = $this->_to_alias($table);
					$tables[] = $table;
					$tables_alias[] = $table_alias;
					$this->_notify('Start to create query for `' . $table_alias . '`');
					$select = '';
					$grab_fields = array();
					$result = $this->query('DESCRIBE `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $table . '`');
					$discribe = $this->_to_array($result);
					$this->_notify($discribe);
					$start_colums = array();
					foreach($discribe as $colum)
					{
						if(!(substr($select, 0, 6) == 'SELECT'))
							$select = 'SELECT ';
						else
							$select .= ', ';
						$select .= '`' . $table_alias . '`.`' . $colum['Field'] . '` AS `' . $this->_to_alias($table) . $this->tableAlias_columName_seperator . $colum['Field'] . '`';
						$start_colums[] = $colum['Field'];
						$grab_fields['`' . $table_alias . '`.`' . $colum['Field'] . '`'] = $colum['Field'];
					}
					$from = 'FROM `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $table . '` AS `' . $table_alias . '`';
					$where = '';
					$order = '';
					$group = '';
					$limit = '';
					$joins = array();
					$return_type = 'all';

					foreach($table_data as $data_type => $data_value)
						switch($data_type)
						{
							case 'conditions' :
								$this->_notify('Start creating conditions');
								foreach($data_value as $colum => $value)
								{
									if(is_int($colum))
									{
										if(!(substr($where, 0, 5) == 'WHERE'))
											$where = 'WHERE ';
										else
											$where .= ' AND ';
										$where .= $value;
									}else
									{
										$search_char = '=';
										$parts = explode(' ', $colum);
										if(count($parts) > 1)
											$search_char = trim(empty($parts[1]) ? $search_char : $parts[1]);
										$colum = $parts[0];
										if(!(substr($where, 0, 5) == 'WHERE'))
											$where = 'WHERE ';
										else
											$where .= ' AND ';
										if(strstr($colum, '(') === false)
											$where .= '`' . $table_alias . '`.`' . $colum . '` ' . $search_char . ' ' . (is_string($value) ? '\'' . $value . '\'' : ($search_char == 'IN' ? '(' . implode(',', $value) . ')' : $value));
										else
											$where .= substr($colum, 0, strpos($colum, '(')) . '(`' . $table_alias . '`.`' . substr($colum, strpos($colum, '(') + 1, strlen($colum) - strpos($colum, '(') - 2) . '`) ' . $search_char . ' ' . (is_string($value) ? '\'' . $value . '\'' : $value);
									}
								}
							break;

							case 'fields' :
								if(count($data_value) > 0)
								{
									$select = '';
								}
								if(is_string($data_value))
									$data_value = array($data_value);
								foreach($data_value as $extra_select => $extra_select_alias)
									if(is_int($extra_select) && $extra_select_alias == '!')
										$grab_fields = array();
									else
									{
										if($extra_select == '!')
										{
											if(is_string($extra_select_alias))
												$extra_select_alias = array($extra_select_alias);
											foreach($extra_select_alias as $exclude_field)
												unset($grab_fields[array_search($exclude_field, $grab_fields)]);
										}else
										{
											if(is_int($extra_select))
											{
												$select .= ', `' . $table_alias . '`.`' . $extra_select_alias . '` AS `' . $table_alias . $this->tableAlias_columName_seperator . $extra_select_alias . '`';
												$grab_fields[$extra_select_alias] = $extra_select_alias;
											}else
											{
												$select .= ', `' . $table_alias . '`.' . $extra_select . ' AS `' . $extra_select_alias . '`';
												$grab_fields[$extra_select] = $extra_select_alias;
											}
										}
									}
								if(count($data_value) > 0)
									$select = 'SELECT ' . substr($select, 2);
							break;

							case 'group' :
								$group = 'GROUP BY `' . $table_alias . '`.`' . $data_value . '`';
							break;

							case 'limit' :
								$limit = 'LIMIT ' . $data_value;
							break;

							case 'order' :
								$order_rows = array();
								if(!is_array($data_value))
									$order_rows[] = $data_value;
								else
									$order_rows = $data_value;
								foreach($order_rows as $order_row)
								{
									if(!(substr($order, 0, 5) == 'ORDER'))
										$order = 'ORDER BY ';
									else
										$order .= ', ';
									/*
									$parts = explode(' ', $order_row);
									$order .= '`' . $table_alias . '`.`' . $parts[0] . '`';
									for($i=1; $i<count($parts); $i++)
										$order .= ' ' . $parts[$i];
									*/
									$order .= $order_row;
								}
							break;

							case 'find' :
							case 'return' :
							case 'select' :
								$return_type = $data_value;
							break;

							case 'where' :
								if(!(substr($where, 0, 5) == 'WHERE'))
									$where = 'WHERE ';
								else
									$where .= ' AND ';
								$where .= $data_value;
							break;

							case 'children' :
								$join_tables = $data_value;
								if(is_string($join_tables))
									$join_tables = array($tables);
							break;

							case 'child' :
								$child_tables = $data_value;
								if(is_string($child_tables))
									$child_tables = array($tables);
							break;

							case 'parent' :
								$parent_tables = $data_value;
								if(is_string($parent_tables))
									$parent_tables = array($tables);
							break;

						}
					/*
					if($return_type == 'count')
					{
						$num_rows = $this->query("SELECT COUNT(*) AS num_rows FROM " . $table, true);
						return $num_rows[0]['num_rows'];
					}
					*/
					if(!isset($child_tables))
						$child_tables = array();
					if(!isset($join_tables))
						$join_tables = array();
					if(!isset($parent_tables))
						$parent_tables = array();

					if(count($child_tables) > 0 || count($join_tables) > 0)
						$grab_fields['`' . $table_alias . '`.`id`'] = 'id';

					foreach($parent_tables as $parent_table => $parse_data)
					{
						if(is_int($parent_table))
							$parent_table = $parse_data;
						$grab_fields['`' . $table_alias . '`.`' . $this->_to_single($parent_table) . '_id`'] = $this->_to_single($parent_table) . '_id';
					}

					$select = 'SELECT ';
					foreach($grab_fields as $colum => $alias)
						$select .= $colum . ' AS `' . $table_alias . $this->tableAlias_columName_seperator . $alias . '`, ';

					$select = substr($select, 0, -2);

					if($return_type == 'count')
						$select = 'SELECT COUNT(*) AS num_rows';

					$query = '
					' . $select . '
					' . $from . '
					' . $where . '
					' . $group . '
					' . $order . '
					' . $limit . '
					';
					$this->_notify($query);

					if($return_type == 'count')
					{
						$num_rows = $this->query($query, true);
						return $num_rows[0]['num_rows'];
					}

					$result = $this->query($query);
					$return = $this->_to_array_alias($result);
				}
			$this->_notify($return);

			foreach($return as $key => $ret)
			{
				foreach($join_tables as $join_table => $parse_data)
				{
					if(is_int($join_table))
					{
						$join_table = $parse_data;
						$parse_data = array();
					}

					$table_merge = array();
					$table_merge[] = $table;
					$table_merge[] = $join_table;
					sort($table_merge);
					$table_between = $table_merge[0] . '_' . $table_merge[1];

					$between_select = 'SELECT `' . $this->_to_single($join_table) . '_id`';
					$between_from = 'FROM `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $table_between . '`';
					$between_where = 'WHERE `' . $this->_to_single($table) . '_id` = ' . $ret[$this->_to_alias($table)]['id'];

					$between_query = '
					' . $between_select . '
					' . $between_from . '
					' . $between_where . '
					';

					$result = $this->query($between_query);
					$result = $this->_to_array_alias($result);
					//$result = $this->query($between_query, true);

					if((count($result) >= 5) || (count($result) >= 2 && isset($parse_data['order'])))
					{
						$discribe = $this->query('DESCRIBE `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $join_table . '`', true);

						$Select = '';
						$select_fields = array();
						foreach($discribe as $colum)
						{
							$Select .= ', `' . $this->_to_alias($join_table) . '`.`' . $colum['Field'] . '` AS `' . $this->_to_alias($join_table) . $this->tableAlias_columName_seperator . $colum['Field'] . '`';
							$select_fields['`' . $this->_to_alias($join_table) . '`.`' . $colum['Field'] . '`'] = $colum['Field'];
						}
						$Select = substr($Select, 2);

						$IN = '';
						foreach($result as $row)
							$IN .= $row[$this->_to_single($join_table) . '_id'][0] . ',';
						$IN = '(' . substr($IN, 0, -1) . ')';

						$order_string = '';

						foreach($parse_data as $parsed_data => $parsed_value)
						{
							switch($parsed_data)
							{
								case 'fields' :

									if($parsed_value == '!')
										$select_fields = array();

									if(is_array($parsed_value))
									{

										foreach($parsed_value as $een => $twee)
										{
											if(is_int($een) && is_string($twee))
											{
												if($twee == '!')
													$select_fields = array();
												else
													$select_fields[$twee] = $twee;
											}else
											{
												if($een == '!')
												{
													if(!is_array($twee))
														$twee = array($twee);
													foreach($twee as $exclude)
														unset($select_fields[array_search($exclude, $select_fields)]);
												}else
													$select_fields[$een] = $twee;
											}
										}
									}
								break;

								case 'order' :
									if(!is_array($parsed_value))
										$parsed_value = array($parsed_value);
									foreach($parsed_value as $order)
										$order_string .= $order . ', ';
									if(count($parsed_value) > 0)
										$order_string = substr($order_string, 0, -2);
								break;
							}
						}

						if(strlen($order_string) > 0)
							$order_string = 'ORDER BY ' . $order_string;

						$Select = '';
						foreach($select_fields as $select_colum => $select_alias)
							$Select .= ', ' . $select_colum . ' AS `' . $this->_to_alias($join_table) . $this->tableAlias_columName_seperator . $select_alias . '`';
						$Select = substr($Select, 2);

						if(empty($Select))
							$Select = '`' . $this->_to_alias($join_table) . '`.`id` AS `' . $this->_to_alias($join_table) . $this->tableAlias_columName_seperator . 'id`';

						$result = $this->query("SELECT " . $Select . " FROM `" . (!empty($this->prefix) ? $this->prefix . '_' : '') . $join_table . "` AS `" . $this->_to_alias($join_table) . "` WHERE `id` IN " . $IN . " " . $order_string . " ");
						$result = $this->_to_array_alias($result);

						foreach($result as $return_row)
							if(!empty($return_row[$this->_to_alias($join_table)]))
								$return[$key][$this->_to_alias($join_table)][] = $return_row[$this->_to_alias($join_table)];

					}else
					{

						foreach($result as $row)
						{
							//$result = $this->query('DESCRIBE `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $join_table . '`');
							//$discribe = $this->_to_array($result);
							$discribe = $this->query('DESCRIBE `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $join_table . '`', true);

							$Select = '';
							$select_fields = array();
							foreach($discribe as $colum)
							{
								$Select .= ', `' . $this->_to_alias($join_table) . '`.`' . $colum['Field'] . '` AS `' . $this->_to_alias($join_table) . $this->tableAlias_columName_seperator . $colum['Field'] . '`';
								$select_fields['`' . $this->_to_alias($join_table) . '`.`' . $colum['Field'] . '`'] = $colum['Field'];
							}
							$Select = substr($Select, 2);


							foreach($parse_data as $parsed_data => $parsed_value)
							{
								switch($parsed_data)
								{
									case 'fields' :

										if($parsed_value == '!')
											$select_fields = array();

										if(is_array($parsed_value))
										{

											foreach($parsed_value as $een => $twee)
											{
												if(is_int($een) && is_string($twee))
												{
													if($twee == '!')
														$select_fields = array();
													else
														$select_fields[$twee] = $twee;
												}else
												{
													if($een == '!')
													{
														if(!is_array($twee))
															$twee = array($twee);
														foreach($twee as $exclude)
															unset($select_fields[array_search($exclude, $select_fields)]);
													}else
														$select_fields[$een] = $twee;
												}
											}
										}
									break;
								}
							}

							$Select = '';
							foreach($select_fields as $select_colum => $select_alias)
								$Select .= ', ' . $select_colum . ' AS `' . $this->_to_alias($join_table) . $this->tableAlias_columName_seperator . $select_alias . '`';
							$Select = substr($Select, 2);

							$result = $this->query("SELECT " . $Select . " FROM `" . (!empty($this->prefix) ? $this->prefix . '_' : '') . $join_table . "` AS `" . $this->_to_alias($join_table) . "` WHERE `id` = " . $row[$this->_to_single($join_table) . '_id'][0] . " ");
							$result = $this->_to_array_alias($result);
							$return[$key][$this->_to_alias($join_table)][] = $result[0][$this->_to_alias($join_table)];
						}

					}

				}

				foreach($child_tables as $child_table)
				{
					$serie_id = $ret[$this->_to_alias($table)]['id'];

					//$result = $this->query('DESCRIBE `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $child_table . '`');
					//$discribe = $this->_to_array($result);
					$discribe = $this->query('DESCRIBE `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $child_table . '`', true);

					$Select = '';
					foreach($discribe as $colum)
						$Select .= ', `' . $this->_to_alias($child_table) . '`.`' . $colum['Field'] . '` AS `' . $this->_to_alias($child_table) . $this->tableAlias_columName_seperator . $colum['Field'] . '`';
					$Select = substr($Select, 2);

					$result = $this->query('SELECT ' . $Select . ' FROM `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $child_table . '` AS `' . $this->_to_alias($child_table) . '` WHERE `' . $this->_to_alias($child_table) . '`.`' . $this->_to_single($table) . '_id` = ' . $serie_id);
					$result = $this->_to_array_alias($result);
					//$result = $this->query('SELECT ' . $Select . ' FROM `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $child_table . '` AS `' . $this->_to_alias($child_table) . '` WHERE `' . $this->_to_alias($child_table) . '`.`' . $this->_to_single($table) . '_id` = ' . $serie_id, true);

					$return[$key][$this->_to_alias($child_table)] = array();
					foreach($result as $result_slim)
						$return[$key][$this->_to_alias($child_table)][] = $result_slim[$this->_to_alias($child_table)];
				}

				foreach($parent_tables as $parent_table => $parse_data)
				{
					if(is_int($parent_table))
					{
						$parent_table = $parse_data;
						$parse_data = array();
					}

					$serie_id = $ret[$this->_to_alias($table)][$this->_to_single($parent_table) . '_id'];

					//$result = $this->query('DESCRIBE `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $parent_table . '`');
					//$discribe = $this->_to_array($result);
					$discribe = $this->query('DESCRIBE `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $parent_table . '`', true);

					$Select = '';
					$select_fields = array();
					foreach($discribe as $colum)
					{
						$Select .= ', `' . $this->_to_alias($parent_table) . '`.`' . $colum['Field'] . '` AS `' . $this->_to_alias($parent_table) . $this->tableAlias_columName_seperator . $colum['Field'] . '`';
						$select_fields['`' . $this->_to_alias($parent_table) . '`.`' . $colum['Field'] . '`'] = $colum['Field'];
					}
					$Select = substr($Select, 2);

					foreach($parse_data as $parsed_data => $parsed_value)
					{
						switch($parsed_data)
						{
							case 'fields' :

								if($parsed_value == '!')
									$select_fields = array();

								if(is_array($parsed_value))
								{

									foreach($parsed_value as $een => $twee)
									{
										if(is_int($een) && is_string($twee))
										{
											if($twee == '!')
												$select_fields = array();
											else
												$select_fields[$twee] = $twee;
										}else
										{
											if($een == '!')
											{
												if(!is_array($twee))
													$twee = array($twee);
												foreach($twee as $exclude)
													unset($select_fields[array_search($exclude, $select_fields)]);
											}else
												$select_fields[$een] = $twee;
										}
									}
								}
							break;
						}
					}

					$Select = '';
					foreach($select_fields as $select_colum => $select_alias)
						$Select .= ', ' . $select_colum . ' AS `' . $this->_to_alias($parent_table) . $this->tableAlias_columName_seperator . $select_alias . '`';
					$Select = substr($Select, 2);

					if(empty($Select))
						$Select = '`' . $this->_to_alias($parent_table) . '`.`id` AS `' . $this->_to_alias($parent_table) . $this->tableAlias_columName_seperator . 'id`';

					$result = $this->query('SELECT ' . $Select . ' FROM `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $parent_table . '` AS `' . $this->_to_alias($parent_table) . '` WHERE `id` = ' . $serie_id . ' LIMIT 1');
					$result = $this->_to_array_alias($result);
					//$result = $this->query('SELECT ' . $Select . ' FROM `' . (!empty($this->prefix) ? $this->prefix . '_' : '') . $parent_table . '` AS `' . $this->_to_alias($parent_table) . '` WHERE `id` = ' . $serie_id . ' LIMIT 1', true);

					$return[$key][$this->_to_alias($parent_table)] = $result[0][$this->_to_alias($parent_table)];

				}

			}






			if($return_type == 'first')
				return $return[0];
			return $return;
		}



		function save($data)
		{

			$table_alias = reset(array_keys($data));
			$table = $this->_to_table($table_alias);

			$created = false;
			$updated = false;

			$result = $this->query('DESCRIBE `' . $table . '`');
			$discribe = $this->_to_array($result);
			$this->_notify($discribe);
			foreach($discribe as $colum)
			{
				if($colum['Key'] == 'PRI')
					$primairy_key = $colum['Field'];
				if($colum['Field'] == 'created')
					$created = true;
				if($colum['Field'] == 'updated')
					$updated = true;
			}

			if(isset($data[$table_alias][$primairy_key]) && $data[$table_alias][$primairy_key] > 0)
			{
				if($updated)
					$data[$table_alias]['updated'] = date('Y-m-d H:i:s');
				return $this->update($data);
			}else{
				if($updated)
					$data[$table_alias]['updated'] = date('Y-m-d H:i:s');
				if($created)
					$data[$table_alias]['created'] = date('Y-m-d H:i:s');
				return $this->insert($data);
			}
		}

		function update($data)
		{
			$table_alias = reset(array_keys($data));
			$table = $this->_to_table($table_alias);

			$return = 'ehhe';

			$updated = false;
			$colums = array();
			$primairy_key = '';
			foreach($data[$table_alias] as $colum => $value)
			{
				$colums[$colum] = $value;
			}

			$discribe = $this->query('DESCRIBE `' . $table . '`', true);
			$this->_notify($discribe);
			foreach($discribe as $colum)
			{
				if($colum['Key'] == 'PRI')
					$primairy_key = $colum['Field'];
				if($colum['Field'] == 'updated')
					$updated = true;
			}

			if($updated)
				$colums['updated'] = date('Y-m-d H:i:s');

			$set = '';
			foreach($colums as $colum => $value)
			{
				$set .= '`' . mysql_real_escape_string($colum) . '` = \'' . mysql_real_escape_string($value) . '\',';
			}

			$set = substr($set, 0, -1);

			$query = '
				UPDATE `' . mysql_real_escape_string($table) . '`
				SET
					' . $set . '
				WHERE
					`' . $primairy_key . '` = ' . $colums[$primairy_key] . '
			';

			$this->_notify($query);
			$this->query($query);

			$params = array(
				$table => array(
					'conditions' => array(
						'id' => $colums[$primairy_key]
					),
					'select' => 'first'
				)
			);

			$return = $this->select($params);

			return $return;

		}

		function insert($data)
		{
			$table_alias = reset(array_keys($data));
			$table = $this->_to_table($table_alias);

			$created = false;
			$updated = false;
			$colums = '';
			$values = '';
			foreach($data[$table_alias] as $colum => $value)
			{
				$colums .= mysql_real_escape_string($colum) . ', ';
				//if(is_numeric($value))
				//	$values .= $value . ', ';
				//else
					$values .= '\'' . mysql_real_escape_string($value) . '\', ';
			}

			$colums = substr($colums, 0, strlen($colums)-2);
			$values = substr($values, 0, strlen($values)-2);

			$discribe = $this->query('DESCRIBE `' . mysql_real_escape_string($table) . '`', true);
			$this->_notify($discribe);
			foreach($discribe as $colum)
			{
				if($colum['Field'] == 'created')
					$created = true;
				if($colum['Field'] == 'updated')
					$updated = true;
			}

			if($created)
			{
				$colums .= ', created';
				$values .= ', \'' . date('Y-m-d H:i:s') . '\'';
			}

			if($updated)
			{
				$colums .= ', updated';
				$values .= ', \'' . date('Y-m-d H:i:s') . '\'';
			}

			$query = '
				INSERT INTO `' . mysql_real_escape_string($table) . '`
				(' . $colums . ')
				VALUES
				(' . $values . ')
			';

			$this->_notify($query);
			$this->query($query);

			$inserted_id = mysql_insert_id();

			if($inserted_id > 0)
			{
				$params = array(
					$table => array(
						'conditions' => array(
							'id' => mysql_insert_id()
						),
						'select' => 'first'
					)
				);

				$return = $this->select($params);
			}else
				$return = false;

			return $return;
		}

		function delete($data)
		{
			$table_alias = reset(array_keys($data));


			$where = '';

			if(!is_int($table_alias))
			{
				$data[0] = $data;
			}

			foreach($data as $dat)
			{
				$table_alias = reset(array_keys($dat));
				$table = $this->_to_table($table_alias);
				if(!$table_alias)
					return false;
				$query = '
					DELETE FROM `' . $table . '`
					WHERE id = ' . $dat[$table_alias]['id'] . '
				';
				if(!$this->query($query))
					return false;
				$this->_notify('DELETED ' . $dat[$table_alias]['id'] . ' `' . $table_alias . '`');
			}

			return true;


			/*
			foreach($data as $alias => $table_data)
			{
				if(is_int($table_data['id']))
				{
					$table = $this->_to_table($alias);
					$query = '
						DELETE FROM `' . $table . '`
						WHERE id = ' . $table_data['id'] . '
					';
					if($this->query($query))
						return $table_data['id'];
				}
			}
			return false;
			*/
		}


		function errors()
		{
			if(count($this->errors) > 0)
			{
				print('<pre>');
					print_r($this->errors);
				print('</pre>');
			}
		}

		function dump()
		{
			print('<pre>');
			print_r($this->debug);
			print('</pre>');
		}


		function _to_single($table_name)
		{
			$convertion = array_search($table_name, $this->convertions);
			if(!empty($convertion))
				return $convertion;
			return substr($table_name, 0, strlen($table_name)-1);
		}

		function _to_table($alias_name)
		{
			return strtolower($alias_name) . 's';
		}

		function _to_alias($table_name)
		{
			$convertion = array_search($table_name, $this->convertions);
			if(!empty($convertion))
				return ucfirst($convertion);
			return ucfirst(substr($table_name, 0, strlen($table_name)-1));
		}

		function _to_array($mysql_resource)
		{
			$return = array();
			while($row = @mysql_fetch_array($mysql_resource, MYSQL_ASSOC))
				$return[] = $row;
			return $return;
		}

		function _to_array_alias($mysql_resource)
		{
			$return = array();
			$i = 0;
			while($row = @mysql_fetch_array($mysql_resource, MYSQL_ASSOC))
			{
				foreach($row as $colum => $value)
				{
					$table = reset(explode($this->tableAlias_columName_seperator, $colum));
					$new_colum = substr($colum, strlen($table)+1);
					$new_colum_first = substr($new_colum, 0, 1);
					if($new_colum_first == strtolower($new_colum_first))
						$return[$i][$table][$new_colum] = $value;
					else
					{
						$table2 = reset(explode($this->tableAlias_columName_seperator, $new_colum));
						$new_colum2 = substr($new_colum, strlen($table2)+1);
						$return[$i][$table][$table2][$new_colum2] = $value;
					}
				}
				$i++;
			}
			return $return;
		}

		function _notify($notify)
		{
			$this->debug[$this->session_nr][] = $notify;
		}
		function _warning($warning)
		{
			$this->debug[$this->session_nr][] = $warning;
		}
		function _error($error)
		{
			$this->debug[$this->session_nr][] = $error;
			$this->errors[] = $error;
		}

		function debug()
		{
			print('
				<table width="100%" style="background-color: #ffffff; ">
				<th style="background-color: #cccccc; color: #000000; " align="left">query</th>
				<th style="background-color: #cccccc; color: #000000; ">took (ms)</th>
			');
			$i = 0;
			foreach($this->qurytimes as $querytime)
			{
				$uneven = ($i % 2 == 0);
				print('
					<tr>
						<td style="' . ($uneven ? 'background-color: #dedede; ' : 'background-color: #ffffff; ') . ' color: #000000; " align="left" valign="middle">' . $querytime['query'] . '</td>
						<td style="' . ($uneven ? 'background-color: #dedede; ' : 'background-color: #ffffff; ') . ' color: #000000; ' . ($querytime['cached'] === true ? 'font-style: italic; ' : 'font-weight: bold; ') . ' " align="center" valign="middle" width="100">' . number_format($querytime['time'] * 1000, 2, '.', '') . '</td>
					</tr>
				');
				$i++;
			}
			print('</table>');
		}

	}

?>
