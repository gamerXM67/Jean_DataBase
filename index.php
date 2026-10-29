<?php
    require __DIR__ . '/donnees.php';
    require __DIR__ . '/connexion.php';

    
    $titrePage = "Ma liste de tâches";
    $compterTachesRestantes = 0;
    $total=count($quetes);
    $requetes = $pdo->query('SELECT Id_quete, nom, etat, exclamation FROM quete');
    $quetes = $requetes->fetchAll();
    function compterTachesRestantes(array $quetes): int
    {
    $restantes=0;

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

    <label for="priorite">Priorité</label>
    <select id="priorite" name="priorite">
        <option value="haute">Haute</option>
        <option value="moyenne">Moyenne</option>
        <option value="basse">Basse</option>
    </select>

    <button type="submit">Ajouter</button>
</form>

<ul>
    <?php foreach ($quetes as $plop_de_la_madame): ?>
        <li>
         <?= $plop_de_la_madame['nom'] ?> - <?= $plop_de_la_madame['etat'] ?> - <?= $plop_de_la_madame['exclamation'] ?>    
        <!-- Afficher ici les informations de la tâche -->
         <?php 
            if ( $plop_de_la_madame['exclamation'] === 1) {
            echo 'Terminée';
            } else {
            echo 'À faire';
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

