SET SERVEROUTPUT ON;

CREATE OR REPLACE FUNCTION fn_available_seats(p_trip_id IN NUMBER)
RETURN NUMBER
IS
    v_capacity   NUMBER;
    v_booked     NUMBER;
BEGIN
    SELECT v.capacity INTO v_capacity
    FROM trips t JOIN vehicles v ON v.vehicle_id = t.vehicle_id
    WHERE t.trip_id = p_trip_id;

    SELECT COUNT(*) INTO v_booked
    FROM tickets
    WHERE trip_id = p_trip_id AND status IN ('BOOKED','COMPLETED');

    RETURN v_capacity - v_booked;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        RAISE_APPLICATION_ERROR(-20001, 'Trip ' || p_trip_id || ' does not exist.');
END fn_available_seats;
/


CREATE OR REPLACE FUNCTION fn_trip_revenue(p_trip_id IN NUMBER)
RETURN NUMBER
IS
    v_revenue NUMBER := 0;
BEGIN
    SELECT NVL(SUM(p.amount), 0) INTO v_revenue
    FROM payments p
    JOIN tickets t ON t.ticket_id = p.ticket_id
    WHERE t.trip_id = p_trip_id AND p.status = 'SUCCESS';

    RETURN v_revenue;
END fn_trip_revenue;
/