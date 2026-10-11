CREATE TABLE vehicles (
    vehicle_id        NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    vehicle_number     VARCHAR2(20)  NOT NULL UNIQUE,
    vehicle_type       VARCHAR2(20)  NOT NULL CHECK (vehicle_type IN ('BUS','VAN','MINIBUS','CAR')),
    capacity           NUMBER(3)     NOT NULL CHECK (capacity > 0),
    model              VARCHAR2(50),
    manufacture_year   NUMBER(4)     CHECK (manufacture_year BETWEEN 1990 AND 2100),
    status             VARCHAR2(20)  DEFAULT 'ACTIVE' NOT NULL
                        CHECK (status IN ('ACTIVE','MAINTENANCE','RETIRED')),
    registration_date  DATE          DEFAULT SYSDATE NOT NULL
);

CREATE TABLE drivers (
    driver_id       NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    full_name       VARCHAR2(100) NOT NULL,
    license_number  VARCHAR2(30)  NOT NULL UNIQUE,
    phone           VARCHAR2(20)  NOT NULL,
    email           VARCHAR2(100) UNIQUE,
    hire_date       DATE          DEFAULT SYSDATE NOT NULL,
    status          VARCHAR2(20)  DEFAULT 'ACTIVE' NOT NULL
                     CHECK (status IN ('ACTIVE','ON_LEAVE','SUSPENDED','TERMINATED'))
);

CREATE TABLE driver_vehicle_assignments (
    assignment_id    NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    driver_id        NUMBER NOT NULL REFERENCES drivers(driver_id),
    vehicle_id       NUMBER NOT NULL REFERENCES vehicles(vehicle_id),
    assigned_date    DATE   DEFAULT SYSDATE NOT NULL,
    unassigned_date  DATE,
    status           VARCHAR2(20) DEFAULT 'CURRENT' NOT NULL
                      CHECK (status IN ('CURRENT','ENDED')),
    CONSTRAINT chk_assign_dates CHECK (unassigned_date IS NULL OR unassigned_date >= assigned_date)
);


CREATE TABLE routes (
    route_id               NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    route_name             VARCHAR2(100) NOT NULL,
    origin                 VARCHAR2(100) NOT NULL,
    destination            VARCHAR2(100) NOT NULL,
    distance_km            NUMBER(6,2)   CHECK (distance_km > 0),
    estimated_duration_min NUMBER(5)     CHECK (estimated_duration_min > 0),
    status                 VARCHAR2(20)  DEFAULT 'ACTIVE' NOT NULL
                            CHECK (status IN ('ACTIVE','SUSPENDED'))
);


CREATE TABLE route_stops (
    stop_id             NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    route_id            NUMBER NOT NULL REFERENCES routes(route_id) ON DELETE CASCADE,
    stop_name           VARCHAR2(100) NOT NULL,
    stop_order          NUMBER(3) NOT NULL,
    arrival_offset_min  NUMBER(5) DEFAULT 0 NOT NULL,
    CONSTRAINT uq_route_stop_order UNIQUE (route_id, stop_order)
);

CREATE TABLE passengers (
    passenger_id       NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    full_name          VARCHAR2(100) NOT NULL,
    email              VARCHAR2(100) NOT NULL UNIQUE,
    phone              VARCHAR2(20)  NOT NULL,
    password_hash      VARCHAR2(255) NOT NULL,
    registration_date  DATE DEFAULT SYSDATE NOT NULL,
    status             VARCHAR2(20) DEFAULT 'ACTIVE' NOT NULL
                        CHECK (status IN ('ACTIVE','INACTIVE','BANNED'))
);

CREATE TABLE trips (
    trip_id         NUMBER GENERATED ALWAYS AS IDENTITY PRIMARY KEY,
    route_id        NUMBER NOT NULL REFERENCES routes(route_id),
    vehicle_id      NUMBER NOT NULL REFERENCES vehicles(vehicle_id),
    driver_id       NUMBER NOT NULL REFERENCES drivers(driver_id),
    trip_date       DATE   NOT NULL,
    departure_time  DATE   NOT NULL,
    arrival_time    DATE,
    fare            NUMBER(8,2) NOT NULL CHECK (fare >= 0),
    status          VARCHAR2(20) DEFAULT 'SCHEDULED' NOT NULL
                     CHECK (status IN ('SCHEDULED','IN_PROGRESS','COMPLETED','CANCELLED')),
    CONSTRAINT chk_trip_times CHECK (arrival_time IS NULL OR arrival_time >= departure_time)
);
