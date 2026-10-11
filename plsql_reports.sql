
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
