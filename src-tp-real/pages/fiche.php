<?php
    include('../inc/functions.php');

    // $_GET['emp_no'] = valeur du paramètre passé dans l'URL (ex. fiche.php?emp_no=10001).
    // ?? est l'opérateur de "coalescence des nuls" (null coalescing operator, PHP 7+).
    // Il signifie : "prends $_GET['emp_no'] s'il EXISTE et n'est PAS null, sinon prends ''".
    // Cela évite un warning "Undefined array key" si l'URL ne contient pas le paramètre.
    // Équivaut à : isset($_GET['emp_no']) ? $_GET['emp_no'] : ''
    $emp_no   = $_GET['emp_no'] ?? '';
    $employee = get_one_employee($emp_no);
    $salary_history = get_salary_history($emp_no);
    $title_history  = get_title_history($emp_no);
    $longest_title  = get_longest_title($emp_no);
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Fiche employé</title>
        <link rel="stylesheet" href="../design/theme-dark/style.css">
    </head>
    <body>
    <nav class="navbar">
        <ul>
            <li class="brand">Employés DB</li>
            <li><a href="index.php">Départements</a></li>
            <li><a href="search.php">Rechercher</a></li>
            <li><a href="stats.php">Statistiques</a></li>
            <li><a href="emp_form.php">Ajouter un employé</a></li>
        </ul>
    </nav>

    <div class="container">
        <p><a href="javascript:history.back()" class="btn btn-secondary">&larr; Retour</a></p>

        <?php if (!$employee) { ?>
            <div class="alert alert-error"><h1>Employé introuvable</h1></div>
        <?php } else { ?>
            <h1><?= $employee['first_name'] ?> <?= $employee['last_name'] ?></h1>
            <p class="mb">
                <a href="change_dept.php?emp_no=<?= urlencode($employee['emp_no']) ?>">
                    <button type="button" class="btn">Changer de département</button>
                </a>
                <a href="become_manager.php?emp_no=<?= urlencode($employee['emp_no']) ?>">
                    <button type="button" class="btn">Devenir Manager</button>
                </a>
                <a href="emp_form.php?emp_no=<?= urlencode($employee['emp_no']) ?>">
                    <button type="button" class="btn btn-secondary">Modifier l'employé</button>
                </a>
            </p>
            
            <div class="card">
                <table class="table">
                    <tr><th>N°</th>              <td><?= $employee['emp_no'] ?></td></tr>
                    <tr><th>Prénom</th>          <td><?= $employee['first_name'] ?></td></tr>
                    <tr><th>Nom</th>             <td><?= $employee['last_name'] ?></td></tr>
                    <tr><th>Genre</th>           <td><?= $employee['gender'] ?></td></tr>
                    <tr><th>Date de naissance</th><td><?= $employee['birth_date'] ?></td></tr>
                    <tr><th>Date d'embauche</th> <td><?= $employee['hire_date'] ?></td></tr>
                    <tr><th>Poste actuel</th>    <td><?= $employee['title'] ?? '—' ?></td></tr>
                    <tr><th>Département</th>      <td><?= $employee['dept_name'] ?? '—' ?></td></tr>
                    <tr><th>Salaire actuel</th>  <td><?= isset($employee['salary']) ? number_format($employee['salary'], 0, ',', ' ') . ' €' : '—' ?></td></tr>
                    <tr><th>Emploi le plus long</th>
                        <td>
                            <?php if ($longest_title) { ?>
                                <?= $longest_title['title'] ?>
                                (<?= round($longest_title['duree_jours'] / 365, 1) ?> ans,
                                du <?= $longest_title['from_date'] ?>
                                au <?= $longest_title['to_date'] === '9999-01-01' ? 'en cours' : $longest_title['to_date'] ?>)
                            <?php } else { echo '—'; } ?>
                        </td>
                    </tr>
                </table>
            </div>

            <h2 class="mt">Historique des emplois</h2>
            <table class="table">
                <tr>
                    <th>Poste</th>
                    <th>Du</th>
                    <th>Au</th>
                </tr>
                <?php foreach ($title_history as $t) { ?>
                    <tr>
                        <td><?= $t['title'] ?></td>
                        <td><?= $t['from_date'] ?></td>
                        <td><?= $t['to_date'] === '9999-01-01' ? 'en cours' : $t['to_date'] ?></td>
                    </tr>
                <?php } ?>
            </table>

            <h2 class="mt">Historique des salaires</h2>
            <table class="table">
                <tr>
                    <th>Salaire</th>
                    <th>Du</th>
                    <th>Au</th>
                </tr>
                <?php foreach ($salary_history as $s) { ?>
                    <tr>
                        <td><?= number_format($s['salary'], 0, ',', ' ') ?> €</td>
                        <td><?= $s['from_date'] ?></td>
                        <td><?= $s['to_date'] === '9999-01-01' ? 'en cours' : $s['to_date'] ?></td>
                    </tr>
                <?php } ?>
            </table>
        <?php } ?>
    </div>
    </body>
</html>