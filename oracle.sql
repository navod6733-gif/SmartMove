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
