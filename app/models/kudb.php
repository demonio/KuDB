<?php

class Kudb
{
	public function initialize()
	{
		
	}
	
	public function setConfig( $host, $database, $model )
	{
		# MODIFICACION DEL DATABASES.INI
		$databases = Config::read('databases');
		
		$s = '';
		foreach ( $databases as $host => $data )
		{
			$data['name'] = ( $host == $data['host'] ) ? $database : $data['name'];
			
			$s .= "\r\n";
			$s .= "[" . $data['host'] . "]\r\n";
			$s .= "host = " . $data['host'] . "\r\n";
			$s .= "username = " . $data['username'] . "\r\n";
			$s .= "password = " . $data['password'] . "\r\n";
			$s .= "name = " . $data['name'] . "\r\n";
			$s .= "type = " . $data['type'] . "\r\n";
		}
		_fs::updateFile(APP_PATH . "config/databases.ini", $s);

		# MODIFICACION DEL CONFIG.INI
		$config = Config::read('config');
		
		$s = "\r\n[application]\r\n";
		foreach ( $config as $config )
		{
			foreach ( $config as $k => $v )
			{
				if ( $k == 'database' ) $v = $host;
				
				$s .= $k . ' = "' . $v . '"' . "\r\n";
			}
		}
		_fs::updateFile(APP_PATH . "config/config.ini", $s);
		
		# CREACION DE ARCHIVO $MODELO.PHP
		if ( ! file_exists( APP_PATH . "/models/$model.php" ) ) $this->setModelFile( $model );
		
		# MODELO SELECCIONADO
		$_SESSION['model'] = $model;

		# BASE DE DATOS SELECCIONADA
		$_SESSION['database'] = $database;
	}
	
	public function getDatabaseConfig()
	{
		# EL SERVIDOR Y LA BASE DE DATOS POR DEFECTO EN EL CONFIG.INI
		$config = Config::read('config');
		$config_database = $config['application']['database'];
		$databases = Config::read('databases');
		
		# TODOS LOS SERVIDORES Y BASES DE DATOS EN EL DATABASES.INI
		foreach ( $databases as $host => $data )
		{						
			$_SESSION['hosts'][$host]['host'] = $data['host'];
			$_SESSION['hosts'][$host]['username'] = $data['username'];
			$_SESSION['hosts'][$host]['password'] = $data['password'];
			$_SESSION['hosts'][$host]['database'] = $data['name'];
			
			if ( $config_database == $host )
			{
				# MODELO POR DEFECTO
				$models = $this->getTables( $data['host'], $data['name'] );
				$_SESSION['model'] = $model = $models[0];

				# CREACION DE ARCHIVO $MODELO.PHP
				if ( ! file_exists( APP_PATH . "/models/$model.php" ) ) $this->setModelFile( $model );

				# BASE DE DATOS SELECCIONADA
				$_SESSION['database'] = $data['name'];
			}
			
		}
		#_::_die_( $_SESSION );
	}
	
	public function setModelFile( $model )
	{
		$Model = preg_replace( '/(?:^|_)(.?)/e', "strtoupper('$1')", $model );
					
		$s =
'<?php

class ' . $Model . ' extends ActiveRecord
{

}
';	
		_fs::createFile(APP_PATH . "models/$model.php", $s);
	}
	
	public function getDatabases( $host )
	{
		$username = $_SESSION['hosts'][$host]['username'];
		$password = $_SESSION['hosts'][$host]['password'];		
		mysql_connect( $host, $username, $password );
		$q = mysql_query( 'SHOW DATABASES' );
		while ( $databese = mysql_fetch_array( $q ) ) $databeses[] = $databese[0];
		return $databeses;
	}
	
	public function getTables( $host, $database )
	{
		$host = $_SESSION['hosts'][$host]['host'];
		$username = $_SESSION['hosts'][$host]['username'];
		$password = $_SESSION['hosts'][$host]['password'];		
		mysql_connect( $host, $username, $password );
	    mysql_select_db( $database );
		$q = mysql_query( 'SHOW TABLES' );
		while ( $table = mysql_fetch_array( $q ) ) $tables[] = $table[0];
		return $tables;
	}
}
