<?php
    // Inclusion des fichiers de configuration et de connexion à la base
    require __DIR__ . '/securite.php';
    require __DIR__ . '/donnees.php';
    require __DIR__ . '/connexion.php';

    // Paramètres de la page : filtres et état de la liste des quêtes
    $etatsAutorises = ['toutes', 'demarre', 'fini'];
    $etat = $_GET['etat'] ?? 'toutes';
    
    // Informations de la page et comptage global
    $titrePage = "Ma liste de tâches";
    $compterTachesRestantes = 0;
    $requetes = $pdo->query('SELECT Id_quete, nom, etat, exclamation FROM quete');
    $quetes = $requetes->fetchAll();
    $total = count($quetes);

    // Fonction pour calculer le nombre de quêtes encore en cours
    function compterTachesRestantes(array $quetes): int
    {
    $restantes = 0;

        foreach ($quetes as $plop_de_la_madame):
            if ($plop_de_la_madame['exclamation'] === 1) {
                $restantes++;
            }
        endforeach; 
        return $restantes;
    }
    
// Sécurisation du filtre d'état avant l'affichage
if (!in_array($etat, $etatsAutorises, true)) {
    $etat = 'toutes';
}

// Récupération des quêtes selon le filtre sélectionné
if ($etat === 'toutes') {
    $requete = $pdo->query(
        'SELECT Id_quete, nom, etat, exclamation
         FROM quete
         ORDER BY Id_quete DESC'
    );
} else {
    $valeurExclamation = ($etat === 'fini') ? 1 : 0;

    $requete = $pdo->prepare(
        'SELECT Id_quete, nom, etat, exclamation
         FROM quete
         WHERE exclamation = :exclamation
         ORDER BY Id_quete DESC'
    );

    $requete->bindValue(
        ':exclamation',
        $valeurExclamation,
        PDO::PARAM_INT
    );

    $requete->execute();
}

$quetes = $requete->fetchAll(PDO::FETCH_ASSOC);

// Calcul du nombre de quêtes restantes et du résultat final affiché
$restantes = compterTachesRestantes($quetes);
$Resultat = $total - $restantes;

$message = $_SESSION['message'] ?? null;

unset($_SESSION['message']);

// Gestion du thème visuel choisi par l'utilisateur
$theme = $_COOKIE['theme'] ?? 'clair';

if (!in_array($theme, ['clair', 'sombre'], true)) {
    $theme = 'clair';
}



    // Inclusion du layout principal de la page
    include __DIR__ . '/assert/header.php';
?>

<main class="theme-<?= htmlspecialchars(
    $theme,
    ENT_QUOTES,
    'UTF-8'
) ?>">

    <!-- Titre principal de la page -->
    <h1><?php echo $titrePage; ?></h1>

    <?php if (is_string($message)): ?>
    <p role="status">
        <?= htmlspecialchars(
            $message,
            ENT_QUOTES,
            'UTF-8'
        ) ?>
    </p>
<?php endif; ?>

<!-- Bloc des paramètres visuels -->
<h2>Préférence d’affichage</h2>

<form action="preference.php" method="post">
    <input
        type="hidden"
        name="csrf"
        value="<?= htmlspecialchars(
            $_SESSION['csrf'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>"
    >

    <label for="theme">Thème</label>

    <select id="theme" name="theme">
        <option
            value="clair"
            <?= $theme === 'clair' ? 'selected' : '' ?>
        >
            Clair
        </option>

        <option
            value="sombre"
            <?= $theme === 'sombre' ? 'selected' : '' ?>
        >
            Sombre
        </option>
    </select>

    <button type="submit">Enregistrer le thème</button>
</form>

<!-- Résumé du statut actuel des quêtes -->
<p>le resultat(<?php echo $Resultat; ?>) est egal a total(<?php echo $total; ?>) - restantes(<?php echo $restantes; ?>)</p>

<div> les quetes sont :</div>

<!-- Formulaire d'ajout d'une nouvelle quête -->
<h2>Ajouter une quête</h2>

<form action="ajouter.php" method="post">
    <input
        type="hidden"
        name="csrf"
        value="<?= htmlspecialchars(
            $_SESSION['csrf'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>"
    >

    <label for="quete">Nom de la quête</label>
    <input
        id="quete"
        name="quete"
        required
        maxlength="150"
    >

    <label for="etat-quete">État</label>
    <select id="etat-quete" name="etat">
        <option value="en_cours">En cours</option>
        <option value="finit">Terminée</option>
    </select>

    <button type="submit">Ajouter</button>
</form>

<!-- Filtres pour changer la vue de la liste -->
<nav>
    <a href="index.php">Toutes les quêtes</a>
    <a href="index.php?etat=demarre">Quêtes en cours</a>
    <a href="index.php?etat=fini">Quêtes terminées</a>
</nav>

<h2>Export des quêtes</h2>

<form action="exporter.php" method="post">
    <input
        type="hidden"
        name="csrf"
        value="<?= htmlspecialchars(
            $_SESSION['csrf'],
            ENT_QUOTES,
            'UTF-8'
        ) ?>"
    >

    <button type="submit">Exporter les quêtes en JSON</button>
</form>

<p>
    <a href="lire-export.php">Consulter le dernier export</a>
</p>


<!-- Liste des quêtes correspondant au filtre actuel -->
<h2>Quêtes affichées</h2>

<p>
    Dans cette sélection :
    <?= $total ?> quête(s),
    dont <?= $restantes ?> en cours
    et <?= $Resultat ?> terminée(s).
</p>

<?php if (empty($quetes)): ?>

    <p>Aucune quête ne correspond à ce filtre.</p>

<?php else: ?>

    <ul>
        <?php foreach ($quetes as $quete): ?>
            <li>
                <h3>
                    <?= htmlspecialchars(
                        $quete['nom'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </h3>

                <p>
                    <?= htmlspecialchars(
                        $quete['etat'],
                        ENT_QUOTES,
                        'UTF-8'
                    ) ?>
                </p>

                <span>
                    <?= (int) $quete['exclamation'] === 1
                        ? 'Terminée'
                        : 'En cours' ?>
                </span>
            </li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>

</main>

<?php include __DIR__ . '/assert/footer.php'; ?>