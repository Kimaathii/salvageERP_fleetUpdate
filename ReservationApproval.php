<?php
$PageSecurity = 1; // Set the page security level
include('includes/session.php');

// Fetch the logged-in user's username
$username = $_SESSION['UserID']; // Assuming the username is stored in the session

// Fetch the user's approval level from the `user_approvals` table
$sql = "SELECT approval_level FROM user_approvals WHERE username = ?";
$stmt = $db->prepare($sql);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();
$userApproval = $result->fetch_assoc();

if (!$userApproval) {
    // If the user is not found in the `user_approvals` table, deny access
    include('includes/header.php');
    prnMsg(_('You do not have access to the approval system.'), 'error');
    include('includes/footer.php');
    exit;
}

// Redirect based on the approval level
$approvalLevel = $userApproval['approval_level'];

if ($approvalLevel == 1) {
    // Redirect to ApprovalHandler.php for Level 1
    header('Location: ApprovalHandler.php');
    exit;
} elseif ($approvalLevel == 2) {
    // Redirect to StageTwoApproval.php for Level 2
    header('Location: StageTwoApproval.php');
    exit;
} else {
    
    // If the approval level is not recognized, deny access
    include('includes/header.php');
    prnMsg(_('Your approval level is not recognized. Please contact the administrator.'), 'error');
    include('includes/footer.php');
    exit;
}
?>