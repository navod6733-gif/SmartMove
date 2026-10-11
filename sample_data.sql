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
