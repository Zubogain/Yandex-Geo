<?php
require __DIR__ . '/vendor/autoload.php';
$api = new \Yandex\Geo\Api();
?>
<!DOCTYPE html>
<html>
<head>
	<title>Сервис поиска yandex/geo</title>
    <script src="https://api-maps.yandex.ru/2.1/?lang=ru_RU" type="text/javascript">
    </script>
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
$coords = NULL; // Массив информации о городе
if (isset($_GET['do_search']) and !empty($_GET['search'])) {
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
            echo "<p>Ничего не найдено!</p>";
        } else {
            if (count($collection) > 1) {
                foreach ($collection as $item) {
                echo "<a href=\"?/api={$item->getLatitude()}/{$item->getLongitude()}/{$item->getAddress()}\">" . $item->getAddress() . '</a><br>'; // вернет адрес
                echo '<p>Широта: ' . $item->getLatitude() . '</p>'; // широта
                echo '<p>Долгота: ' . $item->getLongitude() . '</p><hr>'; // долгота
                }
            } else {
                echo "<h1>Запрос без вариантов</h1>";
                $coords['latitude'] = $collection[0]->getLatitude();
                $coords['longitude'] = $collection[0]->getLongitude();
                $coords['address'] = $collection[0]->getAddress();
            }
        }
} elseif(isset($_GET['/api']) and $pie = explode('/', $_GET['/api']) and count($pie) == 3) {
    $coords['latitude'] = $pie[0];
    $coords['longitude'] = $pie[1];
    $coords['address'] = $pie[2];
} else {
    echo "<p>Введите адрес!</p>";
}
?>
</h3>
<?php 
if ($coords != NULL) {
    $map = "<div id=\"map\" style=\"width: 1024px; height: 768px\"></div>
    <script type=\"text/javascript\">
        ymaps.ready(init);
        var myMap, 
            myPlacemark;

        function init(){ 
            myMap = new ymaps.Map(\"map\", {
                center: ['{$coords['latitude']}','{$coords['longitude']}'],
                zoom: 7
            }); 
            
            myPlacemark = new ymaps.Placemark(['{$coords['latitude']}','{$coords['longitude']}'], {
                hintContent: '{$coords["address"]}'
            });
            
            myMap.geoObjects.add(myPlacemark);
        }
    </script>";
    echo $map;
}
?>
</div>
</body>
</html>