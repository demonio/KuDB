<?php

class KudbController extends ScaffoldController 
{	
	public function lol()
	{
		/*mysql_query
		( "
			CREATE TABLE IF NOT EXISTS `editable`
			(
				`id` int(11) AUTO_INCREMENT,
				`deleted` tinyint(1) DEFAULT '0',
				`modified_in` datetime,
				`created_at` datetime,
				PRIMARY KEY (`id`)
			)
			ENGINE=MyISAM AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COLLATE=utf8_spanish_ci
        " );*/
	}
	
	public function salir()
	{
		$_SESSION = array();
		Router::redirect( 'kudb' );
		exit;
	}
	
	public function config( $server, $database, $model )
	{
		Load::model( 'kudb' )->setConfig( $server, $database, $model );
		Router::redirect( 'kudb' );
		exit;
	}
}
