<?php

class Editable extends ActiveRecord
{	
	public function initialize()
	{
		$config = Config::read('config');
		$config_database = $config['application']['database'];
		$databases = Config::read('databases');
		$this->databases = $databases[$config_database];
		#_::_die_( $this->databases );
	}
	
	public function readDatabasesAndTables()
	{
		$databases = $this->find_all_by_sql( 'SHOW DATABASES' );

		foreach ( $databases as $database )
		{
			$database = $database->Database;
			$a[$database] = $this->readTables( $database );
		}
		return $a;
	}

	public function readDatabases()
	{
		$databases = $this->find_all_by_sql( 'SHOW DATABASES' );

		foreach ( $databases as $database )
		{
			$a[] = $database->Database;
		}
		return $a;
	}

	public function readTablesAndFields( $database_name )
	{		
		$tables = $this->find_all_by_sql( 'SHOW TABLES' );

		$k = 'Tables_in_' . $database_name;
		foreach ( $tables as $table )
		{
			$s = $table->$k;
			$a[$s] = $this->readFields( $s );
		}
		return $a;
	}
	
	public function readTables( $database_name )
	{
		mysql_connect( $this->databases['host'], $this->databases['username'], $this->databases['password'] );
	    mysql_select_db( $database_name );
		$q = mysql_query( 'SHOW TABLES' );
		while ( $table = mysql_fetch_array( $q ) ) $tables[] = $table[0];
		return $tables;
	}
	
	public function readFields( $table )
	{		
		$fields = $this->find_all_by_sql( "SHOW COLUMNS FROM $table" );

		foreach ( $fields as $field )
		{
			$s = $field->Field;
			$a[] = $s;
		}		
		return $a;
	}
	
	public function ifEditable( $table )
	{		
		$check = $this->find_first( "conditions: _table='$table'" );

		if ( $check ) return 1;
		return 0;
	}
}
