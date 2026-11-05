<?php
    require __DIR__ . '/donnees.php';
    require __DIR__ . '/connexion.php';


    $etatsAutorises = ['toutes', 'demarre', 'fini'];
    $etat = $_GET['etat'] ?? 'toutes';
    
    $titrePage = "Ma liste de tâches";
    $compterTachesRestantes = 0;
    $total=count($quetes);
    $requetes = $pdo->query('SELECT Id_quete, nom, etat, exclamation FROM quete');
    $quetes = $requetes->fetchAll();
    function compterTachesRestantes(array $quetes): int
    {
    $restantes=0;

    if (!in_array($etat, $etatsAutorises, true)) {
    $etat = 'toutes';
}
if ($etat === 'toutes') {
    $requete = $pdo->query(
        'SELECT Id_quete, Nom, Etat, exclamation
         FROM quetes
         ORDER BY Id_quete DESC'
    );
} else {
    $valeurExclamation = ($etat === 'fini') ? 1 : 0;

    $requete = $pdo->prepare(
        'SELECT Id_quete, Nom, Etat, exclamation
         FROM quetes
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



        foreach ($quetes as $plop_de_la_madame):
            if ($plop_de_la_madame['exclamation'] === "true") {
                $restantes++;
            }
        endforeach; 
        return $restantes;
    }
    
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

<ul>
    <?php foreach ($quetes as $plop_de_la_madame): ?>
        <li>
         <?= $plop_de_la_madame['nom'] ?> - <?= $plop_de_la_madame['etat'] ?> - <?= $plop_de_la_madame['exclamation'] ?>    
        <!-- Afficher ici les informations de la tâche -->
         <?php 
            if ($plop_de_la_madame['etat'] === 'finit') {
           echo '<span class="terminee">Terminée</span>';
            } else {
           echo '<span class="a-faire">À faire</span>';
            }

         ?>
        </li>
    <?php endforeach; ?>
    <?php $Resultat = $total - $restantes; 
    echo $Resultat;
?>
</ul>
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