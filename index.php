<?php
require __DIR__ . '/vendor/autoload.php';
$api = new \Yandex\Geo\Api();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Сервис поиска yandex/geo</title>
    <link rel="stylesheet" type="text/css" href="style/background.css">
</head>
<body>
<div class="search" align="center">
<form method="GET">
	<input type="text" name="search">
	<input type="submit" name="do_search" value="Найти">
</form>
<hr>
</div>
<div class="result" align="center">
<h3>
<?php 
if (isset($_GET['do_search'])) {
    if (!empty($_GET['search'])) {
        // Поиск по адресу
        $api->setQuery($_GET['search']);
        // Настройка фильтров
        $api
            ->setLang(\Yandex\Geo\Api::LANG_US) // Выбор языка
            ->load();
        $response = $api->getResponse();
        // Список найденных точек
        $collection = $response->getList();

        if ($response->getFoundCount() == 0) {
            echo "Ничего не найдено!";
        } else {
            foreach ($collection as $item) {
                echo $item->getAddress() . '<br>'; // вернет адрес
                echo 'Широта: ' . $item->getLatitude() . '<br>'; // широта
                echo 'Долгота: ' . $item->getLongitude() . '<hr>'; // долгота
            }
        }
    } else {
        echo "Введите адрес!";
    }
} else {
    echo "Введите адрес!";
}
?>
</h3>
</div>
<script type="text/javascript" src="js/snow.js"></script>
</body>
</html>