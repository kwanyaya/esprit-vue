<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

require_once 'config.php';

$responseStatus = '';
$responseMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the email and gender values from the form
    $email = $_POST['email'];
    $gender = $_POST['gender'];

    // Check if the email already exists in the database
    $checkSql = "SELECT * FROM form_data WHERE email = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("s", $email);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();

    if ($checkResult) {
        if ($checkResult->num_rows > 0) {
            // Email already exists, return error response
            $responseStatus = 'error';
            $responseMessage = 'Email already existed in the database.';
        } else {
            // Fetch the promote code from the database
            $promoteSql = "SELECT promote_code FROM form_data WHERE status = 0 LIMIT 1";
            $promoteResult = $conn->query($promoteSql);

            if ($promoteResult && $promoteResult->num_rows > 0) {
                // Fetch the promote code from the result set
                $row = $promoteResult->fetch_assoc();
                $promoteCode = $row['promote_code'];

                // Update the status to true
                $updateSql = "UPDATE form_data SET status = 1 WHERE promote_code = ?";
                $updateStmt = $conn->prepare($updateSql);
                $updateStmt->bind_param("s", $promoteCode);
                if ($updateStmt->execute()) {
                    // Update the email and gender based on the promote code
                    $updateSql = "UPDATE form_data SET email = ?, gender = ? WHERE promote_code = ?";
                    $updateStmt = $conn->prepare($updateSql);
                    $updateStmt->bind_param("sss", $email, $gender, $promoteCode);
                    if ($updateStmt->execute()) {
                        echo $promoteCode;
                        exit; 
                    } else {
                        $responseStatus = 'error';
                        $responseMessage = 'Error updating data: ' . $conn->error;
                    }
                } else {
                    $responseStatus = 'error';
                    $responseMessage = 'Error updating status: ' . $conn->error;
                }
            }
        }
    } else {
        $responseStatus = 'error';
        $responseMessage = 'Error executing query: ' . $conn->error;
    }
} else {
    $responseStatus = 'error';
    $responseMessage = 'Invalid request method.';
}

// Close the database connection
$conn->close();

// Return the response as a JSON string
echo json_encode(array('status' => $responseStatus, 'message' => $responseMessage));