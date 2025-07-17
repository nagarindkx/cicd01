<?php
/**
 * Simple PHP script to connect to a MySQL database and display data.
 *
 * This script demonstrates a basic connection and data retrieval.
 * In a production environment, database credentials should be managed
 * securely (e.g., via environment variables, secrets management).
 */

// Database connection parameters
// IMPORTANT: Replace with your actual database credentials.
// For production, consider using environment variables for security.
$servername = "localhost"; // Or the hostname/IP of your MySQL server
$username = "root";        // Your MySQL username
$password = "password";    // Your MySQL password
$dbname = "my_database";   // The database name you created

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    // In a production environment, avoid exposing detailed error messages to the user.
    // Log the error and show a generic message.
    die("Connection failed: " . $conn->connect_error);
}

echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>PHP MySQL App</title>
    <script src='https://cdn.tailwindcss.com'></script>
    <link href='https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap' rel='stylesheet'>
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<body class='bg-gray-100 flex items-center justify-center min-h-screen'>
    <div class='bg-white p-8 rounded-lg shadow-lg max-w-md w-full text-center'>
        <h1 class='text-3xl font-bold text-gray-800 mb-6'>User List from MySQL</h1>";

// Attempt to fetch data
$sql = "SELECT id, name, email FROM users";
$result = $conn->query($sql);

if ($result) {
    if ($result->num_rows > 0) {
        echo "<div class='overflow-x-auto'>";
        echo "<table class='min-w-full bg-white border border-gray-200 rounded-md'>";
        echo "<thead>
                <tr class='bg-gray-50'>
                    <th class='py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider rounded-tl-md'>ID</th>
                    <th class='py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider'>Name</th>
                    <th class='py-3 px-4 text-left text-sm font-medium text-gray-500 uppercase tracking-wider rounded-tr-md'>Email</th>
                </tr>
              </thead>";
        echo "<tbody class='divide-y divide-gray-200'>";
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "<tr class='hover:bg-gray-50'>
                    <td class='py-3 px-4 whitespace-nowrap text-sm text-gray-700'>" . htmlspecialchars($row["id"]) . "</td>
                    <td class='py-3 px-4 whitespace-nowrap text-sm text-gray-700'>" . htmlspecialchars($row["name"]) . "</td>
                    <td class='py-3 px-4 whitespace-nowrap text-sm text-gray-700'>" . htmlspecialchars($row["email"]) . "</td>
                  </tr>";
        }
        echo "</tbody>";
        echo "</table>";
        echo "</div>";
    } else {
        echo "<p class='text-gray-600'>No users found in the database.</p>";
    }
} else {
    echo "<p class='text-red-500'>Error executing query: " . htmlspecialchars($conn->error) . "</p>";
}

// Close connection
$conn->close();

echo "</div>
    </body>
</html>";
?>
