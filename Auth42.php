<?php

class Auth42
{

	/* The 42 ldap url */
	const serverUrl = "ldaps://ldap.42.fr";

	protected $dn;
	protected $bind;
	protected $password;
	protected $handle;

	/**
	 * Will construct a new Auth42 object, wich is a ldap based search and
	 * authentication tool.
	 * 
	 * @param array $dn the dn to use. If no dn is provided, will use 
	 * ou=2013,ou=people,dc=42,dc=fr as the default dn.
	 * @param string $password an optionnal password to connect with.
	 */
	public function __construct ($dn = array(), $password = "")
	{
		if (count ($dn))
			$this->dn = $dn;
		else
		{
			$this->dn = array (
				"ou" => array("2013","people"),
				"dc" => array("42", "fr")
			);
		}
		$this->password = $password;
		$this->handle = ldap_connect (self::serverUrl);
		ldap_set_option($this->handle, LDAP_OPT_PROTOCOL_VERSION, 3);
	}

	/**
	 * Will release the handle.
	 */
	public function __destruct ()
	{
		ldap_close($this->handle);
	}

	/**
	 * Will try to bind the ldap with the previously given dn and password.
	 * @return int the binding result (0 on failure).
	 */
	public function bind()
	{
		if ($this->handle)
		{
            $this->bind = ldap_bind(
					$this->handle,
					$this->computeDn (),
					$this->password
				);
		}
		return ($this->bind);
	}

	/**
	 * Will try to authenticate the user on the 42 ldap with the given
	 * password and username.
	 * 
	 * @param string $login the login (uid) of the student
	 * @param string $password the password of the student
	 * @return boolean true on success, false otherwise.
	 */
	static function authenticate ($login, $password)
	{
		$tmp = new Auth42(array(), $password);
		$tmp->addOrReplaceDn("uid", $login);
		return ($tmp->bind());
	}

	/**
	 * Will convert the arrays of dn into an undersandable string.
	 * @return string a string with all the dn(s).
	 */
	protected function computeDn ()
	{
		$computed = array();
		foreach ($this->dn as $key => $value)
		{
			if (is_array ($value))
			{
				foreach ($value as $field)
					$computed[] = $key.'='.$field;
			}
			else
				$computed[] = $key.'='.$value;
		}
		return (implode(',', $computed));
	}
	
	/* 
	 * ======================================================================
	 *								DN Management
	 * ======================================================================
	 */

	/**
	 * Will add (or replace) the value of the given key with
	 * the given (new) value(s). If there is more than one value to add, use
	 * an array instead of a string.
	 * 
	 * @param string $key the key to add / replace.
	 * @param string|array $value the value(s) to add.
	 */
	public function addOrReplaceDn($key, $value)
	{
		$this->dn[$key] = $value;
	}

	/**
	 * Remove the given dn key of the current instance.
	 * @param string $key the dn to remove.
	 */
	public function removeDn($key)
	{
		unset($this->dn[$key]);
	}

	/* 
	 * ======================================================================
	 *                        Getters and Setters
	 * ======================================================================
	 */

	public function getDn ()
	{
		return $this->dn;
	}

	public function getBind ()
	{
		return $this->bind;
	}

	public function getPassword ()
	{
		return $this->password;
	}

	public function getHandle ()
	{
		return $this->handle;
	}

	public function setDn ($dn)
	{
		$this->dn = $dn;
	}

	public function setBind ($bind)
	{
		$this->bind = $bind;
	}

	public function setPassword ($password)
	{
		$this->password = $password;
	}

	public function setHandle ($handle)
	{
		$this->handle = $handle;
	}
}

?>