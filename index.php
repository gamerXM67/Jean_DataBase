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

<div>le resultat(<?php echo $Resultat; ?>) est egal a total(<?php echo $total; ?>) - restantes(<?php echo $restantes; ?>)</div>

<div> les quetes sont </div>

<ul>
    <?php foreach ($quetes as $plop_de_la_madame): ?>
        <li>
         <?= $plop_de_la_madame['nom'] ?> - <?= $plop_de_la_madame['etat'] ?> - <?= $plop_de_la_madame['exclamation'] ?>    
        <!-- Afficher ici les informations de la tâche -->
         <?php 
            if ($plop_de_la_madame['exclamation'] === "true") {
                $restantes++;
            }
            echo $restantes;
         ?>
        </li>
    <?php endforeach; ?>
    <?php $Resultat = $total - $restantes; 
    echo $Resultat;
?>
</ul>
</main>

<?php include __DIR__ . '/assert/footer.php'; ?>

