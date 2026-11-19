<?php

$quetesExportees = [];
$messageErreur = null;

try {
    require __DIR__ . '/stockage.php';

    if (!is_file($fichierExport)) {
        throw new RuntimeException(
            'Aucun export disponible. Exportez d’abord les quêtes.'
        );
    }

    $contenu = file_get_contents($fichierExport);

    if ($contenu === false) {
        throw new RuntimeException(
            'Le fichier d’export ne peut pas être lu.'
        );
    }

    $donnees = json_decode(
        $contenu,
        true,
        512,
        JSON_THROW_ON_ERROR
    );

    // Un JSON valide ne contient pas forcément une liste de quêtes.
    if (!is_array($donnees) || !array_is_list($donnees)) {
        throw new RuntimeException(
            'Le fichier ne contient pas une liste de quêtes.'
        );
    }

    foreach ($donnees as $quete) {
        if (
            !is_array($quete)
            || !isset($quete['Nom'])
            || !is_string($quete['Nom'])
        ) {
            throw new RuntimeException(
                'Une quête du fichier ne possède pas de nom valide.'
            );
        }
    }

    $quetesExportees = $donnees;
} catch (JsonException $erreur) {
    $messageErreur = 'Le fichier contient un JSON invalide.';
} catch (RuntimeException $erreur) {
    $messageErreur = $erreur->getMessage();
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Dernier export des quêtes</title>
</head>
<body>
    <h1>Dernier export des quêtes</h1>

    <?php if ($messageErreur !== null): ?>
        <p>
            <?= htmlspecialchars(
                $messageErreur,
                ENT_QUOTES,
                'UTF-8'
            ) ?>
        </p>
    <?php elseif (empty($quetesExportees)): ?>
        <p>L’export ne contient aucune quête.</p>
    <?php else: ?>
        <ul>
            <?php foreach ($quetesExportees as $quete): ?>
                <li>
                    <?= htmlspecialchars(
                        $quete['Nom'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <a href="index.php">Retour au journal</a>
</body>
</html>
