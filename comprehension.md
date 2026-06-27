# ETU5085_Iavo
## les codes ou la logique que vous avez **maintenant compris** ;

```php
// Tous les départements SAUF celui passé en paramètre (pour la liste déroulante)
function get_departments_except($dept_no)
{
    $sql = "SELECT dept_no, dept_name
            FROM departments
            WHERE dept_no <> '%s'
            ORDER BY dept_name";
    $sql = sprintf($sql, $dept_no);
    return get_all_lines($sql);
}


// Fait de l'employé le nouveau manager du département
function make_manager($emp_no, $dept_no, $start_date)
{
    // 1) On clôture le mandat du manager actuel à la date de début du nouveau
    $sql1 = "UPDATE dept_manager
             SET to_date = '%s'
             WHERE dept_no = '%s' AND to_date = '9999-01-01'";
    $sql1 = sprintf($sql1, $start_date, $dept_no);
    execute_query($sql1);

    // 2) On insère le nouveau manager comme courant.
    //    ON DUPLICATE KEY UPDATE : si cet employé a déjà managé ce département, on réactive la ligne.
    $sql2 = "INSERT INTO dept_manager (emp_no, dept_no, from_date, to_date)
             VALUES ('%s', '%s', '%s', '9999-01-01')
             ON DUPLICATE KEY UPDATE from_date = '%s', to_date = '9999-01-01'";
    $sql2 = sprintf($sql2, $emp_no, $dept_no, $start_date, $start_date);
    execute_query($sql2);
}


```

## les codes ou la logique que vous **n'avez pas encore compris** ;

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


```

## les **fonctions utilisées que vous ne connaissez pas** (ex. `urlencode`, `htmlspecialchars`, l'opérateur `??`…).

```php
function get_employees_by_department($dept_no, $limit, $offset)
{
    // ⚠️ sprintf n'échappe pas : injection SQL toujours possible (à sécuriser avec une requête préparée).
    // %d force des entiers pour LIMIT et OFFSET (pagination).
    $sql = "SELECT e.emp_no,
                   e.first_name,
                   e.last_name,
                   e.gender,
                   e.hire_date
            FROM employees e
            INNER JOIN dept_emp de
                    ON de.emp_no = e.emp_no
            WHERE de.dept_no = '%s'
              AND de.to_date = '9999-01-01'
            ORDER BY e.last_name, e.first_name
            LIMIT %d OFFSET %d";
    $sql = sprintf($sql, $dept_no, $limit, $offset);
    return get_all_lines($sql);
}



```
```html
<html>
    <p><a href="fiche.php?emp_no=<?= urlencode($emp_no) ?>" class="btn">&larr; Retour à la fiche</a>    

      <p style="color:red;"><?= htmlspecialchars($error) ?></p>
        
</html>

```
