<?php
require_once __DIR__ . '/../includes/functions.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $ticketId = (int)($_POST['ticket_id'] ?? 0);
    try {
        // Ownership check: only cancel your own ticket
        $rows = oracle_select("SELECT passenger_id FROM tickets WHERE ticket_id = :id", [':id' => $ticketId]);
        if (empty($rows) || (int)$rows[0]['PASSENGER_ID'] !== (int)current_passenger()['passenger_id']) {
            flash('That ticket does not belong to your account.', 'error');
        } else {
            sp_cancel_ticket_call($ticketId);
            flash('Ticket #' . $ticketId . ' cancelled and refunded.');
        }
    } catch (Exception $e) {
        flash($e->getMessage(), 'error');
    }
}

header('Location: my_tickets.php');
exit;
