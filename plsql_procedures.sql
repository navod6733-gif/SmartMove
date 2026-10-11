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


CREATE OR REPLACE PROCEDURE sp_book_ticket (
    p_trip_id         IN  NUMBER,
    p_passenger_id    IN  NUMBER,
    p_seat_number     IN  VARCHAR2,
    p_payment_method  IN  VARCHAR2,
    p_ticket_id       OUT NUMBER
)
IS
    v_trip_status    trips.status%TYPE;
    v_fare           trips.fare%TYPE;
    v_available      NUMBER;
    v_dummy          NUMBER;

    e_trip_not_bookable EXCEPTION;
    e_trip_full         EXCEPTION;
BEGIN
    -- Lock the trip row so two simultaneous bookings can't both
    -- pass the "seat available" check for the very last seat.
    SELECT status, fare INTO v_trip_status, v_fare
    FROM trips
    WHERE trip_id = p_trip_id
    FOR UPDATE;

    IF v_trip_status NOT IN ('SCHEDULED') THEN
        RAISE e_trip_not_bookable;
    END IF;

    v_available := fn_available_seats(p_trip_id);
    IF v_available <= 0 THEN
        RAISE e_trip_full;
    END IF;

    INSERT INTO tickets (trip_id, passenger_id, seat_number, price, status)
    VALUES (p_trip_id, p_passenger_id, p_seat_number, v_fare, 'BOOKED')
    RETURNING ticket_id INTO p_ticket_id;

    INSERT INTO payments (ticket_id, amount, payment_method, status, transaction_ref)
    VALUES (p_ticket_id, v_fare, p_payment_method, 'SUCCESS',
            'TXN' || TO_CHAR(SYSTIMESTAMP, 'YYYYMMDDHH24MISSFF3'));

    COMMIT;

EXCEPTION
    WHEN e_trip_not_bookable THEN
        ROLLBACK;
        RAISE_APPLICATION_ERROR(-20010, 'Trip ' || p_trip_id || ' is not open for booking (status: ' || v_trip_status || ').');
    WHEN e_trip_full THEN
        ROLLBACK;
        RAISE_APPLICATION_ERROR(-20011, 'Trip ' || p_trip_id || ' is fully booked.');
    WHEN DUP_VAL_ON_INDEX THEN
        ROLLBACK;
        RAISE_APPLICATION_ERROR(-20012, 'Seat ' || p_seat_number || ' on trip ' || p_trip_id || ' is already taken.');
    WHEN NO_DATA_FOUND THEN
        ROLLBACK;
        RAISE_APPLICATION_ERROR(-20013, 'Trip ' || p_trip_id || ' does not exist.');
    WHEN OTHERS THEN
        ROLLBACK;
        RAISE_APPLICATION_ERROR(-20099, 'Unexpected error while booking ticket: ' || SQLERRM);
END sp_book_ticket;
/


CREATE OR REPLACE PROCEDURE sp_cancel_ticket (
    p_ticket_id IN NUMBER
)
IS
    v_status tickets.status%TYPE;
    e_already_cancelled EXCEPTION;
BEGIN
    SELECT status INTO v_status FROM tickets WHERE ticket_id = p_ticket_id FOR UPDATE;

    IF v_status = 'CANCELLED' THEN
        RAISE e_already_cancelled;
    END IF;

    UPDATE tickets SET status = 'CANCELLED' WHERE ticket_id = p_ticket_id;
    UPDATE payments SET status = 'REFUNDED' WHERE ticket_id = p_ticket_id AND status = 'SUCCESS';

    COMMIT;
EXCEPTION
    WHEN e_already_cancelled THEN
        RAISE_APPLICATION_ERROR(-20020, 'Ticket ' || p_ticket_id || ' is already cancelled.');
    WHEN NO_DATA_FOUND THEN
        ROLLBACK;
        RAISE_APPLICATION_ERROR(-20021, 'Ticket ' || p_ticket_id || ' does not exist.');
    WHEN OTHERS THEN
        ROLLBACK;
        RAISE_APPLICATION_ERROR(-20098, 'Unexpected error while cancelling ticket: ' || SQLERRM);
END sp_cancel_ticket;
/