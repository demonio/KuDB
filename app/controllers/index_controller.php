<?php
/**
 * Controller por defecto si no se usa el routes
 * 
 */
class IndexController extends AppController 
{
	public function index()
	{
		$objetos = Load::model( 'objetos' )->getPersonas()->getEmpresas()->empresa;
		
		_::_die_( $objetos );
	}
}
