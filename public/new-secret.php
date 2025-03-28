<?php 
function generateSecretKey() {
    return bin2hex(random_bytes(16)); // Generates a 32-character (256-bit) key
}

$secretKey = generateSecretKey();
echo "New Salt Key: " . $secretKey;

?>