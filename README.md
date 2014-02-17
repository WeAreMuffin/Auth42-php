Auth42
======

Classe d'authentification en PHP permettant d'authentifier un utilisateur de 42 (a l'aide d'une requete sur le ldap de 42)


Usage typique
-------------

```php
<?php

/* On inclut la classe */
require_once '../Auth42.php';

/* On spécifie le login et le password de connexion */
$login = "monlogin";
$password = "monpassword";

/* On essaye de s'authentifier */
$result = Auth42::authenticate ($login, $password);

if ($result === true)
    echo "Les identifiants sont valides !";
else
    echo "Les identifiants sont invalides...";
?>
```

Pour pousser un peu plus loin, et avoir, par exemple, des infos sur l'échec 
d'une connexion, il faut faire la connexion a la main, en instanciant un
object `Auth42` et en poussant un peu plus loin:

```php
/* ... */

$login = "monlogin";
$password = "monpassword";

$auth = new Auth42();
$auth->setPassword ($password);
$auth->setDn (array (
    "uid" => $login,
    "ou" => array ("2013", "people"),
    "dc" => array ("42", "fr")
));

try
{
    $result = $auth->bind ();
    if ($result === true)
        echo "Les identifiants sont valides !";
    else
        echo "Les identifiants sont invalides...";
}
catch (Exception $e)
{
    echo "Une erreur s'est produite : ".$e->getMessage();
}
```

Documentation
------------------

Une courte documentation (autogénerée) est dispo ici: http://wearemuffin.github.io/Auth42/

Tests
------
(requiert phpunit, ca doit pouvoir se regler avec un `yum install php-phpunit`):

La classe a été _globalement_ testée, avec un task runner et PHPUnit.
Pour lancer les tests, on va dans le dossier `tests` et on lance phpunit.

```sh
cd tests
```
```sh
phpunit .
```
