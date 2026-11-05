<?php
    require __DIR__ . '/donnees.php';
    require __DIR__ . '/connexion.php';


    $etatsAutorises = ['toutes', 'demarre', 'fini'];
    $etat = $_GET['etat'] ?? 'toutes';
    
    $titrePage = "Ma liste de tâches";
    $compterTachesRestantes = 0;
    $requetes = $pdo->query('SELECT Id_quete, nom, etat, exclamation FROM quete');
    $quetes = $requetes->fetchAll();
    $total=count($quetes);
    function compterTachesRestantes(array $quetes): int
    {
    $restantes=0;

        foreach ($quetes as $plop_de_la_madame):
            if ($plop_de_la_madame['exclamation'] === 1) {
                $restantes++;
            }
        endforeach; 
        return $restantes;
    }
    
if (!in_array($etat, $etatsAutorises, true)) {
    $etat = 'toutes';
}
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

    $restantes=compterTachesRestantes($quetes);
    $Resultat = $total - $restantes; 

    include __DIR__ . '/assert/header.php';
?>



<main>
        <h1><?php echo $titrePage; ?></h1>

<p>le resultat(<?php echo $Resultat; ?>) est egal a total(<?php echo $total; ?>) - restantes(<?php echo $restantes; ?>)</p>

<div> les quetes sont :</div>

<form action="ajouter.php" method="post">
    <label for="nom">Nom de la quête</label>
    <input id="nom" name="nom" required maxlength="150">

    <label for="etat">Etat</label>
    <select id="etat" name="etat">
        <option value="a_demarrer">a demarrer</option>
        <option value="en_cours">en cours</option>
        <option value="finit">finit</option>
    </select>

    <button type="submit">Ajouter</button>
</form>

<nav>
    <a href="index.php">Toutes les quêtes</a>
    <a href="index.php?etat=demarre">Quêtes en cours</a>
    <a href="index.php?etat=fini">Quêtes terminées</a>
</nav>

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

<style>
    .terminee {
        color: green;
    }
    .a-faire {
        color: red;
    }
</style>