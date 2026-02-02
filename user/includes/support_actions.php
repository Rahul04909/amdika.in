<?php
require_once dirname(dirname(__DIR__)) . '/includes/session_config.php';
require_once dirname(dirname(__DIR__)) . '/database/db_config.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];
$action = $_POST['action'] ?? '';

// === Create Ticket ===
if ($action === 'create_ticket') {
    $subject = trim($_POST['subject'] ?? '');
    $message = trim($_POST['message'] ?? '');
    
    if (empty($subject) || empty($message)) {
        echo json_encode(['status' => 'error', 'message' => 'Subject and Message required']);
        exit;
    }
    
    // Generate Ticket No (TIK-YR-RAND)
    $ticket_no = 'TIK-' . date('y') . '-' . strtoupper(substr(uniqid(), -6));
    
    $conn->begin_transaction();
    try {
        // 1. Insert Ticket
        $stmt = $conn->prepare("INSERT INTO support_tickets (ticket_no, user_id, subject) VALUES (?, ?, ?)");
        $stmt->bind_param("sis", $ticket_no, $user_id, $subject);
        $stmt->execute();
        $ticket_id = $stmt->insert_id;
        
        // 2. Insert First Reply (Message)
        $sender = 'User';
        $r_stmt = $conn->prepare("INSERT INTO support_replies (ticket_id, sender_type, message) VALUES (?, ?, ?)");
        $r_stmt->bind_param("iss", $ticket_id, $sender, $message);
        $r_stmt->execute();
        
        $conn->commit();
        echo json_encode(['status' => 'success', 'ticket_id' => $ticket_id]);
    } catch (Exception $e) {
        $conn->rollback();
        echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $e->getMessage()]);
    }
    exit;
}

// === Reply Ticket ===
if ($action === 'reply_ticket') {
    $ticket_id = intval($_POST['ticket_id'] ?? 0);
    $message = trim($_POST['message'] ?? '');
    
    if ($ticket_id <= 0 || empty($message)) echo json_encode(['status' => 'error', 'message' => 'Invalid data']);
    
    // Check ownership
    $check = $conn->prepare("SELECT id FROM support_tickets WHERE id = ? AND user_id = ?");
    $check->bind_param("ii", $ticket_id, $user_id);
    $check->execute();
    if ($check->get_result()->num_rows === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Access denied']);
        exit;
    }
    
    // Insert Reply
    $sender = 'User';
    $stmt = $conn->prepare("INSERT INTO support_replies (ticket_id, sender_type, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $ticket_id, $sender, $message);
    
    if ($stmt->execute()) {
        // Update ticket timestamp
        $conn->query("UPDATE support_tickets SET updated_at = NOW() WHERE id = $ticket_id");
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database error']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid action']);
