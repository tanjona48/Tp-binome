<?php
    include('../inc/functions.php');

    $departments = get_all_departments();

    // Récupération des critères (?? '' évite le warning si le champ est absent)
    $dept_no = $_GET['dept_no'] ?? '';
    $name    = $_GET['name']    ?? '';
    $age_min = $_GET['age_min'] ?? '';
    $age_max = $_GET['age_max'] ?? '';

    // On ne lance la recherche que si le formulaire a été soumis
    $submitted = isset($_GET['dept_no']);
    $results   = $submitted ? search_employees($dept_no, $name, $age_min, $age_max) : array();
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Recherche d'employés</title>
        <link rel="stylesheet" href="../design/theme-dark/style.css">
    </head>
    <body>
    <nav class="navbar">
        <ul>
            <li class="brand">Employés DB</li>
            <li><a href="index.php">Départements</a></li>
            <li><a href="search.php" class="active">Rechercher</a></li>
            <li><a href="stats.php">Statistiques</a></li>
            <li><a href="emp_form.php">Ajouter un employé</a></li>
        </ul>
    </nav>

    <div class="container">
        <p><a href="index.php" class="btn btn-secondary">&larr; Retour aux départements</a></p>
        <h1>Recherche d'employés</h1>

        <div class="card">
            <form method="get" action="search.php">
                <div class="form-group">
                    <label>Département :</label>
                    <select name="dept_no" class="form-control">
                        <option value="">— Tous —</option>
                        <?php foreach ($departments as $d) { ?>
                            <option value="<?= $d['dept_no'] ?>" <?= $dept_no === $d['dept_no'] ? 'selected' : '' ?>>
                                <?= $d['dept_name'] ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>Nom de l'employé :</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($name) ?>">
                </div>
                <div class="form-group">
                    <label>Âge min :</label>
                    <input type="number" name="age_min" class="form-control" value="<?= htmlspecialchars($age_min) ?>">
                </div>
                <div class="form-group">
                    <label>Âge max :</label>
                    <input type="number" name="age_max" class="form-control" value="<?= htmlspecialchars($age_max) ?>">
                </div>
                <div class="form-group">
                    <input type="submit" class="btn" value="Rechercher">
                </div>
            </form>
        </div>

        <?php if ($submitted) { ?>
            <h2 class="mt"><?= count($results) ?> résultat(s)<?= count($results) === 200 ? ' (limité à 200)' : '' ?></h2>
            <table class="table">
                <tr>
                    <th>N°</th>
                    <th>Prénom</th>
                    <th>Nom</th>
                    <th>Genre</th>
                    <th>Âge</th>
                    <th>Département</th>
                </tr>
                <?php foreach ($results as $emp) { ?>
                    <tr>
                        <td><a href="fiche.php?emp_no=<?= urlencode($emp['emp_no']) ?>"><?= $emp['emp_no'] ?></a></td>
                        <td><?= $emp['first_name'] ?></td>
                        <td><?= $emp['last_name'] ?></td>
                        <td><?= $emp['gender'] ?></td>
                        <td><?= $emp['age'] ?></td>
                        <td><?= $emp['dept_name'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
    </body>
</html>