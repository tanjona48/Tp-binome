# Les codes ou la logique que j'ai maintenant compris

```php
function get_all_lines($sql){
    //echo $sql;
    $req = mysqli_query(dbconnect(),$sql );
    if (!$req) {
        die('Erreur SQL : ' . mysqli_error(dbconnect()));
    }
    $result = array();
    while ($line = mysqli_fetch_assoc($req)) {
        $result[] = $line;
    }
    mysqli_free_result($req);
    return $result;
}

function get_one_line($sql){

    $req = mysqli_query(dbconnect(),$sql );
    if (!$req) {
        die('Erreur SQL : ' . mysqli_error(dbconnect()));
    }
    $result = mysqli_fetch_assoc($req);
    mysqli_free_result($req);
    return $result;
}

<?php foreach ($departments as $d) { ?>
                        <option value="<?= $d['dept_no'] ?>"><?= $d['dept_name'] ?></option>
<?php } ?>

$submitted = isset($_GET['dept_no']);

```

# Les codes ou la logique que je n'ai pas encore compris 



```php
function search_employees($dept_no, $name, $age_min, $age_max)
{
    // ⚠️ sprintf n'échappe pas : injection SQL toujours possible (à sécuriser avec une requête préparée).
    // On construit la clause WHERE dynamiquement selon les champs remplis.
    $conditions = array();

    if ($dept_no !== '') {
        $conditions[] = sprintf("de.dept_no = '%s'", $dept_no);
    }
    if ($name !== '') {
        // %% produit un % littéral dans sprintf → '%nom%' pour le LIKE
        $conditions[] = sprintf("(e.first_name LIKE '%%%s%%' OR e.last_name LIKE '%%%s%%')", $name, $name);
    }
    if ($age_min !== '') {
        $conditions[] = sprintf("TIMESTAMPDIFF(YEAR, e.birth_date, CURDATE()) >= %d", $age_min);
    }
    if ($age_max !== '') {
        $conditions[] = sprintf("TIMESTAMPDIFF(YEAR, e.birth_date, CURDATE()) <= %d", $age_max);
    }

    // S'il n'y a aucun filtre, "1=1" garde une clause WHERE valide.
    $where = empty($conditions) ? '1=1' : implode(' AND ', $conditions);

    $sql = "SELECT DISTINCT
                   e.emp_no,
                   e.first_name,
                   e.last_name,
                   e.gender,
                   TIMESTAMPDIFF(YEAR, e.birth_date, CURDATE()) AS age,
                   d.dept_name
            FROM employees e
            INNER JOIN dept_emp de
                    ON de.emp_no = e.emp_no AND de.to_date = '9999-01-01'
            INNER JOIN departments d
                    ON d.dept_no = de.dept_no
            WHERE $where
            ORDER BY e.last_name, e.first_name
            LIMIT 200";
    return get_all_lines($sql);
}

$mode       = $_POST['mode'] ?? 'add';

```

# Les fonctions utilisées que je ne connaissais pas 


```php 
1 . $dept_name = trim($_POST['dept_name'] ?? '');

2 . <p style="color:red;"><?= htmlspecialchars($error) ?></p>


3 . if ($_SERVER['REQUEST_METHOD'] === 'POST' && $current_dept) {
        $start = $_POST['from_date'] ?? '';

4 . <p><a href="fiche.php?emp_no=<?= urlencode($emp_no) ?>">&larr; Retour à la fiche</a></p>

```