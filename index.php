<?php
// index.php - главная страница сайта InvokerDoto
require_once 'includes/database.php';

// Получаем данные из базы
$spells = $db->getAllSpells();
$builds = $db->getAllBuilds();
$stats = $db->getStats();
$generalStats = $db->getGeneralStats();

// Группируем заклинания по типам
$basicSpells = $db->getSpellsByType('Basic');
$advancedSpells = $db->getSpellsByType('Advanced');
$ultimateSpells = $db->getSpellsByType('Ultimate');

// Обработка сообщений
$success = isset($_GET['success']);
$error = $_GET['error'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>InvokerDoto - Master the Arsenal Magus</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Roboto:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        
        /* Steam Statistics Styles */
.steam-profile {
    border-left: 4px solid var(--wex-color);
}

.warning-message {
    background: rgba(241, 196, 15, 0.2);
    border: 1px solid #f1c40f;
    color: #f1c40f;
}

.info-message {
    background: rgba(52, 152, 219, 0.2);
    border: 1px solid #3498db;
    color: #3498db;
}

/* Match history items */
.match-item {
    transition: all 0.3s ease;
}

.match-item:hover {
    transform: translateX(5px);
    background: rgba(156, 136, 255, 0.1) !important;
}

/* Responsive design for steam stats */
@media (max-width: 768px) {
    .steam-profile > div {
        flex-direction: column;
        text-align: center;
    }
    
    .match-item {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
        /* === ОСНОВНЫЕ СТИЛИ === */
        :root {
            --quas-color: #00a8ff;
            --wex-color: #9c88ff;
            --exort-color: #e84118;
            --dark-bg: #0c0e1c;
            --darker-bg: #070811;
            --card-bg: rgba(16, 19, 38, 0.9);
            --text-light: #e0e0e0;
            --gold: #ffd700;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', sans-serif;
            background-color: var(--dark-bg);
            color: var(--text-light);
            overflow-x: hidden;
            background-image: 
                radial-gradient(circle at 10% 20%, rgba(0, 168, 255, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 90% 40%, rgba(156, 136, 255, 0.1) 0%, transparent 20%),
                radial-gradient(circle at 40% 80%, rgba(232, 65, 24, 0.1) 0%, transparent 20%),
                linear-gradient(to bottom, var(--darker-bg) 0%, var(--dark-bg) 100%);
            min-height: 100vh;
            line-height: 1.6;
        }

        /* === АНИМИРОВАННЫЙ ФОН === */
        .particles {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
            overflow: hidden;
        }

        .particle {
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            animation: float 15s infinite linear;
        }

        @keyframes float {
            0% {
                transform: translateY(100vh) rotate(0deg);
                opacity: 0;
            }
            10% {
                opacity: 1;
            }
            90% {
                opacity: 1;
            }
            100% {
                transform: translateY(-100px) rotate(360deg);
                opacity: 0;
            }
        }

        /* === ШАПКА САЙТА === */
        header {
            background: rgba(12, 14, 28, 0.95);
            padding: 1.2rem 2rem;
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 5px 30px rgba(0, 0, 0, 0.5);
        }

        .logo {
            font-family: 'Cinzel', serif;
            font-size: 2.2rem;
            font-weight: 900;
            background: linear-gradient(90deg, var(--quas-color), var(--wex-color), var(--exort-color));
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            text-shadow: 0 0 20px rgba(156, 136, 255, 0.5);
            letter-spacing: 2px;
        }

        nav ul {
            display: flex;
            list-style: none;
            gap: 1rem;
        }

        nav a {
            color: var(--text-light);
            text-decoration: none;
            padding: 0.7rem 1.5rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-weight: 500;
            letter-spacing: 1px;
            text-transform: uppercase;
            font-size: 0.9rem;
            position: relative;
        }

        nav a::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 2px;
            background: linear-gradient(90deg, var(--quas-color), var(--wex-color), var(--exort-color));
            transform: scaleX(0);
            transform-origin: right;
            transition: transform 0.3s ease;
        }

        nav a:hover::before {
            transform: scaleX(1);
            transform-origin: left;
        }

        nav a:hover {
            color: white;
        }

        /* === ГЛАВНЫЙ ЭКРАН === */
        .hero {
            height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            padding: 2rem;
            position: relative;
            overflow: hidden;
            margin-top: 80px;
        }

        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle at center, rgba(156, 136, 255, 0.1) 0%, transparent 70%);
            z-index: -1;
        }

        .hero h1 {
            font-family: 'Cinzel', serif;
            font-size: 5rem;
            margin-bottom: 1.5rem;
            text-shadow: 
                0 0 10px rgba(0, 168, 255, 0.7),
                0 0 20px rgba(156, 136, 255, 0.5),
                0 0 30px rgba(232, 65, 24, 0.3);
            letter-spacing: 4px;
            animation: titleGlow 3s ease-in-out infinite alternate;
        }

        @keyframes titleGlow {
            0% {
                text-shadow: 
                    0 0 10px rgba(0, 168, 255, 0.7),
                    0 0 20px rgba(156, 136, 255, 0.5),
                    0 0 30px rgba(232, 65, 24, 0.3);
            }
            100% {
                text-shadow: 
                    0 0 15px rgba(0, 168, 255, 0.8),
                    0 0 25px rgba(156, 136, 255, 0.6),
                    0 0 40px rgba(232, 65, 24, 0.4);
            }
        }

        .hero p {
            font-size: 1.3rem;
            max-width: 700px;
            margin-bottom: 3rem;
            line-height: 1.6;
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        /* === АНИМИРОВАННЫЕ СФЕРЫ === */
        .orb-container {
            display: flex;
            justify-content: center;
            margin: 3rem 0;
            gap: 2rem;
        }

        .orb {
            width: 100px;
            height: 100px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: white;
            font-size: 1.1rem;
            letter-spacing: 1px;
            box-shadow: 0 0 30px;
            animation: floatOrb 6s ease-in-out infinite;
            cursor: pointer;
            transition: all 0.4s ease;
            position: relative;
            overflow: hidden;
        }

        @keyframes floatOrb {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            33% { transform: translateY(-20px) rotate(120deg); }
            66% { transform: translateY(10px) rotate(240deg); }
        }

        .orb.quas { 
            background: radial-gradient(circle at 30% 30%, var(--quas-color), #0066aa);
            box-shadow: 
                0 0 30px var(--quas-color),
                inset 0 0 20px rgba(255, 255, 255, 0.2);
            animation-delay: 0s; 
        }

        .orb.wex { 
            background: radial-gradient(circle at 30% 30%, var(--wex-color), #6c5ce7);
            box-shadow: 
                0 0 30px var(--wex-color),
                inset 0 0 20px rgba(255, 255, 255, 0.2);
            animation-delay: 0.5s; 
        }

        .orb.exort { 
            background: radial-gradient(circle at 30% 30%, var(--exort-color), #c23616);
            box-shadow: 
                0 0 30px var(--exort-color),
                inset 0 0 20px rgba(255, 255, 255, 0.2);
            animation-delay: 1s; 
        }

        .orb:hover {
            transform: scale(1.2);
            animation-play-state: paused;
        }

        /* === ОСНОВНЫЕ СЕКЦИИ === */
        .section {
            padding: 6rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .section h2 {
            text-align: center;
            font-family: 'Cinzel', serif;
            font-size: 3rem;
            margin-bottom: 4rem;
            color: var(--text-light);
            text-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
        }

        .section h2::after {
            content: '';
            display: block;
            width: 150px;
            height: 3px;
            background: linear-gradient(90deg, var(--quas-color), var(--wex-color), var(--exort-color));
            margin: 1rem auto;
            border-radius: 3px;
        }

        /* === КАРТОЧКИ ЗАКЛИНАНИЙ === */
        .abilities-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
            gap: 2.5rem;
            margin-bottom: 4rem;
        }

        .ability-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            transition: all 0.4s ease;
            border: 1px solid rgba(255, 255, 255, 0.05);
            position: relative;
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .ability-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 5px;
            background: linear-gradient(90deg, var(--quas-color), var(--wex-color), var(--exort-color));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform 0.4s ease;
        }

        .ability-card:hover::before {
            transform: scaleX(1);
        }

        .ability-card:hover {
            transform: translateY(-10px);
            box-shadow: 
                0 15px 30px rgba(0, 0, 0, 0.4),
                0 0 20px rgba(156, 136, 255, 0.2);
        }

        .ability-card h3 {
            color: var(--exort-color);
            margin-bottom: 1.5rem;
            font-size: 1.8rem;
            font-family: 'Cinzel', serif;
        }

        .spell-type {
            display: inline-block;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .type-basic {
            background: rgba(0, 168, 255, 0.2);
            color: var(--quas-color);
            border: 1px solid var(--quas-color);
        }

        .type-advanced {
            background: rgba(156, 136, 255, 0.2);
            color: var(--wex-color);
            border: 1px solid var(--wex-color);
        }

        .type-ultimate {
            background: rgba(232, 65, 24, 0.2);
            color: var(--exort-color);
            border: 1px solid var(--exort-color);
        }

        /* === СТАТИСТИКА === */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }

        .stat-card {
            background: var(--card-bg);
            padding: 1.5rem;
            border-radius: 10px;
            text-align: center;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: transform 0.3s ease;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--quas-color);
            margin-bottom: 0.5rem;
        }

        /* === СБОРКИ ПРЕДМЕТОВ === */
        .builds-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .build-card {
            background: var(--card-bg);
            border-radius: 10px;
            padding: 2rem;
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }

        .build-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }

        .build-card h4 {
            color: var(--wex-color);
            font-size: 1.5rem;
            margin-bottom: 1rem;
            font-family: 'Cinzel', serif;
        }

        .difficulty {
            display: inline-block;
            padding: 0.3rem 1rem;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .diff-easy {
            background: rgba(46, 204, 113, 0.2);
            color: #2ecc71;
            border: 1px solid #2ecc71;
        }

        .diff-medium {
            background: rgba(241, 196, 15, 0.2);
            color: #f1c40f;
            border: 1px solid #f1c40f;
        }

        .diff-hard {
            background: rgba(231, 76, 60, 0.2);
            color: #e74c3c;
            border: 1px solid #e74c3c;
        }

        /* === ФОРМА ДОБАВЛЕНИЯ ЗАКЛИНАНИЙ === */
        .database-section {
            background: var(--card-bg);
            border-radius: 15px;
            padding: 3rem;
            margin-top: 2rem;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(10px);
        }

        .form-group {
            margin-bottom: 2rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.8rem;
            font-weight: 600;
            color: rgba(224, 224, 224, 0.9);
        }

        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 1rem 1.2rem;
            border-radius: 8px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(10, 12, 25, 0.7);
            color: var(--text-light);
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-group input:focus, .form-group textarea:focus, .form-group select:focus {
            outline: none;
            border-color: var(--wex-color);
            box-shadow: 0 0 10px rgba(156, 136, 255, 0.3);
        }

        .btn {
            background: linear-gradient(90deg, var(--quas-color), var(--wex-color));
            color: white;
            border: none;
            padding: 1rem 2rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 700;
            transition: all 0.3s ease;
            letter-spacing: 1px;
            position: relative;
            overflow: hidden;
            font-size: 1rem;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, var(--wex-color), var(--exort-color));
            transition: left 0.4s ease;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        .btn:hover::before {
            left: 0;
        }

        .btn span {
            position: relative;
            z-index: 1;
        }

        /* === СООБЩЕНИЯ === */
        .message {
            padding: 1rem;
            border-radius: 8px;
            margin-bottom: 2rem;
            text-align: center;
            font-weight: bold;
        }

        .success-message {
            background: rgba(46, 204, 113, 0.2);
            border: 1px solid #2ecc71;
            color: #2ecc71;
        }

        .error-message {
            background: rgba(231, 76, 60, 0.2);
            border: 1px solid #e74c3c;
            color: #e74c3c;
        }

        /* === ПОДВАЛ === */
        footer {
            text-align: center;
            padding: 3rem 2rem;
            background: rgba(7, 8, 17, 0.9);
            color: var(--text-light);
            margin-top: 4rem;
            border-top: 1px solid rgba(255, 255, 255, 0.05);
        }

        /* === АДАПТИВНОСТЬ === */
        @media (max-width: 968px) {
            .hero h1 {
                font-size: 3.5rem;
            }
            
            .orb-container {
                flex-direction: column;
                align-items: center;
            }
            
            .orb {
                margin: 1rem 0;
            }
        }

        @media (max-width: 768px) {
            header {
                flex-direction: column;
                padding: 1rem;
            }
            
            .logo {
                margin-bottom: 1rem;
                font-size: 1.8rem;
            }
            
            nav ul {
                flex-wrap: wrap;
                justify-content: center;
            }
            
            nav a {
                padding: 0.5rem 1rem;
                margin: 0.2rem;
                font-size: 0.8rem;
            }
            
            .hero h1 {
                font-size: 2.8rem;
            }
            
            .hero p {
                font-size: 1.1rem;
            }
            
            .section {
                padding: 4rem 1.5rem;
            }
            
            .section h2 {
                font-size: 2.2rem;
            }
            
            .abilities-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <!-- Анимированные частицы на фоне -->
    <div class="particles" id="particles"></div>

    <header>
        <div class="logo">INVOKERDOTO</div>
      <nav>
    <ul>
        <li><a href="#home">Home</a></li>
        <li><a href="#spells">Spells</a></li>
        <li><a href="#builds">Builds</a></li>
        <li><a href="#stats">Stats</a></li>
        <li><a href="#database">Database</a></li>
        <li><a href="#steam-stats">Steam Stats</a></li> <!-- Добавьте эту строку -->
    </ul>
</nav>
    </header>

    <section id="home" class="hero">
        <h1>INVOKER</h1>
        <p>The Arsenal Magus, master of Quas, Wex, and Exort. Command over 10 different spells makes Invoker one of the most complex and rewarding heroes in Dota 2.</p>
        <div class="orb-container">
            <div class="orb quas">QUAS</div>
            <div class="orb wex">WEX</div>
            <div class="orb exort">EXORT</div>
        </div>
    </section>

    <section id="spells" class="section">
        <h2>Invoker's Spell Arsenal</h2>
        
        <?php if ($success): ?>
            <div class="message success-message">
                ✅ Spell added successfully!
            </div>
        <?php endif; ?>

        <?php if ($error === 'missing_fields'): ?>
            <div class="message error-message">
                ❌ Please fill all required fields.
            </div>
        <?php endif; ?>

        <h3 style="color: var(--quas-color); margin: 3rem 0 1.5rem 0; font-family: 'Cinzel', serif;">Basic Spells</h3>
        <div class="abilities-grid">
            <?php foreach ($basicSpells as $spell): ?>
            <div class="ability-card">
                <span class="spell-type type-basic">BASIC</span>
                <h3><?php echo htmlspecialchars($spell['name']); ?></h3>
                <p><strong>Combination:</strong> <?php echo htmlspecialchars($spell['combination']); ?></p>
                <p><?php echo htmlspecialchars($spell['description']); ?></p>
                <p><strong>Damage Type:</strong> <?php echo htmlspecialchars($spell['damage_type']); ?></p>
                <?php if ($spell['mana_cost'] > 0): ?>
                    <p><strong>Mana Cost:</strong> <?php echo $spell['mana_cost']; ?></p>
                <?php endif; ?>
                <?php if ($spell['cooldown'] > 0): ?>
                    <p><strong>Cooldown:</strong> <?php echo $spell['cooldown']; ?>s</p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <h3 style="color: var(--wex-color); margin: 3rem 0 1.5rem 0; font-family: 'Cinzel', serif;">Advanced Spells</h3>
        <div class="abilities-grid">
            <?php foreach ($advancedSpells as $spell): ?>
            <div class="ability-card">
                <span class="spell-type type-advanced">ADVANCED</span>
                <h3><?php echo htmlspecialchars($spell['name']); ?></h3>
                <p><strong>Combination:</strong> <?php echo htmlspecialchars($spell['combination']); ?></p>
                <p><?php echo htmlspecialchars($spell['description']); ?></p>
                <p><strong>Damage Type:</strong> <?php echo htmlspecialchars($spell['damage_type']); ?></p>
                <?php if ($spell['mana_cost'] > 0): ?>
                    <p><strong>Mana Cost:</strong> <?php echo $spell['mana_cost']; ?></p>
                <?php endif; ?>
                <?php if ($spell['cooldown'] > 0): ?>
                    <p><strong>Cooldown:</strong> <?php echo $spell['cooldown']; ?>s</p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>

        <h3 style="color: var(--exort-color); margin: 3rem 0 1.5rem 0; font-family: 'Cinzel', serif;">Ultimate Spells</h3>
        <div class="abilities-grid">
            <?php foreach ($ultimateSpells as $spell): ?>
            <div class="ability-card">
                <span class="spell-type type-ultimate">ULTIMATE</span>
                <h3><?php echo htmlspecialchars($spell['name']); ?></h3>
                <p><strong>Combination:</strong> <?php echo htmlspecialchars($spell['combination']); ?></p>
                <p><?php echo htmlspecialchars($spell['description']); ?></p>
                <p><strong>Damage Type:</strong> <?php echo htmlspecialchars($spell['damage_type']); ?></p>
                <?php if ($spell['mana_cost'] > 0): ?>
                    <p><strong>Mana Cost:</strong> <?php echo $spell['mana_cost']; ?></p>
                <?php endif; ?>
                <?php if ($spell['cooldown'] > 0): ?>
                    <p><strong>Cooldown:</strong> <?php echo $spell['cooldown']; ?>s</p>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="builds" class="section">
        <h2>Item Builds & Playstyles</h2>
        <div class="builds-grid">
            <?php foreach ($builds as $build): ?>
            <div class="build-card">
                <span class="difficulty diff-<?php echo strtolower($build['difficulty']); ?>">
                    <?php echo strtoupper($build['difficulty']); ?>
                </span>
                <h4><?php echo htmlspecialchars($build['name']); ?></h4>
                <p><strong>Playstyle:</strong> <?php echo htmlspecialchars($build['playstyle']); ?></p>
                <p><?php echo htmlspecialchars($build['description']); ?></p>
                <p><strong>Items:</strong></p>
                <?php 
                $items = json_decode($build['items'], true);
                if (is_array($items)): 
                ?>
                    <ul style="margin-left: 1.5rem; margin-top: 0.5rem;">
                        <?php foreach ($items as $item): ?>
                            <li><?php echo htmlspecialchars($item); ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="stats" class="section">
        <h2>Hero Statistics</h2>
        
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-value"><?php echo $generalStats['total_spells']; ?></div>
                <div>Total Spells</div>
            </div>
            <div class="stat-card">
                <div class="stat-value"><?php echo $generalStats['total_builds']; ?></div>
                <div>Item Builds</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">3</div>
                <div>Element Orbs</div>
            </div>
            <div class="stat-card">
                <div class="stat-value">10</div>
                <div>Unique Combinations</div>
            </div>
        </div>

        <h3 style="color: var(--wex-color); margin: 2rem 0 1rem 0; font-family: 'Cinzel', serif;">Base Attributes</h3>
        <div class="database-section">
            <div class="stats-grid">
                <?php 
                $attributeStats = $db->getStats('Attributes');
                foreach ($attributeStats as $stat): 
                ?>
                <div class="stat-card">
                    <div class="stat-value"><?php echo htmlspecialchars($stat['attribute_value']); ?></div>
                    <div><?php echo htmlspecialchars($stat['attribute_name']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <h3 style="color: var(--exort-color); margin: 2rem 0 1rem 0; font-family: 'Cinzel', serif;">Game Statistics</h3>
        <div class="database-section">
            <div class="stats-grid">
                <?php 
                $gameStats = $db->getStats('Game Stats');
                foreach ($gameStats as $stat): 
                ?>
                <div class="stat-card">
                    <div class="stat-value"><?php echo htmlspecialchars($stat['attribute_value']); ?></div>
                    <div><?php echo htmlspecialchars($stat['attribute_name']); ?></div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <section id="database" class="section">
        <h2>InvokerDoto Database</h2>
        <div class="database-section">
            <h3 style="color: var(--wex-color); margin-bottom: 2rem; font-family: 'Cinzel', serif;">Add New Spell to Database</h3>
            
            <?php if ($error === 'db_error'): ?>
                <div class="message error-message">
                    ❌ Database error. Please try again.
                </div>
            <?php endif; ?>

            <form id="spellForm" action="save_spell.php" method="POST">
                <div class="form-group">
                    <label for="spellName">Spell Name *</label>
                    <input type="text" id="spellName" name="spellName" required>
                </div>
                
                <div class="form-group">
                    <label for="combination">Orb Combination *</label>
                    <input type="text" id="combination" name="combination" placeholder="e.g. QWE" required>
                </div>
                
                <div class="form-group">
                    <label for="description">Description *</label>
                    <textarea id="description" name="description" rows="4" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="damageType">Damage Type *</label>
                    <select id="damageType" name="damageType" required>
                        <option value="">Select damage type</option>
                        <option value="Magical">Magical</option>
                        <option value="Physical">Physical</option>
                        <option value="Pure">Pure</option>
                        <option value="None">None</option>
                    </select>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                    <div class="form-group">
                        <label for="manaCost">Mana Cost</label>
                        <input type="number" id="manaCost" name="manaCost" value="0" min="0">
                    </div>
                    
                    <div class="form-group">
                        <label for="cooldown">Cooldown (seconds)</label>
                        <input type="number" id="cooldown" name="cooldown" value="0" min="0">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="spellType">Spell Type</label>
                    <select id="spellType" name="spellType">
                        <option value="Basic">Basic</option>
                        <option value="Advanced">Advanced</option>
                        <option value="Ultimate">Ultimate</option>
                    </select>
                </div>
                
                <button type="submit" class="btn">
                    <span>Invoke New Spell</span>
                </button>
            </form>
            
            <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid rgba(255, 255, 255, 0.1);">
                <h3 style="color: var(--quas-color); font-family: 'Cinzel', serif;">Database Statistics</h3>
                <div class="stats-grid" style="margin-top: 1rem;">
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $generalStats['total_spells']; ?></div>
                        <div>Total Spells</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?php echo $generalStats['total_builds']; ?></div>
                        <div>Item Builds</div>
                    </div>
                </div>
                
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-top: 2rem;">
                    <div>
                        <h4>Spell Types</h4>
                        <ul style="margin-left: 1.5rem;">
                            <?php foreach ($generalStats['spell_types'] as $type): ?>
                                <li><?php echo $type['spell_type']; ?>: <?php echo $type['count']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div>
                        <h4>Playstyles</h4>
                        <ul style="margin-left: 1.5rem;">
                            <?php foreach ($generalStats['playstyles'] as $style): ?>
                                <li><?php echo $style['playstyle']; ?>: <?php echo $style['count']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <p>InvokerDoto &copy; 2023 - The Ultimate Dota 2 Invoker Database</p>
        <p>This content is not affiliated with Valve Corporation. Dota 2 and all related trademarks are property of Valve Corporation.</p>
    </footer>

    <script>
        // Создание анимированных частиц
        function createParticles() {
            const particlesContainer = document.getElementById('particles');
            const particleCount = 40;
            
            for (let i = 0; i < particleCount; i++) {
                const particle = document.createElement('div');
                particle.classList.add('particle');
                
                const size = Math.random() * 4 + 1;
                particle.style.width = `${size}px`;
                particle.style.height = `${size}px`;
                particle.style.left = `${Math.random() * 100}%`;
                particle.style.animationDelay = `${Math.random() * 15}s`;
                
                const colors = [
                    'rgba(0, 168, 255, 0.7)',
                    'rgba(156, 136, 255, 0.7)', 
                    'rgba(232, 65, 24, 0.7)'
                ];
                particle.style.background = colors[Math.floor(Math.random() * colors.length)];
                
                particlesContainer.appendChild(particle);
            }
        }

        // Интерактивные сферы
        document.querySelectorAll('.orb').forEach(orb => {
            orb.addEventListener('click', function() {
                this.style.animation = 'none';
                void this.offsetWidth;
                this.style.animation = null;
                
                const ripple = document.createElement('div');
                ripple.style.position = 'absolute';
                ripple.style.borderRadius = '50%';
                ripple.style.backgroundColor = this.style.backgroundColor;
                ripple.style.width = '0px';
                ripple.style.height = '0px';
                ripple.style.opacity = '0.7';
                ripple.style.left = '50%';
                ripple.style.top = '50%';
                ripple.style.transform = 'translate(-50%, -50%)';
                this.appendChild(ripple);
                
                const startTime = Date.now();
                const animateRipple = () => {
                    const elapsed = Date.now() - startTime;
                    const duration = 800;
                    const progress = elapsed / duration;
                    const size = 200 * progress;
                    const opacity = 0.7 * (1 - progress);
                    
                    if (progress < 1) {
                        ripple.style.width = `${size}px`;
                        ripple.style.height = `${size}px`;
                        ripple.style.opacity = opacity;
                        requestAnimationFrame(animateRipple);
                    } else {
                        ripple.remove();
                    }
                };
                animateRipple();
            });
        });

        // Плавная прокрутка
        document.querySelectorAll('nav a').forEach(anchor => {
            anchor.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);
                window.scrollTo({
                    top: targetElement.offsetTop - 80,
                    behavior: 'smooth'
                });
            });
        });

        // Инициализация
        window.addEventListener('load', createParticles);
    </script>
    <?php
// Добавляем после других require_once
require_once 'includes/steam_api.php';

// Обработка формы Steam ID
$steam_stats = null;
$steam_profile = null;
$steam_error = null;

if (isset($_POST['steam_id'])) {
    $steam_input = trim($_POST['steam_id']);
    
    try {
        $steam_id = $steam_api->getSteamId($steam_input);
        
        if ($steam_id) {
            $steam_profile = $steam_api->getPlayerSummary($steam_id);
            $steam_stats = $steam_api->getInvokerStats($steam_id);
        } else {
            $steam_error = "Не удалось определить Steam ID. Проверьте введенные данные.";
        }
    } catch (Exception $e) {
        $steam_error = "Ошибка при получении данных: " . $e->getMessage();
    }
}
?>
<<section id="steam-stats" class="section">
    <h2>Steam Statistics</h2>
    
    <div class="database-section">
        <h3 style="color: var(--wex-color); margin-bottom: 2rem;">Check Your Dota 2 Stats</h3>
        
        <form method="POST" action="#steam-stats" style="margin-bottom: 3rem;">
            <div class="form-group">
                <label for="steam_id">Steam ID или Profile URL</label>
                <input type="text" id="steam_id" name="steam_id" 
                       placeholder="Введите Steam ID, профиль (https://steamcommunity.com/id/username) или профиль (https://steamcommunity.com/profiles/7656119...)"
                       value="<?php echo isset($_POST['steam_id']) ? htmlspecialchars($_POST['steam_id']) : ''; ?>"
                       required>
                <small style="color: rgba(224, 224, 224, 0.7); margin-top: 0.5rem; display: block;">
                    Примеры: 76561197960287930 или https://steamcommunity.com/id/username
                </small>
            </div>
            <button type="submit" class="btn">Load Statistics</button>
        </form>

        <?php if ($steam_error): ?>
            <div class="message error-message">
                ❌ <?php echo $steam_error; ?>
            </div>
        <?php endif; ?>

        <?php if ($steam_profile && $steam_stats): ?>
            <!-- Профиль игрока -->
            <div class="steam-profile" style="background: var(--card-bg); padding: 2rem; border-radius: 10px; margin-bottom: 2rem;">
                <div style="display: flex; align-items: center; gap: 1.5rem;">
                    <?php if (isset($steam_profile['avatarfull'])): ?>
                        <img src="<?php echo htmlspecialchars($steam_profile['avatarfull']); ?>" 
                             alt="Avatar" style="width: 80px; height: 80px; border-radius: 50%;">
                    <?php endif; ?>
                    <div>
                        <h3 style="color: var(--quas-color); margin-bottom: 0.5rem;">
                            <?php echo htmlspecialchars($steam_profile['personaname']); ?>
                        </h3>
                        <?php if (isset($steam_profile['realname'])): ?>
                            <p style="margin-bottom: 0.5rem;"><?php echo htmlspecialchars($steam_profile['realname']); ?></p>
                        <?php endif; ?>
                        <p style="color: rgba(224, 224, 224, 0.7);">
                            Steam ID: <?php echo htmlspecialchars($steam_profile['steamid']); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Общая статистика -->
            <div class="stats-grid" style="margin-bottom: 2rem;">
                <?php if (isset($steam_stats['general']['solo_competitive_rank'])): ?>
                <div class="stat-card">
                    <div class="stat-value"><?php echo htmlspecialchars($steam_stats['general']['solo_competitive_rank']); ?></div>
                    <div>Solo MMR</div>
                </div>
                <?php endif; ?>

                <?php if (isset($steam_stats['general']['competitive_rank'])): ?>
                <div class="stat-card">
                    <div class="stat-value"><?php echo htmlspecialchars($steam_stats['general']['competitive_rank']); ?></div>
                    <div>Party MMR</div>
                </div>
                <?php endif; ?>

                <?php if (isset($steam_stats['general']['rank_tier'])): ?>
                <div class="stat-card">
                    <div class="stat-value">
                        <?php 
                        $rank_tier = $steam_stats['general']['rank_tier'] ?? 0;
                        if ($rank_tier) {
                            $rank = floor($rank_tier / 10);
                            $tier = $rank_tier % 10;
                            echo "{$rank} - {$tier}";
                        } else {
                            echo "Unranked";
                        }
                        ?>
                    </div>
                    <div>Rank Tier</div>
                </div>
                <?php endif; ?>

                <?php if (isset($steam_stats['general']['leaderboard_rank'])): ?>
                <div class="stat-card">
                    <div class="stat-value">#<?php echo htmlspecialchars($steam_stats['general']['leaderboard_rank']); ?></div>
                    <div>Leaderboard</div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Статистика по Invoker -->
            <?php if ($steam_stats['invoker']): ?>
            <div style="background: var(--card-bg); padding: 2rem; border-radius: 10px; margin-bottom: 2rem;">
                <h3 style="color: var(--exort-color); margin-bottom: 1.5rem; text-align: center;">
                    🧙‍♂️ Invoker Statistics
                </h3>
                
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-value"><?php echo htmlspecialchars($steam_stats['invoker']['games'] ?? 0); ?></div>
                        <div>Games Played</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value"><?php echo htmlspecialchars($steam_stats['invoker']['win'] ?? 0); ?></div>
                        <div>Wins</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">
                            <?php 
                            // ИСПРАВЛЕНИЕ: Добавляем проверку на ноль
                            $games = $steam_stats['invoker']['games'] ?? 0;
                            $win = $steam_stats['invoker']['win'] ?? 0;
                            
                            if ($games > 0) {
                                $win_rate = ($win / $games) * 100;
                                echo round($win_rate, 1) . '%';
                            } else {
                                echo '0%';
                            }
                            ?>
                        </div>
                        <div>Win Rate</div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-value">
                            <?php
                            // ИСПРАВЛЕНИЕ: Добавляем проверку на существование ключа
                            $total_time = $steam_stats['invoker']['duration'] ?? 0;
                            if ($total_time > 0) {
                                $hours = floor($total_time / 3600);
                                echo $hours;
                            } else {
                                echo '0';
                            }
                            ?>
                        </div>
                        <div>Hours Played</div>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="message warning-message">
                ℹ️ No Invoker games found in recent matches.
            </div>
            <?php endif; ?>

            <!-- Последние матчи -->
            <?php if (!empty($steam_stats['recent_matches'])): ?>
            <div style="background: var(--card-bg); padding: 2rem; border-radius: 10px;">
                <h3 style="color: var(--wex-color); margin-bottom: 1.5rem;">Recent Matches</h3>
                
                <div style="display: grid; gap: 1rem;">
                    <?php foreach (array_slice($steam_stats['recent_matches'], 0, 5) as $match): ?>
                    <div style="display: flex; justify-content: between; align-items: center; padding: 1rem; background: rgba(255,255,255,0.05); border-radius: 8px;">
                        <div style="flex: 1;">
                            <div style="font-weight: bold; color: <?php echo ($match['player_slot'] ?? 0) <= 127 ? '#2ecc71' : '#e74c3c'; ?>;">
                                <?php echo ($match['player_slot'] ?? 0) <= 127 ? 'Radiant' : 'Dire'; ?>
                                - <?php echo ($match['radiant_win'] ?? false) ? 'Win' : 'Loss'; ?>
                            </div>
                            <div style="color: rgba(224, 224, 224, 0.7); font-size: 0.9rem;">
                                Match ID: <?php echo $match['match_id'] ?? 'Unknown'; ?>
                            </div>
                        </div>
                        <div style="text-align: center;">
                            <div style="font-size: 1.2rem; font-weight: bold;">
                                <?php echo $match['kills'] ?? 0; ?>/<?php echo $match['deaths'] ?? 0; ?>/<?php echo $match['assists'] ?? 0; ?>
                            </div>
                            <div style="color: rgba(224, 224, 224, 0.7); font-size: 0.8rem;">K/D/A</div>
                        </div>
                        <div style="text-align: right;">
                            <div style="font-size: 1.1rem; font-weight: bold;">
                                <?php 
                                $duration = $match['duration'] ?? 0;
                                echo floor($duration / 60) . ':' . str_pad($duration % 60, 2, '0', STR_PAD_LEFT); 
                                ?>
                            </div>
                            <div style="color: rgba(224, 224, 224, 0.7); font-size: 0.8rem;">Duration</div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        <?php elseif (isset($_POST['steam_id'])): ?>
            <div class="message info-message">
                ℹ️ Введите корректный Steam ID или URL профиля для просмотра статистики.
            </div>
        <?php endif; ?>
    </div>
</section>
</body>
</html>