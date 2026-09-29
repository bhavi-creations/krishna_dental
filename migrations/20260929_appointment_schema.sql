-- Additive update for the legacy appointment schema. Run once before deploying.
ALTER TABLE holidays
    ADD COLUMN holiday_type ENUM('fullday', 'morning', 'afternoon') NOT NULL DEFAULT 'fullday';

ALTER TABLE appointments
    ADD COLUMN name VARCHAR(100) NULL,
    ADD COLUMN email VARCHAR(254) NULL,
    ADD COLUMN time_slot VARCHAR(50) NULL,
    ADD COLUMN message TEXT NULL;

UPDATE appointments
SET name = patient_name, time_slot = appointment_time;
