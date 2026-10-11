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