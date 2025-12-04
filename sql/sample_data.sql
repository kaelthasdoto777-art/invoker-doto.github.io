-- sql/sample_data.sql - тестовые данные для InvokerDoto
USE InvokerDoto;

-- Добавление заклинаний Invoker
INSERT INTO spells (name, combination, description, damage_type, mana_cost, cooldown, spell_type) VALUES
('Cold Snap', 'QQQ', 'Freezes the target enemy in an icy prison, stunning and damaging them when they take damage', 'Magical', 100, 20, 'Basic'),
('Ghost Walk', 'QQW', 'Invoker turns invisible, creating a freezing field that slows nearby enemies', 'None', 200, 35, 'Basic'),
('Ice Wall', 'QQE', 'Creates a wall of solid ice that slows and damages enemies who pass through it', 'Magical', 175, 25, 'Advanced'),
('EMP', 'WWW', 'Drains mana from enemy heroes in an area after a short delay', 'Magical', 125, 30, 'Basic'),
('Tornado', 'WWQ', 'Launches a fast-moving tornado that damages and throws enemies into the air', 'Magical', 150, 30, 'Basic'),
('Alacrity', 'WWE', 'Increases attack speed and damage of a target ally for a short duration', 'None', 75, 15, 'Basic'),
('Sun Strike', 'EEE', 'Calls a destructive ray from the sun at any visible location on the battlefield', 'Pure', 175, 25, 'Basic'),
('Forge Spirit', 'EEQ', 'Creates a controllable fire entity that attacks enemies and reduces their armor', 'Physical', 75, 30, 'Advanced'),
('Chaos Meteor', 'EEW', 'Calls a massive meteor to strike the land, damaging and burning enemies in its path', 'Magical', 200, 55, 'Ultimate'),
('Deafening Blast', 'QWE', 'Unleashes a powerful sonic wave that damages, disarms, and knocks back enemies', 'Magical', 300, 40, 'Ultimate');

-- Добавление сборок предметов
INSERT INTO builds (name, description, items, playstyle, difficulty) VALUES
('Classic Quas-Wex', 'Utility and control build focusing on teamfight impact and survivability', 
 '["Hand of Midas", "Aghanim''s Scepter", "Boots of Travel", "Blink Dagger", "Shiva''s Guard", "Refresher Orb"]', 
 'Quas-Wex', 'Medium'),
('Magic Burst Exort', 'High magical damage output with Exort for nuking potential', 
 '["Aghanim''s Scepter", "Octarine Core", "Kaya and Sange", "Eul''s Scepter", "Black King Bar", "Boots of Travel"]', 
 'Quas-Exort', 'Hard'),
('Physical Carry', 'Right-click Invoker with physical damage and attack speed', 
 '["Maelstrom", "Dragon Lance", "Daedalus", "Black King Bar", "Butterfly", "Moon Shard"]', 
 'Wex-Exort', 'Medium'),
('Hybrid Control', 'Balanced build with both magical and physical damage options', 
 '["Aghanim''s Scepter", "Orchid Malevolence", "Black King Bar", "Linken''s Sphere", "Boots of Travel", "Scythe of Vyse"]', 
 'Hybrid', 'Hard');

-- Добавление статистики
INSERT INTO stats (attribute_name, attribute_value, category) VALUES
('Movement Speed', '285', 'Attributes'),
('Attack Range', '600', 'Attributes'),
('Attack Animation', '0.4 + 0.7', 'Attributes'),
('Base Attack Time', '1.7', 'Attributes'),
('Vision Range', '1800/800', 'Attributes'),
('Turn Rate', '0.5', 'Attributes'),
('Strength', '18 + 2.4', 'Base Stats'),
('Agility', '14 + 1.9', 'Base Stats'),
('Intelligence', '15 + 2.7', 'Base Stats'),
('Win Rate', '48.2%', 'Game Stats'),
('Pick Rate', '12.7%', 'Game Stats'),
('Ban Rate', '3.4%', 'Game Stats');