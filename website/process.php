<?php
// Simulating data retrieval from a database
$data = array(
    'id' => 123,
    'topic' => 'Sample Topic',
    'teacher' => 'John Doe',
    'description' => 'Sample description',
    'department' => 'CST',
    'start_time' => '2024-03-15T12:00',
    'end_time' => '2024-03-15T14:00'
);

// Convert data to JSON format
echo json_encode($data);
?>
