<?php

class BaseOutputSanitizer extends Dinkly
{
	//An array containing substrings to search variable names for, used for bypassing sanitization
	protected $exception_substrings = array();

	public function sanitizeOutput($variable_name, $variable_value)
	{
		//If the passed variable name matches one of the exceptions, bypass
		if($this->variable_override_substrings != array())
		{
			foreach($variable_override_substrings as $substring)
			{
				if(stristr($variable_name, $substring))
				{
					return false;
				}
			}
		}
		else
		{
			if(is_object($variable_value))
			{
				return $this->sanitizeObject($variable_value);
			}
			elseif(is_array($variable_value))
			{
				return $this->sanitizeArray($variable_value);
			}
			else
			{
				return $this->sanitizeScalar($variable_value); 
			}
		}

		return false;
	}

	public function setExceptionSubstrings($exception_array)
	{
		$this->exception_substrings = $exception_substrings;
	}

	protected function sanitizeScalar($value)
	{
		if($value === null)
		{
			return null;
		}

		if($value === false)
		{
			return false;
		}

		if($value === 0)
		{
			return 0;
		}

		if($value === '')
		{
			return '';
		}

		return mb_convert_encoding(htmlentities($value, ENT_QUOTES, 'UTF-8'), 'UTF-8', 'UTF-8');
	}

	protected function sanitizeArray($array)
	{
		$output = array();

		foreach($array as $key => $value)
		{
			if(is_object($value))
			{
				$output[$key] = $this->sanitizeObject($value);
			}
			elseif(is_array($value))
			{
				$output[$key] = $this->sanitizeArray($value);
			}
			else
			{
				$output[$key] = $this->sanitize($value);
			}
		}

		return $output;
	}

	protected function sanitizeObject($object)
	{
		$variables = get_object_vars($object);

		$output_object = clone $object;

		foreach($variables as $name => $value)
		{
			$output_object->{$name} = $this->sanitizeOutput($value);
		}

		return $output_object;
	}
}