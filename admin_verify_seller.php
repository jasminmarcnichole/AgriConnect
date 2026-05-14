<?php
// Prevent any output before JSON
ob_start();

session_start();
require 'config.php';

// Clear any output that might have occurred
ob_end_clean();

// Set header for JSON response
header('Content-Type: application/json');

if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$userId = $data['user_id'] ?? 0;
$status = $data['status'] ?? '';

if (!in_array($status, ['approved', 'rejected', 'pending'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid status: ' . $status]);
    exit;
}

if (!$userId) {
    echo json_encode(['success' => false, 'message' => 'Invalid user ID']);
    exit;
}

try {
    // First check if user exists and is a seller
    $checkStmt = $pdo->prepare("SELECT id, role, verification_status FROM users WHERE id = ?");
    $checkStmt->execute([$userId]);
    $user = $checkStmt->fetch();
    
    if (!$user) {
        echo json_encode(['success' => false, 'message' => 'User not found']);
        exit;
    }
    
    if ($user['role'] !== 'seller') {
        echo json_encode(['success' => false, 'message' => 'User is not a seller']);
        exit;
    }
    
    // Update verification status
    $stmt = $pdo->prepare("UPDATE users SET verification_status = ? WHERE id = ?");
    $stmt->execute([$status, $userId]);
    
    if ($stmt->rowCount() > 0 || $user['verification_status'] === $status) {
        // Get seller info for email
        $stmt = $pdo->prepare("SELECT full_name, email FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        $seller = $stmt->fetch();
        
        if ($seller && $status === 'approved') {
            sendVerificationEmail($seller['email'], $seller['full_name']);
        }
        
        echo json_encode(['success' => true, 'message' => "Seller $status successfully!"]);
    } else {
        echo json_encode(['success' => false, 'message' => 'No changes made to database']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Error: ' . $e->getMessage()]);
}

function sendVerificationEmail($email, $name) {
    $subject = "Agri-Connect - Your Seller Account Has Been Verified!";
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .container { max-width: 600px; margin: 0 auto; padding: 20px; }
            .header { background: #6b8e23; color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
            .content { background: #f4f7f0; padding: 30px; border-radius: 0 0 8px 8px; }
            .button { display: inline-block; padding: 12px 30px; background: #6b8e23; color: white; text-decoration: none; border-radius: 5px; margin: 20px 0; }
            .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='header'>
                <h1>🎉 Congratulations!</h1>
            </div>
            <div class='content'>
                <h2>Hello " . htmlspecialchars($name) . ",</h2>
                <p>Great news! Your seller account on <strong>Agri-Connect</strong> has been successfully verified and approved by our admin team.</p>
                <p><strong>You can now:</strong></p>
                <ul>
                    <li>✅ List your agricultural products</li>
                    <li>✅ Connect with buyers nationwide</li>
                    <li>✅ Manage your seller dashboard</li>
                    <li>✅ Start growing your business</li>
                </ul>
                <center>
                    <a href='http://localhost/AgriConnect/dashboard.php' class='button' style='color: white;'>Go to Dashboard</a>
                </center>
                <p>Thank you for choosing Agri-Connect. We're excited to have you as part of our agricultural community!</p>
                <p style='margin-top: 30px; padding-top: 20px; border-top: 1px solid #ddd; color: #666;'>
                    <small>If you have any questions, feel free to contact our support team.</small>
                </p>
            </div>
            <div class='footer'>
                <p>&copy; 2024 Agri-Connect. All rights reserved.</p>
                <p><strong>Cultivating Connections, Growing Together</strong></p>
            </div>
        </div>
    </body>
    </html>
    ";
    
    // Simple email sending
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Agri-Connect <noreply@agriconnect.com>" . "\r\n";
    
    @mail($email, $subject, $message, $headers);
}
