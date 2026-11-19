<?php

require __DIR__ . '/Quete.php';

$quete1 = new Quete('Explorer la forêt');
$quete2 = new Quete('Retrouver le grimoire');

$quete1->terminer();

$quetes = [$quete1, $quete2];

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Découverte des objets</title>
</head>
<body>
    <h1>Journal de quêtes avec des objets</h1>

    <ul>
        <?php foreach ($quetes as $quete): ?>
            <li>
                <?= htmlspecialchars(
                    $quete->nom,
                    ENT_QUOTES,
                    'UTF-8'
                ) ?>

                —
                <?= $quete->terminee ? 'Terminée' : 'En cours' ?>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
