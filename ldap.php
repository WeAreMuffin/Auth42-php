<?php
// La séquence de base avec LDAP est
// connexion, liaison, recherche, interprétation du résultat
// déconnexion

echo '<h3>test LDAP</h3>';
$ds=ldap_connect("ldaps://ldap.42.fr", 636);  // doit être un serveur LDAP
echo 'Le résultat de connexion est ' . $ds . '<br />';

if ($ds) {
    echo 'Liaison ...';
    $r=ldap_bind($ds, "dn=user", "");     // connexion
    echo 'Le résultat de connexion est ' . $r . '<br />';

    echo 'Fermeture de la connexion';
    ldap_close($ds);

} else {
    echo '<h4>Impossible de se connecter au serveur LDAP.</h4>';
}
?>
