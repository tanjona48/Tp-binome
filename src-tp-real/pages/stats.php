<?php
    include('../inc/functions.php');
    $stats = get_jobs_stats();
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Statistiques par emploi</title>
        <link rel="stylesheet" href="../design/theme-dark/style.css">
    </head>
    <body>
    <nav class="navbar">
        <ul>
            <li class="brand">Employés DB</li>
            <li><a href="index.php">Départements</a></li>
            <li><a href="search.php">Rechercher</a></li>
            <li><a href="stats.php" class="active">Statistiques</a></li>
            <li><a href="emp_form.php">Ajouter un employé</a></li>
        </ul>
    </nav>

    <div class="container">
        <p><a href="index.php" class="btn btn-secondary">&larr; Retour aux départements</a></p>
        <h1>Statistiques par emploi</h1>

        <table class="table">
            <tr>
                <th>Emploi</th>
                <th>Hommes</th>
                <th>Femmes</th>
                <th>Total</th>
                <th>Salaire moyen</th>
            </tr>
            <?php foreach ($stats as $row) { ?>
                <tr>
                    <td><?= $row['title'] ?></td>
                    <td><?= $row['nb_hommes'] ?></td>
                    <td><?= $row['nb_femmes'] ?></td>
                    <td><?= $row['nb_total'] ?></td>
                    <td><?= number_format($row['salaire_moyen'], 0, ',', ' ') ?> €</td>
                </tr>
            <?php } ?>
        </table>
    </div>
    </body>
</html>