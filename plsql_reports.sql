
SET SERVEROUTPUT ON;


CREATE OR REPLACE PROCEDURE rpt_most_frequent_routes (
    p_cursor OUT SYS_REFCURSOR
)
IS
BEGIN
    OPEN p_cursor FOR
        SELECT r.route_id,
               r.route_name,
               r.origin,
               r.destination,
               COUNT(tk.ticket_id)                       AS tickets_sold,
               COUNT(DISTINCT t.trip_id)                 AS trips_run,
               ROUND(COUNT(tk.ticket_id) / NULLIF(COUNT(DISTINCT t.trip_id), 0), 2) AS avg_tickets_per_trip
        FROM routes r
        JOIN trips t   ON t.route_id = r.route_id
        LEFT JOIN tickets tk ON tk.trip_id = t.trip_id AND tk.status IN ('BOOKED','COMPLETED')
        GROUP BY r.route_id, r.route_name, r.origin, r.destination
        ORDER BY tickets_sold DESC;
END rpt_most_frequent_routes;
/

CREATE OR REPLACE PROCEDURE rpt_revenue_by_period (
    p_start_date IN  DATE,
    p_end_date   IN  DATE,
    p_cursor     OUT SYS_REFCURSOR
)
IS
BEGIN
    OPEN p_cursor FOR
        SELECT TRUNC(p.payment_date)        AS payment_day,
               r.route_name,
               COUNT(p.payment_id)          AS payments_count,
               SUM(p.amount)                AS total_revenue
        FROM payments p
        JOIN tickets tk ON tk.ticket_id = p.ticket_id
        JOIN trips t    ON t.trip_id = tk.trip_id
        JOIN routes r   ON r.route_id = t.route_id
        WHERE p.status = 'SUCCESS'
          AND p.payment_date BETWEEN p_start_date AND p_end_date
        GROUP BY TRUNC(p.payment_date), r.route_name
        ORDER BY payment_day, total_revenue DESC;
END rpt_revenue_by_period;
/


CREATE OR REPLACE FUNCTION fn_total_revenue_in_period (
    p_start_date IN DATE,
    p_end_date   IN DATE
) RETURN NUMBER
IS
    v_total NUMBER;
BEGIN
    SELECT NVL(SUM(amount), 0) INTO v_total
    FROM payments
    WHERE status = 'SUCCESS'
      AND payment_date BETWEEN p_start_date AND p_end_date;
    RETURN v_total;
END fn_total_revenue_in_period;
/