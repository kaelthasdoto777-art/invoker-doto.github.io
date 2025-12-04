<?php
// includes/database.php - функции для работы с базой данных
require_once 'config.php';

class InvokerDatabase {
    private $pdo;

    public function __construct() {
        global $pdo;
        $this->pdo = $pdo;
    }

    // Получить все заклинания
    public function getAllSpells() {
        $stmt = $this->pdo->query("SELECT * FROM spells ORDER BY spell_type, name");
        return $stmt->fetchAll();
    }

    // Получить заклинания по типу
    public function getSpellsByType($type) {
        $stmt = $this->pdo->prepare("SELECT * FROM spells WHERE spell_type = ? ORDER BY name");
        $stmt->execute([$type]);
        return $stmt->fetchAll();
    }

    // Добавить новое заклинание
    public function addSpell($name, $combination, $description, $damage_type, $mana_cost = 0, $cooldown = 0, $spell_type = 'Basic') {
        $stmt = $this->pdo->prepare("
            INSERT INTO spells (name, combination, description, damage_type, mana_cost, cooldown, spell_type) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$name, $combination, $description, $damage_type, $mana_cost, $cooldown, $spell_type]);
    }

    // Получить все сборки
    public function getAllBuilds() {
        $stmt = $this->pdo->query("SELECT * FROM builds ORDER BY difficulty, name");
        return $stmt->fetchAll();
    }

    // Получить сборки по стилю игры
    public function getBuildsByPlaystyle($playstyle) {
        $stmt = $this->pdo->prepare("SELECT * FROM builds WHERE playstyle = ? ORDER BY difficulty");
        $stmt->execute([$playstyle]);
        return $stmt->fetchAll();
    }

    // Получить статистику
    public function getStats($category = null) {
        if ($category) {
            $stmt = $this->pdo->prepare("SELECT * FROM stats WHERE category = ?");
            $stmt->execute([$category]);
        } else {
            $stmt = $this->pdo->query("SELECT * FROM stats ORDER BY category, attribute_name");
        }
        return $stmt->fetchAll();
    }

    // Получить общую статистику
    public function getGeneralStats() {
        $stats = [];
        
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM spells");
        $stats['total_spells'] = $stmt->fetch()['count'];
        
        $stmt = $this->pdo->query("SELECT COUNT(*) as count FROM builds");
        $stats['total_builds'] = $stmt->fetch()['count'];
        
        $stmt = $this->pdo->query("SELECT spell_type, COUNT(*) as count FROM spells GROUP BY spell_type");
        $stats['spell_types'] = $stmt->fetchAll();
        
        $stmt = $this->pdo->query("SELECT playstyle, COUNT(*) as count FROM builds GROUP BY playstyle");
        $stats['playstyles'] = $stmt->fetchAll();
        
        return $stats;
    }
}

// Создаем экземпляр для использования
$db = new InvokerDatabase();
?>