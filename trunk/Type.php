<?php

class Validation_Type
{
	const MSG = 'Wymagany typ %s. Podano %s.';

	static protected function message( $exp, $passed )
	{
		return sprintf(self::MSG, $exp, $passed);
	}

	static public function isBool( $data )
	{
		if ( !is_bool($data))
		{
			throw new Validation_Exception_BoolExpected(
				self::message('bool', gettype($data))
			);
		}
		return true;
	}

	static public function isNull( $data )
	{
		if ( !is_null($data))
		{
			throw new Validation_Exception_NullExpected(
				self::message('null', gettype($data))
			);
		}
		return true;
	}

	static public function isInteger( $data )
	{
		if ( !is_integer($data))
		{
			throw new Validation_Exception_IntExpected(
				self::message('int', gettype($data))
			);
		}
		return true;
	}

	static public function isFloat( $data )
	{
		if ( !is_float($data))
		{
			throw new Validation_Exception_FloatExpected(
				self::message('float', gettype($data))
			);
		}
		return true;
	}

	static public function isString( $data )
	{
		if ( !is_string($data))
		{
			throw new Validation_Exception_StringExpected(
				self::message('string', gettype($data))
			);
		}
		return true;
	}

	static public function isNotEmptyString( $data )
	{
		self::isString($data);
		if (empty($data))
		{
			throw new Validation_Exception_NotEmptyStringExpected(
				self::message('string', gettype($data))
			);
		}
		return true;
	}

	static public function isArray( &$data )
	{
		if ( !is_array($data))
		{
			throw new Validation_Exception_ArrayExpected(
				self::message('array', gettype($data))
			);
		}
		return true;
	}

	static public function isObject( $data )
	{
		if ( !is_object($data))
		{
			throw new Validation_Exception_ObjectExpected(
				self::message('object', gettype($data))
			);
		}

		return true;
	}

	
	static public function isNumeric( $data )
	{
		if ( !is_numeric($data))
		{
			throw new Validation_Exception_NumericExpected(
				self::message('liczba', gettype($data))
			);
		}
		return true;
	}

	static public function is( $data, $type )
	{
		if ( gettype($data) === strtolower($type) )
		{
			throw new Validation_Exception_Type(
				self::message($type, gettype($data))
			);
		}
		return true;
	}
}
