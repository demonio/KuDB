<?php

class Objetos extends ActiveRecord
{
	public function initialize()
	{
		$this->belongs_to( 'personas' );
	}
}
