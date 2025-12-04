<?php
// includes/steam_api.php - функции для работы со Steam API
class SteamAPI {
    private $steam_api_key = 'C262367CBD2CFAA735B2ADD1909C6F51';
    private $cache_duration = 300; // 5 минут кэширования

    public function __construct($api_key = null) {
        if ($api_key) {
            $this->steam_api_key = $api_key;
        }
        
        // Создаем папку для кэша если её нет
        if (!file_exists('cache')) {
            mkdir('cache', 0755, true);
        }
    }

    // Универсальный метод для API запросов через cURL
    private function makeApiRequest($url) {
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT => 15,
            CURLOPT_USERAGENT => 'InvokerDoto/1.0',
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_SSL_VERIFYPEER => false
        ]);
        
        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        
        if ($http_code === 200 && $response) {
            return json_decode($response, true);
        }
        
        error_log("API request failed: HTTP $http_code for URL: $url");
        return null;
    }

    // Получить Steam ID по кастомному URL или числовому ID
    public function getSteamId($steam_id_or_url) {
        // Если это уже SteamID64 (17 цифр)
        if (preg_match('/^\d{17}$/', $steam_id_or_url)) {
            return $steam_id_or_url;
        }

        // Если это кастомный URL
        if (strpos($steam_id_or_url, 'steamcommunity.com') !== false) {
            $url = $steam_id_or_url;
            if (strpos($url, '/id/') !== false) {
                // Формат: https://steamcommunity.com/id/username
                $parts = explode('/id/', $url);
                $vanity_url = end($parts);
                $vanity_url = rtrim($vanity_url, '/');
                
                $api_url = "https://api.steampowered.com/ISteamUser/ResolveVanityURL/v0001/?key={$this->steam_api_key}&vanityurl={$vanity_url}";
                $data = $this->makeApiRequest($api_url);
                
                if (isset($data['response']['steamid'])) {
                    return $data['response']['steamid'];
                }
            } elseif (strpos($url, '/profiles/') !== false) {
                // Формат: https://steamcommunity.com/profiles/7656119...
                preg_match('/\/profiles\/(\d+)/', $url, $matches);
                if (isset($matches[1])) {
                    return $matches[1];
                }
            }
        }

        return null;
    }

    // Получить базовую информацию о профиле
    public function getPlayerSummary($steam_id) {
        $cache_file = "cache/player_{$steam_id}_summary.json";
        
        if (file_exists($cache_file) && (time() - filemtime($cache_file) < $this->cache_duration)) {
            $cached_data = json_decode(file_get_contents($cache_file), true);
            if ($cached_data) return $cached_data;
        }

        $url = "https://api.steampowered.com/ISteamUser/GetPlayerSummaries/v0002/?key={$this->steam_api_key}&steamids={$steam_id}";
        $data = $this->makeApiRequest($url);

        if (isset($data['response']['players'][0])) {
            file_put_contents($cache_file, json_encode($data['response']['players'][0]));
            return $data['response']['players'][0];
        }

        return null;
    }

    // Получить статистику Dota 2 через OpenDota API
    public function getDotaStats($steam_id) {
        $cache_file = "cache/player_{$steam_id}_dota.json";
        
        if (file_exists($cache_file) && (time() - filemtime($cache_file) < $this->cache_duration)) {
            $cached_data = json_decode(file_get_contents($cache_file), true);
            if ($cached_data) return $cached_data;
        }

        // Получаем общую статистику игрока
        $player_data = $this->makeApiRequest("https://api.opendota.com/api/players/{$steam_id}");
        
        // Получаем статистику по героям
        $heroes_data = $this->makeApiRequest("https://api.opendota.com/api/players/{$steam_id}/heroes");
        
        // Получаем матчи
        $matches_data = $this->makeApiRequest("https://api.opendota.com/api/players/{$steam_id}/recentMatches");

        $result = [
            'profile' => $player_data ?? [],
            'heroes' => $heroes_data ?? [],
            'recent_matches' => $matches_data ?? []
        ];

        file_put_contents($cache_file, json_encode($result));
        return $result;
    }

    // Получить статистику по Invoker с улучшенными проверками
    public function getInvokerStats($steam_id) {
        try {
            $all_stats = $this->getDotaStats($steam_id);
            $invoker_stats = null;

            // Проверяем наличие статистики по героям
            if (isset($all_stats['heroes']) && is_array($all_stats['heroes'])) {
                foreach ($all_stats['heroes'] as $hero) {
                    if (isset($hero['hero_id']) && $hero['hero_id'] == 74) { // 74 - ID Invoker
                        $invoker_stats = $hero;
                        break;
                    }
                }
            }

            return [
                'general' => $all_stats['profile'] ?? [],
                'invoker' => $invoker_stats,
                'recent_matches' => $all_stats['recent_matches'] ?? []
            ];
        } catch (Exception $e) {
            error_log("Error getting Invoker stats: " . $e->getMessage());
            return [
                'general' => [],
                'invoker' => null,
                'recent_matches' => []
            ];
        }
    }

    // Получить WL статистику (Wins/Losses)
    public function getWinLossStats($steam_id) {
        return $this->makeApiRequest("https://api.opendota.com/api/players/{$steam_id}/wl");
    }
}

// Создаем экземпляр для использования
$steam_api = new SteamAPI();
?>