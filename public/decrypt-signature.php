<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Decrypt Data</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }
        textarea {
            width: 100%;
            height: 100px;
        }
        button {
            padding: 10px 20px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #0056b3;
        }
        .result {
            margin-top: 20px;
            padding: 10px;
            background: #f8f9fa;
            border: 1px solid #ccc;
            white-space: pre-wrap;
        }
    </style>
</head>
<body>

    <h2>Decrypt Data</h2>

    <form method="POST">
        <label>Paste Encrypted Data:</label>
        <textarea name="encryptedData" required></textarea>

        <label>Enter Secret Key (32 characters):</label>
        <input type="text" name="secretKey" required style="width: 100%;" maxlength="32">

        <button type="submit">Decrypt</button>
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $encryptedData = $_POST["encryptedData"] ?? '';
        $secretKey = $_POST["secretKey"] ?? '';

        function decryptData($encryptedData, $secretKey)
        {
            $decoded = base64_decode($encryptedData);
            $iv = substr($decoded, 0, 16); // Extract the IV
            $cipherText = substr($decoded, 16); // Extract encrypted data
            return openssl_decrypt($cipherText, 'AES-256-CBC', $secretKey, 0, $iv);
        }

        $decryptedData = decryptData($encryptedData, $secretKey);

        // Convert to formatted JSON
        $jsonOutput = json_encode(json_decode($decryptedData, true), JSON_PRETTY_PRINT);

        echo "<div class='result'><strong>Decrypted Data (JSON Format):</strong><br><pre>" . htmlspecialchars($jsonOutput) . "</pre></div>";
    }
    ?>

</body>
</html>
