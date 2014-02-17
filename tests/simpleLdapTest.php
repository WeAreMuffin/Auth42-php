<?php

require_once '../Auth42.php';

/**
 * A simple ldap auth test.
 * @author sanecz <lbekdach@student.42.fr>
 */

$login = "";
$password = "";


/* Trying with Auth42 wrapper */

$auth = new Auth42();
$auth->setPassword ($password);
$auth->setDn (array (
    "uid" => $login,
    "ou" => array ("2013", "people"),
    "dc" => array ("42", "fr")
));

try
{
	$info = $auth->search("uid=sbarale");
	    for ($i=0; $i<$info["count"]; $i++) {
	    	var_dump($info);
	    }

    ldap_close($ds);
}
catch (Exception $e)
{
    echo "Une erreur s'est produite : ".$e->getMessage();
}
?>
