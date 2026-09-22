<?php
header('Content-Type: application/json');

// 1. Authenticate admin
// Since this is in admin/api/live_visitors.php, includes/auth.php is relative
require_once __DIR__ . '/../includes/auth.php';

// 2. Include database configuration
require_once __DIR__ . '/../../database/db_config.php';

if (!isset($conn) || $conn->connect_error) {
    echo json_encode(['error' => 'Database connection failed']);
    exit;
}

try {
    // 3. Count unique live visitors in last 5 minutes (excluding Bots from the main count, or including them but labeling them? Let's exclude Bots from the main live user count, but we can show them if needed. Actually, let's count only non-bots for live human count, or count all. Let's count non-bots as main live count and total count separately.)
    
    // Total humans
    $count_sql = "SELECT COUNT(DISTINCT session_id) FROM visitor_logs WHERE created_at >= NOW() - INTERVAL 5 MINUTE AND device_type != 'Bot'";
    $count_res = $conn->query($count_sql);
    $live_humans = $count_res ? intval($count_res->fetch_row()[0]) : 0;

    // Total bots
    $bot_count_sql = "SELECT COUNT(DISTINCT session_id) FROM visitor_logs WHERE created_at >= NOW() - INTERVAL 5 MINUTE AND device_type = 'Bot'";
    $bot_count_res = $conn->query($bot_count_sql);
    $live_bots = $bot_count_res ? intval($bot_count_res->fetch_row()[0]) : 0;

    $total_live = $live_humans + $live_bots;

    // 4. Fetch details of latest activity for each active visitor session in the last 5 minutes
    $details_sql = "
        SELECT vl1.* 
        FROM visitor_logs vl1
        INNER JOIN (
            SELECT session_id, MAX(created_at) as max_created
            FROM visitor_logs
            WHERE created_at >= NOW() - INTERVAL 5 MINUTE
            GROUP BY session_id
        ) vl2 ON vl1.session_id = vl2.session_id AND vl1.created_at = vl2.max_created
        ORDER BY vl1.created_at DESC
        LIMIT 50
    ";
    
    $details_res = $conn->query($details_sql);
    $visitors = [];
    
    if ($details_res) {
        while ($row = $details_res->fetch_assoc()) {
            // Simplify page URL for better display
            $display_page = $row['page_url'];
            if (strlen($display_page) > 40) {
                $display_page = substr($display_page, 0, 37) . '...';
            }
            
            // Format time elapsed
            $time_diff = time() - strtotime($row['created_at']);
            if ($time_diff < 60) {
                $time_str = "Just now";
            } else {
                $mins = floor($time_diff / 60);
                $time_str = $mins . " min" . ($mins > 1 ? "s" : "") . " ago";
            }
            
            $visitors[] = [
                'ip_address' => $row['ip_address'],
                'device_type' => $row['device_type'],
                'browser' => $row['browser'],
                'os' => $row['os'],
                'page_url' => $row['page_url'],
                'display_page' => $display_page,
                'referrer' => empty($row['referrer']) ? 'Direct' : $row['referrer'],
                'last_active' => $time_str,
                'created_at' => $row['created_at']
            ];
        }
    }

    echo json_encode([
        'success' => true,
        'live_count' => $live_humans,
        'bot_count' => $live_bots,
        'total_count' => $total_live,
        'visitors' => $visitors
    ]);

} catch (Throwable $e) {
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
?>
