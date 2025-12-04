<?php
// save_spell.php - обработка добавления новых заклинаний
require_once 'includes/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $spellName = $_POST['spellName'] ?? '';
    $combination = $_POST['combination'] ?? '';
    $description = $_POST['description'] ?? '';
    $damageType = $_POST['damageType'] ?? '';
    $manaCost = $_POST['manaCost'] ?? 0;
    $cooldown = $_POST['cooldown'] ?? 0;
    $spellType = $_POST['spellType'] ?? 'Basic';
    
    if (!empty($spellName) && !empty($combination) && !empty($description) && !empty($damageType)) {
        try {
            $result = $db->addSpell($spellName, $combination, $description, $damageType, $manaCost, $cooldown, $spellType);
            
            if ($result) {
                header('Location: index.php?success=1#database');
                exit;
            } else {
                header('Location: index.php?error=save_failed#database');
                exit;
            }
        } catch(PDOException $e) {
            header('Location: index.php?error=db_error#database');
            exit;
        }
    } else {
        header('Location: index.php?error=missing_fields#database');
        exit;
    }
} else {
    header('Location: index.php');
    exit;
}
?>