<?php
require_once 'src/Database.php';
require_once 'src/Message.php';

$db = new Database();
$messageModel = new Message($db);

echo "Testing create message...\n";
$id = $messageModel->create('Test Name', 'test@example.com', 'Test Subject', 'Test Message');
echo "Created message with ID: $id\n";

echo "Testing read all...\n";
$messages = $messageModel->read();
echo "Total messages: " . count($messages) . "\n";

echo "Testing read one...\n";
$message = $messageModel->read($id);
if ($message) {
    echo "Message: " . $message['name'] . " - " . $message['subject'] . "\n";
} else {
    echo "Message not found\n";
}

echo "Testing update...\n";
$messageModel->update($id, ['status' => 'read']);
$message = $messageModel->read($id);
echo "Updated status: " . $message['status'] . "\n";

echo "Testing delete...\n";
$messageModel->delete($id);
$messages = $messageModel->read();
echo "Total messages after delete: " . count($messages) . "\n";

echo "All tests completed.\n";
?>