<?php
    require __DIR__ . '/donnees.php';

    
    $titrePage = "Ma liste de tâches";
    $compterTachesRestantes = 0;
    $total=count($quetes);

    function compterTachesRestantes(array $quetes): int
    {
    $restantes=0;

        foreach ($quetes as $plop_de_la_madame):
            if ($plop_de_la_madame["Exclamation"] === "true") {
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



<ul>
    <?php foreach ($quetes as $plop_de_la_madame): ?>
        <li>
         <?= $plop_de_la_madame['NomQue'] ?> - <?= $plop_de_la_madame['Etat'] ?> - <?= $plop_de_la_madame['Exclamation'] ?>    
        <!-- Afficher ici les informations de la tâche -->
         <?php 
            if ($plop_de_la_madame["Exclamation"] === "true") {
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

