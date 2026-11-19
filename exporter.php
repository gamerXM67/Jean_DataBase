<?php

require_once __DIR__ . '/securite.php';

verifierFormulaire();

require_once __DIR__ . '/connexion.php';

try {
    require __DIR__ . '/stockage.php';

    $requete = $pdo->query(
        'SELECT Id_quete, nom, etat, exclamation
         FROM quete
         ORDER BY Id_quete'
    );

    $quetes = $requete->fetchAll(PDO::FETCH_ASSOC);

    $json = json_encode(
        $quetes,
        JSON_PRETTY_PRINT
        | JSON_UNESCAPED_UNICODE
        | JSON_THROW_ON_ERROR
    );

    $resultat = file_put_contents(
        $fichierExport,
        $json,
        LOCK_EX
    );

    if ($resultat === false) {
        throw new RuntimeException(
            'Impossible d’écrire le fichier JSON.'
        );
    }

    $_SESSION['message'] = 'Les quêtes ont été exportées.';
} catch (JsonException $erreur) {
    $_SESSION['message'] =
        'L’export a échoué : les données ne peuvent pas être encodées.';
} catch (RuntimeException $erreur) {
    $_SESSION['message'] =
        'L’export a échoué : vérifiez le stockage et ses permissions.';
}

header('Location: index.php', true, 303);
exit;
