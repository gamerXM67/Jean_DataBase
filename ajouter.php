<?php

require_once __DIR__ . '/securite.php';
require_once __DIR__ . '/connexion.php';

verifierFormulaire();

// Lire les valeurs sans les transformer immédiatement.
$quete = $_POST['quete'] ?? '';
$etat = $_POST['etat'] ?? '';

// Vérifier les types avant d’appeler trim().
if (!is_string($quete) || !is_string($etat)) {
    http_response_code(400);
    exit('Données invalides.');
}

$quete = trim($quete);
$etat = trim($etat);

// Contrôler le nom.
if ($quete === '') {
    http_response_code(400);
    exit('Le nom de la quête est obligatoire.');
}

if (mb_strlen($quete, 'UTF-8') > 150) {
    http_response_code(400);
    exit('Le nom ne doit pas dépasser 150 caractères.');
}

// Contrôler l’état.
if (!in_array($etat, ['finit', 'en_cours'], true)) {
    http_response_code(400);
    exit('L’état de la quête est invalide.');
}

// Conserver la cohérence entre les deux colonnes.
$exclamation = ($etat === 'finit') ? 1 : 0;

$requete = $pdo->prepare(
    'INSERT INTO quete (nom, etat, exclamation)
     VALUES (:nom, :etat, :exclamation)'
);

$requete->bindValue(':nom', $quete, PDO::PARAM_STR);
$requete->bindValue(':etat', $etat, PDO::PARAM_STR);
$requete->bindValue(
    ':exclamation',
    $exclamation,
    PDO::PARAM_INT
);

$requete->execute();

// Préparer un message pour la prochaine page.
$_SESSION['message'] = 'La quête a bien été ajoutée.';

// Rediriger après le POST.
header('Location: index.php', true, 303);
exit;
