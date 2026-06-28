<?php
    include('../inc/functions.php');

    $emp_no   = $_GET['emp_no'] ?? '';
    $employee = get_one_employee($emp_no);
    $current  = get_current_department($emp_no);

    $error   = '';
    $success = false;

    // Traitement du formulaire (méthode POST car on modifie la base)
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $new_dept = $_POST['dept_no']   ?? '';
        $start    = $_POST['from_date'] ?? '';

        if ($new_dept === '' || $start === '') {
            $error = "Veuillez choisir un département et une date de début.";
        } elseif ($current && $start < $current['from_date']) {
            // c. Erreur si la date de début est antérieure à celle du département actuel
            $error = "La date de début ($start) ne peut pas être antérieure à celle du département actuel (" . $current['from_date'] . ").";
        } else {
            change_department($emp_no, $new_dept, $start);
            $success = true;
            // a. On recharge le département courant pour vérifier qu'il a bien changé
            $current = get_current_department($emp_no);
        }
    }

    // b. La liste déroulante exclut le département actuel
    $departments = get_departments_except($current ? $current['dept_no'] : '');
?>
<html>
    <head>
        <title>Changer de département</title>
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
    <p><a href="fiche.php?emp_no=<?= urlencode($emp_no) ?>" class="btn">&larr; Retour à la fiche</a></p>

    <?php if (!$employee) { ?>
        <h1>Employé introuvable</h1>
    <?php } else { ?>
        <h1>Changer le département de <?= $employee['first_name'] ?> <?= $employee['last_name'] ?></h1>

        <?php if ($success) { ?>
            <p style="color:green;">Changement effectué.</p>
        <?php } ?>
        <?php if ($error !== '') { ?>
            <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        <?php } ?>

        <!-- b. Département actuel affiché en haut, avec sa date de début -->
        <p>
            <strong>Département actuel :</strong>
            <?= $current ? $current['dept_name'] . ' (depuis le ' . $current['from_date'] . ')' : 'aucun' ?>
        </p>
        <div class="card">
        <form method="post" action="change_dept.php?emp_no=<?= urlencode($emp_no) ?>">
            <p>
            <div class="form-group">
                Nouveau département :
                <select name="dept_no" class="form-control">
                    <option value="">— Choisir —</option>
                    <?php foreach ($departments as $d) { ?>
                        <option value="<?= $d['dept_no'] ?>"><?= $d['dept_name'] ?></option>
                    <?php } ?>
                </select>
                </div>
            </p>
            <div class="form-group">
            <p>Date de début : <input class="form-control"type="date" name="from_date"></p>
            <p><input type="submit" value="Changer de département" class="btn btn-secondary"></p>
            </div>
        </form>
        </div>
    <?php } ?>
    </div>
    </body>
</html>
