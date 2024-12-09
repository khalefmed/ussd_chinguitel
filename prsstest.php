<?php function processMessage($message) {
    if (strpos($message, '*') == 0 && strpos($message, '#') == strlen($message) - 1) {
        // Message starts with * and ends with #
        return substr($message, 1, -1);
    } else {
        // Return the message as is
        return $message;
    }
}

// Test cases
$message1 = "*Hello, world!#";
$message2 = "This is a regular message.";
$message3 = "*Start but no end";
$message4 = "No start or end#";

$result1 = processMessage($message1); // Returns "Hello, world!"
$result2 = processMessage($message2); // Returns "This is a regular message."
$result3 = processMessage($message3); // Returns "*Start but no end"
$result4 = processMessage($message4); // Returns "No start or end#"

echo $result1 . "\n";
echo $result2 . "\n";
echo $result3 . "\n";
echo $result4 . "\n";
?>
