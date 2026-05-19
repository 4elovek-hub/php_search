<?php

// ===========================
// API KEY от Serper.dev
// ===========================

$apiKey = "5b89e6e03229f48aa6ad21821d248459fdee2e74";

// ===========================
// Переменные
// ===========================

$items = [];
$search = "";

// ===========================
// Проверка GET запроса
// ===========================

if (isset($_GET['search']) && !empty($_GET['search'])) {

    // Получаем текст поиска
    $search = $_GET['search'];

    // Данные запроса
    $data = [
        "q" => $search
    ];

    // ===========================
    // cURL запрос
    // ===========================

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, "https://google.serper.dev/search");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "X-API-KEY: $apiKey",
        "Content-Type: application/json"
    ]);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Выполнение запроса
    $resultJson = curl_exec($ch);

    // Проверка ошибок
    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    }

    curl_close($ch);

    // ===========================
    // JSON → массив
    // ===========================

    $resultArray = json_decode($resultJson, true);

    // Получаем результаты поиска
    if (isset($resultArray['organic'])) {
        $items = $resultArray['organic'];
    }
}

?>

<!DOCTYPE html>
<html lang="ru">

<head>

    <meta charset="UTF-8">

    <title>My Browser</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background-color: #f5f5f5;
        }

        h1 {
            color: #333;
        }

        form {
            margin-bottom: 30px;
        }

        input[type="text"] {
            width: 400px;
            padding: 10px;
            font-size: 16px;
        }

        input[type="submit"] {
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }

        .result {
            background: white;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.1);
        }

        .result h2 {
            margin: 0;
        }

        .result a {
            color: blue;
            text-decoration: none;
        }

        .result a:hover {
            text-decoration: underline;
        }

        .link {
            color: green;
            font-size: 14px;
            margin-top: 5px;
            margin-bottom: 10px;
        }

    </style>

</head>

<body>

<h1>My Browser</h1>

<form method="GET" action="index.php">

    <label for="search">Search:</label><br><br>

    <input
        type="text"
        id="search"
        name="search"
        placeholder="Введите запрос..."
        value="<?php echo htmlspecialchars($search); ?>"
    >

    <input type="submit" value="Search">

</form>

<hr>

<?php

// ===========================
// Вывод результатов
// ===========================

if (!empty($items)) {

    foreach ($items as $item) {

        echo '<div class="result">';

        // Заголовок
        echo '<h2>' . htmlspecialchars($item['title']) . '</h2>';

        // Ссылка
        echo '<div class="link">';
        echo htmlspecialchars($item['link']);
        echo '</div>';

        echo '<a href="' . htmlspecialchars($item['link']) . '" target="_blank">';
        echo 'Перейти на сайт';
        echo '</a>';

        // Описание
        echo '<p>' . htmlspecialchars($item['snippet']) . '</p>';

        echo '</div>';
    }

} else {

    if (isset($_GET['search'])) {
        echo "<p>Ничего не найдено.</p>";
    }
}

?>

</body>
</html>