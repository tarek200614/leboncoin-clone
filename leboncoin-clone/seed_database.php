<?php
/**
 * Database Seeder - LeBonCoin Clone
 * Run this once to populate with realistic data
 * Usage: php seed_database.php
 */

require_once __DIR__ . '/config/db.php';

echo "🌱 Starting database seeding...\n\n";

try {
    // Check if data already exists
    $count = $pdo->query("SELECT COUNT(*) FROM annonces")->fetchColumn();
    if ($count > 0) {
        echo "⚠️  Database already contains {$count} advertisements.\n";
        echo "   Do you want to continue? (y/n): ";
        $handle = fopen("php://stdin", "r");
        $line = fgets($handle);
        if (trim($line) !== 'y') {
            echo "❌ Seeding cancelled.\n";
            exit;
        }
    }

    // Read and execute SQL file
    $sql = file_get_contents(__DIR__ . '/database.sql');
    
    // Execute multiple statements
    $pdo->exec($sql);
    
    echo "✅ Database seeded successfully!\n";
    echo "📊 Summary:\n";
    echo "   - 9 users created\n";
    echo "   - 9 advertisements added\n";
    echo "   - 20+ images linked\n";
    echo "   - All categories populated\n\n";
    echo " Default password for all users: 'password'\n\n";
    
} catch (PDOException $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
