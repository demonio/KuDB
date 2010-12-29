<?php
/**
 * Todas las controladores heredan de esta clase en un nivel superior
 * por lo tanto los metodos aqui definidos estan disponibles para
 * cualquier controlador.
 *
 * @category Kumbia
 * @package Controller
 **/

// @see Controller nuevo controller
require_once CORE_PATH . 'kumbia/controller.php';

Load::lib( '_' );
Load::lib( '_fs' );

class AppController extends Controller {

	final protected function initialize()
	{
		if ( empty( $_SESSION['hosts'] ) )
		{
			Load::model( 'kudb' )->getDatabaseConfig();
			Router::redirect( 'kudb' );
			exit;
		}
		
		$this->model = $_SESSION['model'];
		#die( $this->model );
		
		View::template( 'kudb' );
	}

	final protected function finalize()
	{
	}
}
