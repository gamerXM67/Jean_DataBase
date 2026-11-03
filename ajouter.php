<?php

require __DIR__ . '/connexion.php';

$nom = $_POST['nom'] ?? '';
$etat = $_POST['etat'] ?? '';

if (!is_string($nom) || !is_string($etat)) {
    exit('Données invalides.');
};

$nom = trim($nom);
$etat = trim($etat);

if ($nom==='') {
    exit('Le titre est obligatoire.');
};

if (!in_array($etat, ['a_demarrer', 'en_cours', 'finit'], true)) {
    exit('La priorité est invalide.');
};

echo 'Données reçues et valides.';

$requetes = $pdo->prepare(
    'INSERT INTO quete (nom, etat, exclamation)
     VALUES (:nom, :etat, 0)'
);

$requetes->execute([
    'nom' => $nom,
    'etat' => $etat,
]);

echo 'Tâche enregistrée !';