<?php
// Render deployment initializer.
// Creates the existing database tables/seed rooms and the default manager
// account if they do not already exist. It does not alter existing data.

$host = getenv('DB_HOST') ?: 'localhost';
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'hotel_booking';

$managerUsername = getenv('MANAGER_USERNAME') ?: 'manager1';
$managerPassword = getenv('MANAGER_PASSWORD') ?: 'manager123';
$managerName = getenv('MANAGER_NAME') ?: 'Hotel Manager';
$managerEmail = getenv('MANAGER_EMAIL') ?: 'manager@example.com';
$managerPhone = getenv('MANAGER_PHONE') ?: '0000000000';

$maxAttempts = 60;
$attempt = 0;
$conn = null;

while ($attempt < $maxAttempts) {
    $conn = @new mysqli($host, $user, $pass, $db);
    if (!$conn->connect_error) {
        break;
    }
    $attempt++;
    sleep(2);
}

if (!$conn || $conn->connect_error) {
    fwrite(STDERR, "Database connection failed after {$maxAttempts} attempts.\n");
    exit(1);
}

$conn->set_charset('utf8mb4');

$sql = file_get_contents(__DIR__ . '/database.sql');
if ($sql === false || !$conn->multi_query($sql)) {
    fwrite(STDERR, "Database initialization failed: " . $conn->error . "\n");
    exit(1);
}

// Drain all result sets produced by multi_query before issuing another query.
do {
    if ($result = $conn->store_result()) {
        $result->free();
    }
} while ($conn->more_results() && $conn->next_result());

$check = $conn->query("SELECT id FROM users WHERE role='manager' LIMIT 1");
if (!$check) {
    fwrite(STDERR, "Manager account check failed: " . $conn->error . "\n");
    exit(1);
}

if ($check->num_rows === 0) {
    $hash = password_hash($managerPassword, PASSWORD_DEFAULT);
    $stmt = $conn->prepare(
        "INSERT INTO users (full_name, username, email, phone, password, role) VALUES (?, ?, ?, ?, ?, 'manager')"
    );
    $stmt->bind_param('sssss', $managerName, $managerUsername, $managerEmail, $managerPhone, $hash);

    if (!$stmt->execute()) {
        fwrite(STDERR, "Manager account creation failed: " . $stmt->error . "\n");
        exit(1);
    }

    echo "Manager account created: {$managerUsername}\n";
} else {
    echo "Manager account already exists; no account changes made.\n";
}

echo "Database initialization complete.\n";
$conn->close();
