<?php
/**
 * PHP version 5
 * LICENSE
 *
 * This source file is subject to the GNU/GPL that is bundled
 * with this package in the file docs/LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to distrotuz@gmail.com so we can send you a copy immediately.
 *
 * @author demomnio69 #kumbiaphp
 */

class _
{
	static public function  _return_( $x ) { return _page::_text_( $x ); }
	
	static public function  _echo_( $x ) { echo _page::_text_( $x ); }
	
	static public function  _die_( $x ) { die( _page::_text_( $x ) ); }
	
	/**
	 * Devuelve el valor de un array[index] si no esta vacio
	 * 
	 * @param string $index indice del array
	 * @param array $array matriz
	 * @return mix retorna el valor o false si no esta definido el index
	 */
	static public function _var_( $index, $array )
	{
		if ( ! empty( $array[$index] ) )
		{
			echo $array[$index];
		}
	}
	
	/**
	 * Devuelve los datos de una tabla o consulta
	 * 
	 * @param string $table matriz con llaves y valores a verificar
	 * @return string
	 */
	static public function query($sql)
	{
        # SI $sql ES UNA PALABRA, SE HACE UNA CONSULTA A LA TABLA PALABRA
        if ( ! strstr( $sql, ' ') ) $sql = "SELECT * FROM $sql";
        
		$db = DbBase::raw_connect();
        $query = $db->query( $sql );
        while ( $row = mysql_fetch_object( $query ) ) $rows[] = $row;
        $db->close();
        return $rows;
	}
	
	/**
	 * Verifica si existen las llaves en $a, para evitar el notice de index no existe
	 * 
	 * @param array $a matriz con llaves y valores a verificar
	 * @param array $keys llaves a verificar en $a
	 * @return array
	 */
	static function is_empty( $a, $keys ) 
	{
		foreach ( $keys as $k => $v )
		{
			if ( is_array( $v ) )
			{
				foreach ( $v as $name )
				{
					$b[$k][$name] = empty($a[$k][$name]) ? '' : $a[$k][$name];
				}
			}
			else $b[$v] = empty($a[$v]) ? '' : $a[$v];
		}
		return $b;
	}
	
	/**
	 * Devuelve una tabla preparada para copy paste en excel
	 * 
	 * @param array $rows resultado de un find de KumbiaPHP
	 * @return string
	 */
	static function grid($rows) 
	{
		$c = count( $rows );
		$s = '';
		if ($c == 0) return $s;
		
		$s .= '<table><tr>';
		$cols = $rows[0]->fields;
		foreach ( $cols as $col )
		{
			$s .= "<td class=\"head\"><strong>$col&nbsp;</strong></td>\t";
		}
		$s .= "</tr>\n";
		foreach ( $rows as $row )
		{
			$s .= '<tr>';
			foreach ( $cols as $col )
			{
				$v = $row->$col;
				$s .=  "<td>$v&nbsp;</td>\t";
			}
			$s .= "</tr>\n";
		}
		return $s . "</table>$c results.";
	}
	
	/**
	 * Descarga el bufer para imprimir varibles en ejecucion
	 */
	static public function flush()
	{
		ob_flush();
		flush();
	}
	
	/**
	 * Configura una funcion javascript
	 * 
	 * @param string $fn funcion javascript
	 * @param string $value cadena de valores para la funcion
	 * @param number $delay tiempo en segundos
	 * @return string
	 */                        
	static public function js( $fn, $value, $delay=0 )
	{
		# ALGUNAS FUNCIONES JS TIENEN OTRO FORMATO
		$a = array( 'location' );
	
		# FORMATO FN='VALUE';
		if ( in_array( $fn, $a ) )
		{
			$s = "$fn='$value';";
		}
		# FORMATO FN('VALUE');
		else
		{
			$s = "$fn('$value');";
		}
	
		# RETARDO PARA LANZAR LA FUNCION EN SEGUNDOS    
		if ( $delay ) $s = _js::_delay_( $fn, $delay );
		
		return $s;
	}
	
	/**
	 * Convierte una cadena de texto a una key para una variable
	 * 
	 * @param string $s cadena de texto a convertir
	 * @return string
	 */                                
	static public function to_var($s)
	{
		$s = str_replace(' ', '_', $s);
		return strtolower( $s );
	}
	
	/**
	 */
	static public function go( $url )
	{
		
	}
	
	/**
	 * Hace una redireccion detectando si es ajax o si el javascript esta activado
	 *
	 * @param string $url url de destino
	 * @param number $delay tiempo en segundos
	 */
	static public function _go_($url, $delay=0.5)
	{
		if ( ! preg_match( '/^http/', $url ) and defined( 'PUBLIC_PATH' ) ) $url = PUBLIC_PATH . ltrim( $url, '/' );
		
		# SE COMPRUEBA SI SE ENVIARON CABECERAS. POR EJEMPLO COMO PASA CON AJAX
		if ( headers_sent() )
		{
			# SI EL USUARIO TIENE JAVASCRIPT ACTIVADO SE USA LOCATION
			echo _page::wrap( self::js('location', $url, $delay), 'script' );
			
			# SI NO LO TIENE SE USA META
			echo _page::wrap( '<meta http-equiv="refresh" content="' . $delay . ';url=' . $url . '" />', 'noscript' );
		}
		else
		{
			# SI NO SE ENVIARON CABECERAS, AQUI VA UNA
			header( "refresh:$delay;url=$url" );
		}
		exit();
	}

	/**
	 * Imprime las constantes definidas rompiendo el flujo
	 */			
	static public function _constants_()
	{
		$a = get_defined_constants( 1 );
		self::_die_( $a['user'] );
	}

	/**
	 * Imprime las constantes de un tipo o clase o todas las clases rompiendo el flujo
	 * 
	 * @param string $type /^session|post|get|classes|class$/
	 * @param string $class el nombre de la clase para si $type es igual a class
	 */			
	static public function _vars_( $type='', $class='' )
	{
		if ( $type == 'session' )
		{
			self::_die_( $_SESSION );
		}
		else if ( $type == 'post' )
		{
			self::_die_( $_POST );
		}
		else if ( $type == 'get' )
		{
			self::_die_( $_GET );
		}
		else if ( $type == 'classes' )
		{
			$classes = get_declared_classes();
			foreach ( $classes as $class )
			{
				$vars[$class] = get_class_vars( $class );
			}
			self::_die_( $vars );		
		}
		else if ( $type == 'class' )
		{
			$vars[$class] = get_class_vars( $class );
			self::_die_( $vars );		
		}
	}
}