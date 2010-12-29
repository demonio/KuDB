<?php

class Personas extends ActiveRecord
{
	public function initialize()
	{
		$this->belongs_to( 'empresas' );
	}	
}
