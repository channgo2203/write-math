<?php
require_once '../svg.php';
include '../init.php';

function add_classification($user_id, $raw_data_id, $latex) {
    global $pdo;

    // Get formula id if it is already in the database
    $sql = "SELECT `id` FROM `wm_formula` ".
           "WHERE `formula_in_latex` = :latex";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':latex', $latex, PDO::PARAM_STR);
    $stmt->execute();
    $formula_id = $stmt->fetchObject()->id;

    if($formula_id == 0) {
        // it was not in the database. Add it.
    }

    // check if raw_data_id 
}

if (isset($_GET['raw_data_id'])) {
    $raw_data_id = $_GET['raw_data_id'];
    $sql = "SELECT `user_id`, `data`, `creation_date`, ".
                                   "`accepted_formula_id` ".
                                   "FROM `wm_raw_draw_data` WHERE `id` = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $_GET['raw_data_id'], PDO::PARAM_INT);
    $stmt->execute();
    $row = $stmt->fetchObject();

    $user_id = $row->user_id;
    $data = $row->data;
    $creation_date = $row->creation_date;
    $accepted_formula_id = $row->accepted_formula_id;

    // Add a new classification
    if (isset($_POST['latex'])) {
        $user_id = get_uid();
        $latex = $_POST['latex'];
        $raw_data_id = $_GET['raw_data_id'];
        add_classification($user_id, $raw_data_id, $latex);
    }

    // Get all probable classifications
    $sql = "SELECT `display_name`, `formula_in_latex`, `formula_id` ".
           "FROM `wm_raw_data2formula`".
           "INNER JOIN `wm_users` ON `wm_raw_data2formula`.`user_id` = `wm_users`.`id`".
           "INNER JOIN `wm_formula` ON `wm_raw_data2formula`.`formula_id` = `wm_formula`.`id`".
           "WHERE `raw_data_id` = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':id', $_GET['raw_data_id'], PDO::PARAM_INT);
    $stmt->execute();
    $answers = $stmt->fetchAll();
}

echo $twig->render('view.twig', array('heading' => 'View',
                                       'logged_in' => is_logged_in(),
                                       'display_name' => $_SESSION['display_name'],
                                       'file' => "view",
                                       'path' => get_path($data),
                                       'user_id' => $user_id,
                                       'creation_date' => $creation_date,
                                       'accepted_formula_id' => $accepted_formula_id,
                                       'raw_data_id' => $raw_data_id,
                                       'answers' => $answers
                                       )
                  );

?>