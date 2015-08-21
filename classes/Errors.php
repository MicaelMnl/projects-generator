<?php

class Errors
{
	private $errors = array();

	public function __construct()
	{
		
	}

	public function add($msg, $field = '')
	{
		if(!empty($msg))
		{
			if(!empty($field))
			{
				if(is_array($field))
				{
					foreach($field AS $f)
						$this->add($msg, $f);
				}
				else
					$this->errors[$field] = $msg;
			}
			else
				$this->errors[] = $msg;
		}
	}

	public function has($field)
	{
		if(isset($this->errors[$field]))
			return true;

		return false;
	}

	public function get($field)
	{
		if(isset($this->errors[$field]))
			return $this->errors[$field];
	}

	public function getAll()
	{
		return $this->errors;
	}

	public function clear()
	{
		$this->errors = array();
	}

	public function isEmpty()
	{
		return empty($this->errors);
	}

	public function json()
	{
		Utils::json(array('result' => 'error', 'errors' => $this->errors));
	}
}