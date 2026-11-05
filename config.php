<?php
// À adapter à votre installation locale. Ne pas utiliser root en production.
return [
   'host' => '127.0.0.1',
   'port' => '3306',
   'base' => 'jdgsave',
   'utilisateur' => 'root',
   'mot_de_passe' => '',
];

$requete = $pdo->query(
   'SELECT id, titre, priorite, terminee FROM taches'
);
$taches = $requete->fetchAll();
