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

if (!in_array($etat, ['haute', 'moyenne', 'basse'], true)) {
    exit('La priorité est invalide.');
};

echo 'Données reçues et valides.';

$requetes = $pdo->prepare(
    'INSERT INTO taches (nom, etat, finit)
     VALUES (:nom, :etat, 0)'
);

$requetes->execute([
    'nom' => $titre,
    'etat' => $priorite,
]);

echo 'Tâche enregistrée !';