INSERT INTO vehicles (vehicle_number, vehicle_type, capacity, model, manufacture_year, status, registration_date) VALUES ('SM-B-001','BUS',52,'Ashok Leyland Viking',2019,'ACTIVE', DATE '2019-03-10');
INSERT INTO vehicles (vehicle_number, vehicle_type, capacity, model, manufacture_year, status, registration_date) VALUES ('SM-B-002','BUS',52,'Ashok Leyland Viking',2020,'ACTIVE', DATE '2020-01-15');
INSERT INTO vehicles (vehicle_number, vehicle_type, capacity, model, manufacture_year, status, registration_date) VALUES ('SM-B-003','BUS',45,'Tata Starbus',2021,'ACTIVE', DATE '2021-05-20');
INSERT INTO vehicles (vehicle_number, vehicle_type, capacity, model, manufacture_year, status, registration_date) VALUES ('SM-V-001','VAN',15,'Toyota HiAce',2022,'ACTIVE', DATE '2022-02-01');
INSERT INTO vehicles (vehicle_number, vehicle_type, capacity, model, manufacture_year, status, registration_date) VALUES ('SM-V-002','VAN',15,'Toyota HiAce',2021,'ACTIVE', DATE '2021-07-11');
INSERT INTO vehicles (vehicle_number, vehicle_type, capacity, model, manufacture_year, status, registration_date) VALUES ('SM-M-001','MINIBUS',25,'Nissan Civilian',2020,'MAINTENANCE', DATE '2020-09-05');
INSERT INTO vehicles (vehicle_number, vehicle_type, capacity, model, manufacture_year, status, registration_date) VALUES ('SM-C-001','CAR',4,'Toyota Corolla',2023,'ACTIVE', DATE '2023-01-18');
INSERT INTO vehicles (vehicle_number, vehicle_type, capacity, model, manufacture_year, status, registration_date) VALUES ('SM-B-004','BUS',52,'Ashok Leyland Viking',2018,'RETIRED', DATE '2018-04-25');

INSERT INTO routes (route_name, origin, destination, distance_km, estimated_duration_min, status) VALUES ('Colombo - Kandy Express','Colombo','Kandy',115,180,'ACTIVE');
INSERT INTO routes (route_name, origin, destination, distance_km, estimated_duration_min, status) VALUES ('Colombo - Galle Coastal','Colombo','Galle',119,150,'ACTIVE');
INSERT INTO routes (route_name, origin, destination, distance_km, estimated_duration_min, status) VALUES ('Negombo - Colombo Commuter','Negombo','Colombo',37,60,'ACTIVE');
INSERT INTO routes (route_name, origin, destination, distance_km, estimated_duration_min, status) VALUES ('Colombo - Jaffna Overnight','Colombo','Jaffna',398,420,'ACTIVE');
INSERT INTO routes (route_name, origin, destination, distance_km, estimated_duration_min, status) VALUES ('Colombo - Nuwara Eliya Hill','Colombo','Nuwara Eliya',180,240,'ACTIVE');

INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express'),'Colombo Fort',1,0);
INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express'),'Kadawatha',2,30);
INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express'),'Kegalle',3,100);
INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express'),'Kandy',4,180);

INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Colombo - Galle Coastal'),'Colombo Fort',1,0);
INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Colombo - Galle Coastal'),'Kalutara',2,45);
INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Colombo - Galle Coastal'),'Galle',3,150);

INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter'),'Negombo',1,0);
INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter'),'Ja-Ela',2,20);
INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter'),'Colombo Fort',3,60);

INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Colombo - Jaffna Overnight'),'Colombo Fort',1,0);
INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Colombo - Jaffna Overnight'),'Vavuniya',2,300);
INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Colombo - Jaffna Overnight'),'Jaffna',3,420);

INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Colombo - Nuwara Eliya Hill'),'Colombo Fort',1,0);
INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Colombo - Nuwara Eliya Hill'),'Kandy',2,180);
INSERT INTO route_stops (route_id, stop_name, stop_order, arrival_offset_min) VALUES ((SELECT route_id FROM routes WHERE route_name='Colombo - Nuwara Eliya Hill'),'Nuwara Eliya',3,240);


INSERT INTO drivers (full_name, license_number, phone, email, hire_date, status) VALUES ('Nimal Perera','DL-LK-10021','0771234561','nimal.perera@smartmove.lk', DATE '2019-04-01','ACTIVE');
INSERT INTO drivers (full_name, license_number, phone, email, hire_date, status) VALUES ('Kasun Silva','DL-LK-10022','0771234562','kasun.silva@smartmove.lk', DATE '2019-06-15','ACTIVE');
INSERT INTO drivers (full_name, license_number, phone, email, hire_date, status) VALUES ('Sunil Fernando','DL-LK-10023','0771234563','sunil.fernando@smartmove.lk', DATE '2020-02-10','ACTIVE');
INSERT INTO drivers (full_name, license_number, phone, email, hire_date, status) VALUES ('Anura Jayasuriya','DL-LK-10024','0771234564','anura.j@smartmove.lk', DATE '2020-08-20','ACTIVE');
INSERT INTO drivers (full_name, license_number, phone, email, hire_date, status) VALUES ('Ruwan Gunawardena','DL-LK-10025','0771234565','ruwan.g@smartmove.lk', DATE '2021-01-05','ACTIVE');
INSERT INTO drivers (full_name, license_number, phone, email, hire_date, status) VALUES ('Chamara Bandara','DL-LK-10026','0771234566','chamara.b@smartmove.lk', DATE '2021-11-12','ON_LEAVE');
INSERT INTO drivers (full_name, license_number, phone, email, hire_date, status) VALUES ('Priyantha Kumara','DL-LK-10027','0771234567','priyantha.k@smartmove.lk', DATE '2022-03-22','ACTIVE');
INSERT INTO drivers (full_name, license_number, phone, email, hire_date, status) VALUES ('Sampath Wickrama','DL-LK-10028','0771234568','sampath.w@smartmove.lk', DATE '2022-09-09','ACTIVE');

INSERT INTO passengers (full_name, email, phone, password_hash, registration_date, status) VALUES ('Dilani Rathnayake','dilani.r@example.com','0712345601','$2y$10$examplehash0000000000000000000000000000000000000001', DATE '2023-01-10','ACTIVE');
INSERT INTO passengers (full_name, email, phone, password_hash, registration_date, status) VALUES ('Ashan Peris','ashan.peris@example.com','0712345602','$2y$10$examplehash0000000000000000000000000000000000000002', DATE '2023-01-15','ACTIVE');
INSERT INTO passengers (full_name, email, phone, password_hash, registration_date, status) VALUES ('Nadeesha Kumari','nadeesha.k@example.com','0712345603','$2y$10$examplehash0000000000000000000000000000000000000003', DATE '2023-02-01','ACTIVE');
INSERT INTO passengers (full_name, email, phone, password_hash, registration_date, status) VALUES ('Chathura Wijesinghe','chathura.w@example.com','0712345604','$2y$10$examplehash0000000000000000000000000000000000000004', DATE '2023-02-10','ACTIVE');
INSERT INTO passengers (full_name, email, phone, password_hash, registration_date, status) VALUES ('Ishara Madushani','ishara.m@example.com','0712345605','$2y$10$examplehash0000000000000000000000000000000000000005', DATE '2023-02-20','ACTIVE');
INSERT INTO passengers (full_name, email, phone, password_hash, registration_date, status) VALUES ('Lahiru Chandimal','lahiru.c@example.com','0712345606','$2y$10$examplehash0000000000000000000000000000000000000006', DATE '2023-03-01','ACTIVE');
INSERT INTO passengers (full_name, email, phone, password_hash, registration_date, status) VALUES ('Harshani De Silva','harshani.d@example.com','0712345607','$2y$10$examplehash0000000000000000000000000000000000000007', DATE '2023-03-05','ACTIVE');
INSERT INTO passengers (full_name, email, phone, password_hash, registration_date, status) VALUES ('Tharindu Abeywardena','tharindu.a@example.com','0712345608','$2y$10$examplehash0000000000000000000000000000000000000008', DATE '2023-03-18','ACTIVE');
INSERT INTO passengers (full_name, email, phone, password_hash, registration_date, status) VALUES ('Sachini Weerasinghe','sachini.w@example.com','0712345609','$2y$10$examplehash0000000000000000000000000000000000000009', DATE '2023-04-02','ACTIVE');
INSERT INTO passengers (full_name, email, phone, password_hash, registration_date, status) VALUES ('Dinesh Kariyawasam','dinesh.k@example.com','0712345610','$2y$10$examplehash0000000000000000000000000000000000000010', DATE '2023-04-14','ACTIVE');
INSERT INTO passengers (full_name, email, phone, password_hash, registration_date, status) VALUES ('Piumi Herath','piumi.h@example.com','0712345611','$2y$10$examplehash0000000000000000000000000000000000000011', DATE '2023-05-01','ACTIVE');
INSERT INTO passengers (full_name, email, phone, password_hash, registration_date, status) VALUES ('Ravindu Senanayake','ravindu.s@example.com','0712345612','$2y$10$examplehash0000000000000000000000000000000000000012', DATE '2023-05-19','INACTIVE');

INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-B-001'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10021'),
  DATE '2025-09-01', TIMESTAMP '2025-09-01 06:00:00', TIMESTAMP '2025-09-01 09:00:00', 850,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-B-001'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10021'),
  DATE '2025-09-08', TIMESTAMP '2025-09-08 06:00:00', TIMESTAMP '2025-09-08 09:00:00', 850,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-B-002'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10022'),
  DATE '2025-09-15', TIMESTAMP '2025-09-15 14:00:00', TIMESTAMP '2025-09-15 17:00:00', 850,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Colombo - Galle Coastal'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-B-003'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10023'),
  DATE '2025-09-02', TIMESTAMP '2025-09-02 07:00:00', TIMESTAMP '2025-09-02 09:30:00', 700,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Colombo - Galle Coastal'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-B-003'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10023'),
  DATE '2025-09-09', TIMESTAMP '2025-09-09 07:00:00', TIMESTAMP '2025-09-09 09:30:00', 700,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Colombo - Galle Coastal'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-B-003'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10023'),
  DATE '2025-09-16', TIMESTAMP '2025-09-16 07:00:00', TIMESTAMP '2025-09-16 09:30:00', 700,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-V-001'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10024'),
  DATE '2025-09-01', TIMESTAMP '2025-09-01 06:30:00', TIMESTAMP '2025-09-01 07:30:00', 250,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-V-001'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10024'),
  DATE '2025-09-03', TIMESTAMP '2025-09-03 06:30:00', TIMESTAMP '2025-09-03 07:30:00', 250,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-V-001'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10024'),
  DATE '2025-09-05', TIMESTAMP '2025-09-05 06:30:00', TIMESTAMP '2025-09-05 07:30:00', 250,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-V-001'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10024'),
  DATE '2025-09-08', TIMESTAMP '2025-09-08 06:30:00', TIMESTAMP '2025-09-08 07:30:00', 250,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-V-002'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10025'),
  DATE '2025-09-10', TIMESTAMP '2025-09-10 17:00:00', TIMESTAMP '2025-09-10 18:00:00', 250,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Colombo - Jaffna Overnight'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-B-002'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10022'),
  DATE '2025-09-04', TIMESTAMP '2025-09-04 21:00:00', TIMESTAMP '2025-09-05 04:00:00', 2200,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Colombo - Jaffna Overnight'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-B-002'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10022'),
  DATE '2025-09-18', TIMESTAMP '2025-09-18 21:00:00', TIMESTAMP '2025-09-19 04:00:00', 2200,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Colombo - Nuwara Eliya Hill'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-C-001'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10027'),
  DATE '2025-09-06', TIMESTAMP '2025-09-06 05:30:00', TIMESTAMP '2025-09-06 09:30:00', 1500,'COMPLETED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-B-001'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10021'),
  DATE '2025-09-25', TIMESTAMP '2025-09-25 06:00:00', NULL, 850,'SCHEDULED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-V-002'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10025'),
  DATE '2025-09-26', TIMESTAMP '2025-09-26 17:00:00', NULL, 250,'SCHEDULED');
INSERT INTO trips (route_id, vehicle_id, driver_id, trip_date, departure_time, arrival_time, fare, status) VALUES
 ((SELECT route_id FROM routes WHERE route_name='Colombo - Galle Coastal'),(SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-B-003'),(SELECT driver_id FROM drivers WHERE license_number='DL-LK-10023'),
  DATE '2025-08-20', TIMESTAMP '2025-08-20 07:00:00', NULL, 700,'CANCELLED');

INSERT INTO driver_vehicle_assignments (driver_id, vehicle_id, assigned_date, status) VALUES
  ((SELECT driver_id FROM drivers WHERE license_number='DL-LK-10021'), (SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-B-001'), DATE '2023-01-01','CURRENT');
INSERT INTO driver_vehicle_assignments (driver_id, vehicle_id, assigned_date, status) VALUES
  ((SELECT driver_id FROM drivers WHERE license_number='DL-LK-10022'), (SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-B-002'), DATE '2023-01-01','CURRENT');
INSERT INTO driver_vehicle_assignments (driver_id, vehicle_id, assigned_date, status) VALUES
  ((SELECT driver_id FROM drivers WHERE license_number='DL-LK-10023'), (SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-B-003'), DATE '2023-02-01','CURRENT');
INSERT INTO driver_vehicle_assignments (driver_id, vehicle_id, assigned_date, status) VALUES
  ((SELECT driver_id FROM drivers WHERE license_number='DL-LK-10024'), (SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-V-001'), DATE '2023-02-15','CURRENT');
INSERT INTO driver_vehicle_assignments (driver_id, vehicle_id, assigned_date, status) VALUES
  ((SELECT driver_id FROM drivers WHERE license_number='DL-LK-10025'), (SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-V-002'), DATE '2023-03-01','CURRENT');
INSERT INTO driver_vehicle_assignments (driver_id, vehicle_id, assigned_date, status) VALUES
  ((SELECT driver_id FROM drivers WHERE license_number='DL-LK-10027'), (SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-C-001'), DATE '2023-04-01','CURRENT');
INSERT INTO driver_vehicle_assignments (driver_id, vehicle_id, assigned_date, status) VALUES
  ((SELECT driver_id FROM drivers WHERE license_number='DL-LK-10028'), (SELECT vehicle_id FROM vehicles WHERE vehicle_number='SM-M-001'), DATE '2023-04-10','ENDED');

INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-01' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express')),
  (SELECT passenger_id FROM passengers WHERE email='dilani.r@example.com'),'A1',DATE '2025-08-28',850,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-01' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express')),
  (SELECT passenger_id FROM passengers WHERE email='ashan.peris@example.com'),'A2',DATE '2025-08-28',850,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-08' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express')),
  (SELECT passenger_id FROM passengers WHERE email='nadeesha.k@example.com'),'A1',DATE '2025-09-05',850,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-15' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express')),
  (SELECT passenger_id FROM passengers WHERE email='chathura.w@example.com'),'B1',DATE '2025-09-12',850,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-15' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express')),
  (SELECT passenger_id FROM passengers WHERE email='ishara.m@example.com'),'B2',DATE '2025-09-12',850,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-02' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Galle Coastal')),
  (SELECT passenger_id FROM passengers WHERE email='lahiru.c@example.com'),'A1',DATE '2025-08-30',700,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-02' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Galle Coastal')),
  (SELECT passenger_id FROM passengers WHERE email='harshani.d@example.com'),'A2',DATE '2025-08-30',700,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-09' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Galle Coastal')),
  (SELECT passenger_id FROM passengers WHERE email='tharindu.a@example.com'),'A1',DATE '2025-09-06',700,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-16' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Galle Coastal')),
  (SELECT passenger_id FROM passengers WHERE email='sachini.w@example.com'),'A1',DATE '2025-09-13',700,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-16' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Galle Coastal')),
  (SELECT passenger_id FROM passengers WHERE email='dinesh.k@example.com'),'A2',DATE '2025-09-13',700,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-01' AND route_id=(SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter')),
  (SELECT passenger_id FROM passengers WHERE email='piumi.h@example.com'),'C1',DATE '2025-08-31',250,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-03' AND route_id=(SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter')),
  (SELECT passenger_id FROM passengers WHERE email='dilani.r@example.com'),'C1',DATE '2025-09-02',250,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-05' AND route_id=(SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter')),
  (SELECT passenger_id FROM passengers WHERE email='ashan.peris@example.com'),'C1',DATE '2025-09-04',250,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-08' AND route_id=(SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter')),
  (SELECT passenger_id FROM passengers WHERE email='nadeesha.k@example.com'),'C1',DATE '2025-09-07',250,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-10' AND route_id=(SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter')),
  (SELECT passenger_id FROM passengers WHERE email='chathura.w@example.com'),'C1',DATE '2025-09-09',250,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-04' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Jaffna Overnight')),
  (SELECT passenger_id FROM passengers WHERE email='ishara.m@example.com'),'A1',DATE '2025-08-29',2200,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-18' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Jaffna Overnight')),
  (SELECT passenger_id FROM passengers WHERE email='lahiru.c@example.com'),'A1',DATE '2025-09-14',2200,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-06' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Nuwara Eliya Hill')),
  (SELECT passenger_id FROM passengers WHERE email='harshani.d@example.com'),'A1',DATE '2025-09-01',1500,'COMPLETED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-25' AND route_id=(SELECT route_id FROM routes WHERE route_name='Colombo - Kandy Express')),
  (SELECT passenger_id FROM passengers WHERE email='tharindu.a@example.com'),'A1',DATE '2025-09-20',850,'BOOKED');
INSERT INTO tickets (trip_id, passenger_id, seat_number, booking_date, price, status) VALUES
 ((SELECT trip_id FROM trips WHERE trip_date=DATE '2025-09-26' AND route_id=(SELECT route_id FROM routes WHERE route_name='Negombo - Colombo Commuter')),
  (SELECT passenger_id FROM passengers WHERE email='sachini.w@example.com'),'C1',DATE '2025-09-24',250,'BOOKED');