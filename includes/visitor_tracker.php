<?php
// Silently wrap everything to prevent any errors from breaking the page
try {
    // 1. Check if session is started, if not start it
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    // 2. Exclude logged-in admins from tracking
    if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
        return;
    }

    // 3. Exclude AJAX requests
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        return;
    }

    // 4. Exclude static assets or common non-page files if somehow hit
    $request_uri = $_SERVER['REQUEST_URI'] ?? '';
    $path_info = parse_url($request_uri, PHP_URL_PATH);
    $ext = pathinfo($path_info, PATHINFO_EXTENSION);
    $excluded_exts = ['css', 'js', 'jpg', 'jpeg', 'png', 'gif', 'svg', 'ico', 'webp', 'woff', 'woff2', 'ttf', 'json', 'xml'];
    if (in_array(strtolower($ext), $excluded_exts)) {
        return;
    }

    // Helper: Detect if visitor is a search engine bot
    function tracker_is_bot($user_agent) {
        if (empty($user_agent)) return false;
        $bots = [
            'googlebot', 'bingbot', 'yandexbot', 'slurp', 'duckduckbot', 'baiduspider',
            'sogou', 'exabot', 'facebot', 'ia_archiver', 'twitterbot', 'facebookexternalhit',
            'linkedinbot', 'embedly', 'quora link preview', 'showyoubot', 'outbrain',
            'pinterest/0.', 'pinterestbot', 'slackbot', 'vkshare', 'w3c_validator',
            'redditbot', 'applebot', 'whatsapp', 'telegrambot'
        ];
        foreach ($bots as $bot) {
            if (stripos($user_agent, $bot) !== false) {
                return true;
            }
        }
        return false;
    }

    // Helper: Parse OS, Browser, Device Type from User Agent
    function tracker_parse_user_agent($user_agent) {
        $os = "Unknown OS";
        $browser = "Unknown Browser";
        $device_type = "Desktop";

        if (empty($user_agent)) {
            return [$os, $browser, $device_type];
        }

        // 1. Detect Bot
        if (tracker_is_bot($user_agent)) {
            $device_type = 'Bot';
            
            // Extract bot name
            if (stripos($user_agent, 'googlebot') !== false) $browser = 'Googlebot';
            elseif (stripos($user_agent, 'bingbot') !== false) $browser = 'Bingbot';
            elseif (stripos($user_agent, 'applebot') !== false) $browser = 'Applebot';
            elseif (stripos($user_agent, 'facebookexternalhit') !== false) $browser = 'Facebook';
            elseif (stripos($user_agent, 'twitterbot') !== false) $browser = 'Twitter';
            else $browser = 'Search Bot';
            
            $os = 'Web Crawler';
            return [$os, $browser, $device_type];
        }

        // 2. Detect OS
        $os_array = [
            '/windows nt 10/i'      =>  'Windows 10/11',
            '/windows nt 6.3/i'     =>  'Windows 8.1',
            '/windows nt 6.2/i'     =>  'Windows 8',
            '/windows nt 6.1/i'     =>  'Windows 7',
            '/windows nt 6.0/i'     =>  'Windows Vista',
            '/windows nt 5.1/i'     =>  'Windows XP',
            '/windows xp/i'         =>  'Windows XP',
            '/macintosh|mac os x/i' =>  'macOS',
            '/mac_powerpc/i'        =>  'Mac OS 9',
            '/linux/i'              =>  'Linux',
            '/ubuntu/i'             =>  'Ubuntu',
            '/iphone/i'             =>  'iOS (iPhone)',
            '/ipod/i'               =>  'iOS (iPod)',
            '/ipad/i'               =>  'iPadOS (iPad)',
            '/android/i'            =>  'Android',
            '/blackberry/i'         =>  'BlackBerry',
            '/webos/i'              =>  'Mobile'
        ];

        foreach ($os_array as $regex => $value) {
            if (preg_match($regex, $user_agent)) {
                $os = $value;
                break;
            }
        }

        // 3. Detect Browser
        if (preg_match('/edge/i', $user_agent) || preg_match('/edg/i', $user_agent)) {
            $browser = 'Edge';
        } elseif (preg_match('/chrome/i', $user_agent)) {
            $browser = 'Chrome';
        } elseif (preg_match('/safari/i', $user_agent)) {
            $browser = 'Safari';
        } elseif (preg_match('/firefox/i', $user_agent)) {
            $browser = 'Firefox';
        } elseif (preg_match('/opera|opr/i', $user_agent)) {
            $browser = 'Opera';
        } elseif (preg_match('/msie|trident/i', $user_agent)) {
            $browser = 'Internet Explorer';
        } else {
            $browser_array = [
                '/msie/i'      => 'Internet Explorer',
                '/firefox/i'   => 'Firefox',
                '/safari/i'    => 'Safari',
                '/chrome/i'    => 'Chrome',
                '/edge/i'      => 'Edge',
                '/opera/i'     => 'Opera',
                '/netscape/i'  => 'Netscape',
                '/maxthon/i'   => 'Maxthon',
                '/konqueror/i' => 'Konqueror'
            ];
            foreach ($browser_array as $regex => $value) {
                if (preg_match($regex, $user_agent)) {
                    $browser = $value;
                    break;
                }
            }
        }

        // 4. Detect Device Type
        $tablet_agents = '/(tablet|ipad|playbook|silk)|(android(?!.*mobile))/i';
        $mobile_agents = '/(mobi|ipod|phone|blackberry|opera mini|fennec|minimo|symbian|psp|opera mobi)/i';

        if (preg_match($tablet_agents, $user_agent)) {
            $device_type = 'Tablet';
        } elseif (preg_match($mobile_agents, $user_agent)) {
            $device_type = 'Mobile';
        } else {
            $device_type = 'Desktop';
        }

        return [$os, $browser, $device_type];
    }

    // Helper: Get Real IP Address
    function tracker_get_ip() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip_list = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = trim($ip_list[0]);
        } else {
            $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        }
        return filter_var($ip, FILTER_VALIDATE_IP) ? $ip : '127.0.0.1';
    }

    // 5. Gather Information
    $session_id = session_id();
    $ip_address = tracker_get_ip();
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $page_url   = $_SERVER['REQUEST_URI'] ?? '/';
    $referrer   = $_SERVER['HTTP_REFERER'] ?? '';

    // Parse User Agent
    list($os, $browser, $device_type) = tracker_parse_user_agent($user_agent);

    // Ensure database connection is active
    global $conn;
    if (isset($conn) && !$conn->connect_error) {
        $stmt = $conn->prepare("INSERT INTO visitor_logs (session_id, ip_address, user_agent, device_type, browser, os, page_url, referrer) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt) {
            $stmt->bind_param("ssssssss", $session_id, $ip_address, $user_agent, $device_type, $browser, $os, $page_url, $referrer);
            $stmt->execute();
            $stmt->close();
        }
    }
} catch (Throwable $e) {
    // Fail silently to prevent interrupting user experience
    error_log("Visitor Tracker Error: " . $e->getMessage());
}
