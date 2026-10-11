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