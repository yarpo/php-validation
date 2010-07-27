<?php

/**
 * klasa pozwalajaca na walidacje wartosci w zmiennych z wykorzystaniem regExpow
 *
 * @autor : Patryk Jar < jar dot patryk at gmail dot com >
 * @data  : 19 - 04 - 2010 r.
 * */


class Validation_Value
{
	/**
	 * Wartosc zwracana, gdy dane sa poprawne
	 * */
	const VALID   = 0;


	/**
	 * Wartsoc zwracana, gdy dane sa nieprawidlowe
	 * */
	const INVALID = 1;


	/**
	 * Warosc zwracana, gdy nie ma ustawionych regul walidacyjnych dla
	 * jednego z pol
	 * */
	const NOT_CHECKED = 2;


	/**
	 * Tablica przekazana do walidacji
	 * */
	protected $aInput;


	/**
	 * Zbior regul:
	 * array
	 * (
	 * 		key => regExp, ...
	 * )
	 * */
	protected $aRules;


	/**
	 * Falaga decydujaca czy przy nieprawidlowosciach podczas sprawdzania
	 * powinien byc rzucany wyjatek
	 * */
	protected $bThrowable;


	/**
	 * Tablica z nieprawidlowosciami odkrytymi podczas walidacji
	 * */
	protected $aInvalid;


	/**
	 * Tablica z wartosciami niesprawdzonymi podczas walidacji
	 * */
	protected $aNotChecked;


	/**
	 * Trzyma wyniki binarnie sprawdzania
	 * */
	protected $iResult;


	/**
	 * Konstruktor
	 *
	 * @param array $rules - zbior regul do walidacji
	 * @param array $input - zbior zmiennych walidaowanych wg rules
	 *
	 * @throws Validation_ArrayExpectedException
	 * */
	public function __construct($rules = array(), $input = array())
	{
		Validation_Type::isArray($input);
		Validation_Type::isArray($rules);
		$this->aInput = $input;
		$this->aRules = $rules;
		$this->bThrowable = false;
		$this->iResult = self::VALID;
	}


	/**
	 * Setter regul walidayjnych
	 *
	 * @param array $rules - nowy znior regul
	 *
	 * @throw Validation_ArrayExpectedException
	 * */
	public function setRules($rules)
	{
		Validation_Type::isArray($rules);
		$this->aRules = $rules;
	}


	/**
	 * getter regul
	 * */
	public function getRules()
	{
		return $this->aRules;
	}


	/**
	 * setter flagi rzucania wyjatkow
	 *
	 * @param bool $throwable - default true
	 *
	 * @throw Validation_BoolExpectedException
	 * */
	public function setThrowable($throwable = true)
	{
		Validation_Type::isBool($throwable);
		$this->bThrowable = $throwable;
	}


	/**
	 * Waliduje podana tablice [domyslnie $this->aInput]
	 *
	 * @input array $input - domyslnie null => $this->aInput
	 *
	 * @throw Validation_ValueException
	 *
	 * @return int 
	 * */
	public function validation($input = null)
	{
		$input = is_null($input) ? $this->aInput : $input;

		Validation_Type::isArray($input);

		$this->aInvalid = array();
		$this->aNotChecked = array();
		$this->iResult = self::VALID;

		foreach($this->aRules as $key => $value)
		{
			if (isset($input[$key]))
			{
				if (self::INVALID ===
						self::validItem($input[$key], $value))
				{
					self::throwException(
						'Nieprawidlowe dane: ' . $key . ' = ' . $input[$key] .
						'[$value]');

					$this->aInvalid[$key] = $input[$key];
					$this->iResult |= self::INVALID;
				}
			}
			else
			{
				self::throwException(
					'Nie Podano danych: ' . $key);

				$this->aNotChecked[$key] = $input[$key];
				$this->iResult |= self::NOT_CHECKED;
			}
		}
		return $this->iResult;
	}


	/**
	 * Zwraca wynik porownania
	 *
	 * @param binary $res
	 *
	 * @return bool
	 * */
	private function result($res)
	{
		return (($this->iResult & $res) === $res);
	}


	/**
	 * Czy dane sa poprawnie zwalidowane != !isNotInvalid()
	 * 		oznacz, ze podczas walidacji nie bylo zadnych bledow [ani invalid,
	 * 		ani notchecked]
	 *
	 * @return bool
	 * */
	public function isValid()
	{
		return ($this->iResult === self::VALID);
	}


	/**
	 * Czy dane sa nieporawnie zwalidowane
	 * */
	public function isNotValid()
	{
		return $this->result(self::INVALID);
	}


	/**
	 * Czy podczas walidacji byly pola, dla ktorych nie ma regul
	 * */
	public function isNotChecked()
	{
		return $this->result(self::NOT_CHECKED);
	}

	/**
	 * metoda pozwalajaca rzucac wyjatki
	 *
	 * @throw Validation_ValueException
	 * */
	private function throwException($message)
	{
		if (true === $this->bThrowable)
		{
			throw new Validation_ValueException($message);
		}
	}


	/**
	 * Getter tablicy niewlasciwie wypelnionych pol
	 * */
	public function getInvalid()
	{
		return $this->aInvalid;
	}


	/**
	 * Getter pol dla ktorych nie bylo regul
	 * */
	public function getNotChecked()
	{
		return $this->aNotChecked;
	}


		/**
	 * Test poprawnosci wartosci zmiennej z podana regula walidacyjna
	 *
	 * @param string $input - ciag znakow do sprawdzenia
	 * @param string $rule - regula walidacyjna
	 *
	 * @return int [VALID, INVALID]
	 * */
	static function validItem($input, $rule)
	{
		return (1 === preg_match($rule, $input)) ? self::VALID : self::INVALID;
	}
}
