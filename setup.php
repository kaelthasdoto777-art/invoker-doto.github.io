<?php
// setup.php - гарантированное создание базы данных
try {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    
    // Подключаемся к MySQL без выбора базы данных
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Создаем базу данных
    $pdo->exec("DROP DATABASE IF EXISTS InvokerDoto");
    $pdo->exec("CREATE DATABASE InvokerDoto CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    $pdo->exec("USE InvokerDoto");
    
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Setup - InvokerDoto Database</title>
        <style>
            * { margin: 0; padding: 0; box-sizing: border-box; }
            body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #0c0e1c; color: #e0e0e0; min-height: 100vh; padding: 2rem; }
            .container { max-width: 800px; margin: 0 auto; background: rgba(16, 19, 38, 0.9); border-radius: 15px; padding: 2rem; }
            h1 { text-align: center; margin-bottom: 2rem; color: #9c88ff; font-size: 2.5rem; }
            .status { padding: 1rem; margin: 1rem 0; border-radius: 8px; border-left: 4px solid; }
            .success { background: rgba(46, 204, 113, 0.2); border-color: #2ecc71; color: #2ecc71; }
            .error { background: rgba(231, 76, 60, 0.2); border-color: #e74c3c; color: #e74c3c; }
            .warning { background: rgba(241, 196, 15, 0.2); border-color: #f1c40f; color: #f1c40f; }
            .btn { display: inline-block; background: linear-gradient(90deg, #00a8ff, #9c88ff); color: white; padding: 1rem 2rem; border-radius: 8px; text-decoration: none; font-weight: bold; margin: 0.5rem; }
            .stats { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; margin: 2rem 0; }
            .stat-card { background: rgba(255, 255, 255, 0.1); padding: 1rem; border-radius: 8px; text-align: center; }
            .stat-number { font-size: 2rem; font-weight: bold; color: #00a8ff; }
        </style>
    </head>
    <body>
        <div class='container'>
            <h1>🎮 InvokerDoto Database Setup</h1>";
    
    // Создаем таблицу spells
    $pdo->exec("CREATE TABLE spells (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        combination VARCHAR(10) NOT NULL,
        description TEXT NOT NULL,
        damage_type VARCHAR(50) NOT NULL,
        mana_cost INT DEFAULT 0,
        cooldown INT DEFAULT 0,
        spell_type ENUM('Basic', 'Advanced', 'Ultimate') DEFAULT 'Basic',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "<div class='status success'>✅ Table 'spells' created successfully</div>";
    
    // Создаем таблицу builds
    $pdo->exec("CREATE TABLE builds (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        description TEXT,
        items TEXT NOT NULL,
        playstyle ENUM('Quas-Wex', 'Quas-Exort', 'Wex-Exort', 'Hybrid') DEFAULT 'Hybrid',
        difficulty ENUM('Easy', 'Medium', 'Hard') DEFAULT 'Medium',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
    echo "<div class='status success'>✅ Table 'builds' created successfully</div>";
    
    // Создаем таблицу stats
    $pdo->exec("CREATE TABLE stats (
        id INT AUTO_INCREMENT PRIMARY KEY,
        attribute_name VARCHAR(255) NOT NULL,
        attribute_value VARCHAR(255) NOT NULL,
        category VARCHAR(100) NOT NULL
    )");
    echo "<div class='status success'>✅ Table 'stats' created successfully</div>";
    
    // Добавляем заклинания
    $spells = [
        ['Cold Snap', 'QQQ', 'Freezes the target enemy in an icy prison, stunning and damaging them when they take damage', 'Magical', 100, 20, 'Basic'],
        ['Ghost Walk', 'QQW', 'Invoker turns invisible, creating a freezing field that slows nearby enemies', 'None', 200, 35, 'Basic'],
        ['Ice Wall', 'QQE', 'Creates a wall of solid ice that slows and damages enemies who pass through it', 'Magical', 175, 25, 'Advanced'],
        ['EMP', 'WWW', 'Drains mana from enemy heroes in an area after a short delay', 'Magical', 125, 30, 'Basic'],
        ['Tornado', 'WWQ', 'Launches a fast-moving tornado that damages and throws enemies into the air', 'Magical', 150, 30, 'Basic'],
        ['Alacrity', 'WWE', 'Increases attack speed and damage of a target ally for a short duration', 'None', 75, 15, 'Basic'],
        ['Sun Strike', 'EEE', 'Calls a destructive ray from the sun at any visible location on the battlefield', 'Pure', 175, 25, 'Basic'],
        ['Forge Spirit', 'EEQ', 'Creates a controllable fire entity that attacks enemies and reduces their armor', 'Physical', 75, 30, 'Advanced'],
        ['Chaos Meteor', 'EEW', 'Calls a massive meteor to strike the land, damaging and burning enemies in its path', 'Magical', 200, 55, 'Ultimate'],
        ['Deafening Blast', 'QWE', 'Unleashes a powerful sonic wave that damages, disarms, and knocks back enemies', 'Magical', 300, 40, 'Ultimate']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO spells (name, combination, description, damage_type, mana_cost, cooldown, spell_type) VALUES (?, ?, ?, ?, ?, ?, ?)");
    foreach ($spells as $spell) {
        $stmt->execute($spell);
    }
    echo "<div class='status success'>✅ Added " . count($spells) . " spells</div>";
    
    // Добавляем сборки
    $builds = [
        ['Classic Quas-Wex', 'Utility and control build focusing on teamfight impact and survivability', '["Hand of Midas", "Aghanim\'s Scepter", "Boots of Travel", "Blink Dagger", "Shiva\'s Guard", "Refresher Orb"]', 'Quas-Wex', 'Medium'],
        ['Magic Burst Exort', 'High magical damage output with Exort for nuking potential', '["Aghanim\'s Scepter", "Octarine Core", "Kaya and Sange", "Eul\'s Scepter", "Black King Bar", "Boots of Travel"]', 'Quas-Exort', 'Hard'],
        ['Physical Carry', 'Right-click Invoker with physical damage and attack speed', '["Maelstrom", "Dragon Lance", "Daedalus", "Black King Bar", "Butterfly", "Moon Shard"]', 'Wex-Exort', 'Medium'],
        ['Hybrid Control', 'Balanced build with both magical and physical damage options', '["Aghanim\'s Scepter", "Orchid Malevolence", "Black King Bar", "Linken\'s Sphere", "Boots of Travel", "Scythe of Vyse"]', 'Hybrid', 'Hard']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO builds (name, description, items, playstyle, difficulty) VALUES (?, ?, ?, ?, ?)");
    foreach ($builds as $build) {
        $stmt->execute($build);
    }
    echo "<div class='status success'>✅ Added " . count($builds) . " builds</div>";
    
    // Добавляем статистику
    $stats = [
        ['Movement Speed', '285', 'Attributes'],
        ['Attack Range', '600', 'Attributes'],
        ['Attack Animation', '0.4 + 0.7', 'Attributes'],
        ['Base Attack Time', '1.7', 'Attributes'],
        ['Vision Range', '1800/800', 'Attributes'],
        ['Turn Rate', '0.5', 'Attributes'],
        ['Strength', '18 + 2.4', 'Base Stats'],
        ['Agility', '14 + 1.9', 'Base Stats'],
        ['Intelligence', '15 + 2.7', 'Base Stats'],
        ['Win Rate', '48.2%', 'Game Stats'],
        ['Pick Rate', '12.7%', 'Game Stats'],
        ['Ban Rate', '3.4%', 'Game Stats']
    ];
    
    $stmt = $pdo->prepare("INSERT INTO stats (attribute_name, attribute_value, category) VALUES (?, ?, ?)");
    foreach ($stats as $stat) {
        $stmt->execute($stat);
    }
    echo "<div class='status success'>✅ Added " . count($stats) . " stats</div>";
    
    // Показываем статистику
    $spellCount = $pdo->query("SELECT COUNT(*) as count FROM spells")->fetch()['count'];
    $buildCount = $pdo->query("SELECT COUNT(*) as count FROM builds")->fetch()['count'];
    $statCount = $pdo->query("SELECT COUNT(*) as count FROM stats")->fetch()['count'];
    
    echo "<div class='stats'>
            <div class='stat-card'>
                <div class='stat-number'>$spellCount</div>
                <div>Spells</div>
            </div>
            <div class='stat-card'>
                <div class='stat-number'>$buildCount</div>
                <div>Builds</div>
            </div>
            <div class='stat-card'>
                <div class='stat-number'>$statCount</div>
                <div>Stats</div>
            </div>
          </div>";
    
    echo "<div class='status success' style='text-align: center;'>
            <h3>🚀 Setup Complete!</h3>
            <p>Database has been successfully created and populated with sample data.</p>
            <a href='index.php' class='btn'>Go to Invoker Website</a>
          </div>";
    
} catch (PDOException $e) {
    echo "<div class='status error'>❌ Setup failed: " . $e->getMessage() . "</div>";
    echo "<div class='status warning'>Please check your MySQL configuration in config.php</div>";
}

echo "</div></body></html>";
?>