<?php
// ============================================================
// Oracle connection + helper functions (uses the oci8 extension)
// ============================================================
require_once __DIR__ . '/../config.php';

function db_oracle() {
    static $conn = null;
    if ($conn === null) {
        $conn = @oci_connect(ORACLE_USER, ORACLE_PASS, ORACLE_CONN_STR);
        if (!$conn) {
            $e = oci_error();
            die('Oracle connection failed: ' . htmlspecialchars($e['message'] ?? 'unknown error'));
        }
    }
    return $conn;
}

function oracle_error_message($stmt) {
    $e = oci_error($stmt);
    return $e['message'] ?? 'Unknown Oracle error';
}

// Generic SELECT -> array of associative rows
function oracle_select($sql, array $binds = []) {
    $conn = db_oracle();
    $stmt = oci_parse($conn, $sql);
    foreach ($binds as $name => $value) {
        oci_bind_by_name($stmt, $name, $binds[$name]);
    }
    if (!oci_execute($stmt)) {
        $msg = oracle_error_message($stmt);
        oci_free_statement($stmt);
        throw new Exception($msg);
    }
    $rows = [];
    while ($row = oci_fetch_assoc($stmt)) {
        $rows[] = $row;
    }
    oci_free_statement($stmt);
    return $rows;
}

// Generic INSERT/UPDATE/DELETE
function oracle_execute($sql, array $binds = []) {
    $conn = db_oracle();
    $stmt = oci_parse($conn, $sql);
    foreach ($binds as $name => $value) {
        oci_bind_by_name($stmt, $name, $binds[$name]);
    }
    if (!oci_execute($stmt)) {
        $msg = oracle_error_message($stmt);
        oci_free_statement($stmt);
        throw new Exception($msg);
    }
    oci_free_statement($stmt);
    return true;
}

// INSERT ... RETURNING id INTO :id  -> returns the new id
function oracle_insert_returning_id($sql, array $binds, $returnBindName = ':new_id') {
    $conn = db_oracle();
    $stmt = oci_parse($conn, $sql);
    foreach ($binds as $name => $value) {
        oci_bind_by_name($stmt, $name, $binds[$name]);
    }
    $newId = null;
    oci_bind_by_name($stmt, $returnBindName, $newId, 20);
    if (!oci_execute($stmt)) {
        $msg = oracle_error_message($stmt);
        oci_free_statement($stmt);
        throw new Exception($msg);
    }
    oci_free_statement($stmt);
    return $newId;
}

// Calls a report procedure of the form proc(IN..., OUT p_cursor SYS_REFCURSOR)
// $inParams: assoc array, e.g. ['p_start_date' => '2025-09-01']
// Any param whose name contains "date" is auto-wrapped in TO_DATE(...,'YYYY-MM-DD').
function call_report($procName, array $inParams = []) {
    $conn = db_oracle();
    $placeholders = [];
    foreach ($inParams as $name => $value) {
        $placeholders[] = (stripos($name, 'date') !== false)
            ? "TO_DATE(:$name,'YYYY-MM-DD')"
            : ":$name";
    }
    $placeholders[] = ':p_cursor';
    $sql = "BEGIN $procName(" . implode(', ', $placeholders) . "); END;";

    $stmt = oci_parse($conn, $sql);
    foreach ($inParams as $name => $value) {
        oci_bind_by_name($stmt, ":$name", $inParams[$name]);
    }
    $cursor = oci_new_cursor($conn);
    oci_bind_by_name($stmt, ':p_cursor', $cursor, -1, OCI_B_CURSOR);

    if (!oci_execute($stmt)) {
        $msg = oracle_error_message($stmt);
        oci_free_statement($stmt);
        throw new Exception($msg);
    }
    oci_execute($cursor);
    $rows = [];
    while ($row = oci_fetch_assoc($cursor)) {
        $rows[] = $row;
    }
    oci_free_statement($cursor);
    oci_free_statement($stmt);
    return $rows;
}

// ---- Specific business-logic procedure wrappers ----

function sp_book_ticket_call($tripId, $passengerId, $seat, $method) {
    $conn = db_oracle();
    $sql = "BEGIN sp_book_ticket(:p_trip_id, :p_passenger_id, :p_seat_number, :p_payment_method, :p_ticket_id); END;";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':p_trip_id', $tripId);
    oci_bind_by_name($stmt, ':p_passenger_id', $passengerId);
    oci_bind_by_name($stmt, ':p_seat_number', $seat);
    oci_bind_by_name($stmt, ':p_payment_method', $method);
    $ticketId = null;
    oci_bind_by_name($stmt, ':p_ticket_id', $ticketId, 20);

    if (!oci_execute($stmt)) {
        $msg = oracle_error_message($stmt);
        oci_free_statement($stmt);
        throw new Exception($msg);
    }
    oci_free_statement($stmt);
    return $ticketId;
}

function sp_cancel_ticket_call($ticketId) {
    $conn = db_oracle();
    $sql = "BEGIN sp_cancel_ticket(:p_ticket_id); END;";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':p_ticket_id', $ticketId);
    if (!oci_execute($stmt)) {
        $msg = oracle_error_message($stmt);
        oci_free_statement($stmt);
        throw new Exception($msg);
    }
    oci_free_statement($stmt);
    return true;
}

function sp_register_passenger_call($fullName, $email, $phone, $passwordHash) {
    $conn = db_oracle();
    $sql = "BEGIN sp_register_passenger(:p_full_name, :p_email, :p_phone, :p_password_hash, :p_passenger_id); END;";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':p_full_name', $fullName);
    oci_bind_by_name($stmt, ':p_email', $email);
    oci_bind_by_name($stmt, ':p_phone', $phone);
    oci_bind_by_name($stmt, ':p_password_hash', $passwordHash);
    $passengerId = null;
    oci_bind_by_name($stmt, ':p_passenger_id', $passengerId, 20);

    if (!oci_execute($stmt)) {
        $msg = oracle_error_message($stmt);
        oci_free_statement($stmt);
        throw new Exception($msg);
    }
    oci_free_statement($stmt);
    return $passengerId;
}

function sp_assign_driver_vehicle_call($driverId, $vehicleId) {
    $conn = db_oracle();
    $sql = "BEGIN sp_assign_driver_to_vehicle(:p_driver_id, :p_vehicle_id); END;";
    $stmt = oci_parse($conn, $sql);
    oci_bind_by_name($stmt, ':p_driver_id', $driverId);
    oci_bind_by_name($stmt, ':p_vehicle_id', $vehicleId);
    if (!oci_execute($stmt)) {
        $msg = oracle_error_message($stmt);
        oci_free_statement($stmt);
        throw new Exception($msg);
    }
    oci_free_statement($stmt);
    return true;
}

function fn_available_seats_call($tripId) {
    $conn = db_oracle();
    $sql = "BEGIN :result := fn_available_seats(:p_trip_id); END;";
    $stmt = oci_parse($conn, $sql);
    $result = null;
    oci_bind_by_name($stmt, ':result', $result, 20);
    oci_bind_by_name($stmt, ':p_trip_id', $tripId);
    if (!oci_execute($stmt)) {
        $msg = oracle_error_message($stmt);
        oci_free_statement($stmt);
        throw new Exception($msg);
    }
    oci_free_statement($stmt);
    return (int) $result;
}
