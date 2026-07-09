<?php

function appointment_table_columns(mysqli $conn): array
{
    static $columns = null;

    if ($columns !== null) {
        return $columns;
    }

    $columns = [];
    $result = $conn->query('SHOW COLUMNS FROM appointments');

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            $columns[strtolower($row['Field'])] = true;
        }
    }

    return $columns;
}

function appointment_has_column(mysqli $conn, string $column): bool
{
    $columns = appointment_table_columns($conn);
    return isset($columns[strtolower($column)]);
}

function appointment_uses_legacy_schema(mysqli $conn): bool
{
    return appointment_has_column($conn, 'appointment_time') && !appointment_has_column($conn, 'time_slot');
}

function appointment_slot_column(mysqli $conn): string
{
    return appointment_uses_legacy_schema($conn) ? 'appointment_time' : 'time_slot';
}

function appointment_name_column(mysqli $conn): string
{
    return appointment_uses_legacy_schema($conn) ? 'patient_name' : 'name';
}

function appointment_select_sql(mysqli $conn): string
{
    if (appointment_uses_legacy_schema($conn)) {
        return 'id, patient_name AS patient_name, phone, appointment_date, appointment_time AS appointment_time, NULL AS email, NULL AS message';
    }

    return 'id, name AS patient_name, email, phone, appointment_date, time_slot AS appointment_time, message';
}

function appointment_slot_labels(): array
{
    return [
        '10:00 AM - 11:00 AM',
        '11:00 AM - 12:00 PM',
        '12:00 PM - 01:00 PM',
        '01:00 PM - 02:00 PM',
        '02:00 PM - 03:00 PM',
        '03:00 PM - 04:00 PM',
        '04:00 PM - 05:00 PM',
        '05:00 PM - 06:00 PM',
        '06:00 PM - 07:00 PM',
        '07:00 PM - 08:00 PM',
    ];
}

function appointment_morning_slots(): array
{
    return array_slice(appointment_slot_labels(), 0, 4);
}

function appointment_afternoon_slots(): array
{
    return array_slice(appointment_slot_labels(), 4);
}

