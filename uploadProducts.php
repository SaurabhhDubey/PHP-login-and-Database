<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    $fileName = $_FILES['csv_file']['name'];
    $fileType = mime_content_type($file);
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

    // Show MIME type and extension for debugging
    echo "MIME Type: $fileType<br>";
    echo "File Extension: $fileExtension<br>";

    // Accepted MIME types for CSV files
    $allowedMimeTypes = [
        'text/csv',
        'text/plain',
        'application/csv',
        'application/vnd.ms-excel',
        'application/octet-stream' // fallback for browser inconsistencies
    ];

    // Validate file
    if (!in_array($fileType, $allowedMimeTypes) || $fileExtension !== 'csv') {
        echo "Only valid CSV files are allowed.";
        exit;
    }

    if (($handle = fopen($file, "r")) !== FALSE) {
        // Connect to DB
        $conn = new mysqli("localhost", "root", "", "data");
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        fgetcsv($handle); // Skip header row

        while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
            if (count($data) < 4) continue; // Ensure enough columns

            $name = $conn->real_escape_string($data[0]);
            $price = (float)$data[1];
            $description = $conn->real_escape_string($data[2]);
            $category = $conn->real_escape_string($data[3]);

            $sql = "INSERT INTO products (name, price, description, category)
                    VALUES ('$name', $price, '$description', '$category')";
            $conn->query($sql);
        }

        fclose($handle);
        echo "Products uploaded successfully.";
        $conn->close();
    } else {
        echo "Failed to open CSV file.";
    }
} else {
    echo "No file uploaded.";
}
?>
