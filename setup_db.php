<?php
$host = 'localhost';
$user = 'root';
$pass = '';

try {
    // Connect without database selected
    $pdo = new PDO("mysql:host=$host;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS farabi_projects CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
    echo "Database created or already exists.\n";

    // Use the database
    $pdo->exec("USE farabi_projects");

    // Create table if not exists
    $sql = "CREATE TABLE IF NOT EXISTS submissions (
        id INT AUTO_INCREMENT PRIMARY KEY,
        timestamp VARCHAR(100),
        department_name VARCHAR(255),
        college VARCHAR(255),
        coordinator_name VARCHAR(255),
        email VARCHAR(255),
        phone VARCHAR(100),
        submission_date VARCHAR(100),
        project_title VARCHAR(255),
        idea_desc TEXT,
        project_type VARCHAR(255),
        supported_activity VARCHAR(255),
        current_problem TEXT,
        ai_reason TEXT,
        previous_solution VARCHAR(255),
        core_justifications TEXT,
        target_audience VARCHAR(255),
        users_count VARCHAR(255),
        development_party VARCHAR(255),
        ai_technologies VARCHAR(255),
        estimated_budget VARCHAR(255),
        execution_duration VARCHAR(255),
        short_term_results TEXT,
        long_term_results TEXT,
        kpis TEXT,
        dept_head_approval VARCHAR(255),
        priority_level VARCHAR(50),
        additional_notes TEXT,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )";

    $pdo->exec($sql);
    echo "Table 'submissions' created successfully or already exists.\n";

} catch(PDOException $e) {
    die("Error setting up database: " . $e->getMessage());
}
?>
