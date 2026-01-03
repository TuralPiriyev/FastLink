<?php
require_once __DIR__ . '/../services/ProductService.php';
require_once __DIR__ . '/../services/FilterService.php';
$config = require __DIR__ . '/../config/config.php';
$baseUrl = rtrim($config['app']['base_url'] ?? '', '/');
$productService = new ProductService();
$filterService = new FilterService();
$filterI18n = [
  'filters' => [
    'color' => ['az' => 'Rəng', 'ru' => 'Цвет', 'en' => 'Color'],
    'size' => ['az' => 'Ölçü', 'ru' => 'Размер', 'en' => 'Size'],
    'gender' => ['az' => 'Cinsiyyət', 'ru' => 'Пол', 'en' => 'Gender'],
    'material' => ['az' => 'Material', 'ru' => 'Материал', 'en' => 'Material'],
    'season' => ['az' => 'Mövsüm', 'ru' => 'Сезон', 'en' => 'Season'],
    'fit' => ['az' => 'Kəsim', 'ru' => 'Посадка', 'en' => 'Fit'],
    'pattern' => ['az' => 'Naxış', 'ru' => 'Узор', 'en' => 'Pattern'],
    'condition' => ['az' => 'Vəziyyət', 'ru' => 'Состояние', 'en' => 'Condition'],
    'brand' => ['az' => 'Brend', 'ru' => 'Бренд', 'en' => 'Brand'],
    'brand-electronics' => ['az' => 'Brend', 'ru' => 'Бренд', 'en' => 'Brand'],
    'device-type' => ['az' => 'Cihaz tipi', 'ru' => 'Тип устройства', 'en' => 'Device Type'],
    'storage' => ['az' => 'Yaddaş', 'ru' => 'Хранилище', 'en' => 'Storage'],
    'ram' => ['az' => 'RAM', 'ru' => 'RAM', 'en' => 'RAM'],
    'condition-electronics' => ['az' => 'Vəziyyət', 'ru' => 'Состояние', 'en' => 'Condition'],
    'warranty' => ['az' => 'Zəmanət', 'ru' => 'Гарантия', 'en' => 'Warranty'],
    'connectivity' => ['az' => 'Bağlantı', 'ru' => 'Связь', 'en' => 'Connectivity'],
    'sport-type' => ['az' => 'İdman növü', 'ru' => 'Вид спорта', 'en' => 'Sport Type'],
    'size-sport' => ['az' => 'Ölçü', 'ru' => 'Размер', 'en' => 'Size'],
    'gender-sport' => ['az' => 'Cinsiyyət', 'ru' => 'Пол', 'en' => 'Gender'],
    'condition-sport' => ['az' => 'Vəziyyət', 'ru' => 'Состояние', 'en' => 'Condition'],
    'brand-sport' => ['az' => 'Brend', 'ru' => 'Бренд', 'en' => 'Brand'],
    'size-eu' => ['az' => 'Ölçü (EU)', 'ru' => 'Размер (EU)', 'en' => 'Size (EU)'],
    'color-shoes' => ['az' => 'Rəng', 'ru' => 'Цвет', 'en' => 'Color'],
    'material-shoes' => ['az' => 'Material', 'ru' => 'Материал', 'en' => 'Material'],
    'condition-shoes' => ['az' => 'Vəziyyət', 'ru' => 'Состояние', 'en' => 'Condition'],
    'gender-shoes' => ['az' => 'Cinsiyyət', 'ru' => 'Пол', 'en' => 'Gender'],
    'product-type-beauty' => ['az' => 'Məhsul tipi', 'ru' => 'Тип продукта', 'en' => 'Product Type'],
    'skin-type' => ['az' => 'Dəri tipi', 'ru' => 'Тип кожи', 'en' => 'Skin Type'],
    'volume' => ['az' => 'Həcm', 'ru' => 'Объем', 'en' => 'Volume'],
    'condition-beauty' => ['az' => 'Vəziyyət', 'ru' => 'Состояние', 'en' => 'Condition'],
    'brand-beauty' => ['az' => 'Brend', 'ru' => 'Бренд', 'en' => 'Brand'],
    'category-home' => ['az' => 'Kateqoriya', 'ru' => 'Категория', 'en' => 'Category'],
    'material-home' => ['az' => 'Material', 'ru' => 'Материал', 'en' => 'Material'],
    'condition-home' => ['az' => 'Vəziyyət', 'ru' => 'Состояние', 'en' => 'Condition'],
    'color-home' => ['az' => 'Rəng', 'ru' => 'Цвет', 'en' => 'Color'],
    'age-range' => ['az' => 'Yaş aralığı', 'ru' => 'Возраст', 'en' => 'Age Range'],
    'gender-kids' => ['az' => 'Cinsiyyət', 'ru' => 'Пол', 'en' => 'Gender'],
    'condition-kids' => ['az' => 'Vəziyyət', 'ru' => 'Состояние', 'en' => 'Condition'],
    'size-kids' => ['az' => 'Ölçü', 'ru' => 'Размер', 'en' => 'Size'],
    'part-type' => ['az' => 'Hissə tipi', 'ru' => 'Тип детали', 'en' => 'Part Type'],
    'condition-auto' => ['az' => 'Vəziyyət', 'ru' => 'Состояние', 'en' => 'Condition'],
    'brand-auto' => ['az' => 'Brend', 'ru' => 'Бренд', 'en' => 'Brand'],
    'compatibility' => ['az' => 'Uyğunluq', 'ru' => 'Совместимость', 'en' => 'Compatibility'],
    'language-books' => ['az' => 'Dil', 'ru' => 'Язык', 'en' => 'Language'],
    'type-books' => ['az' => 'Tip', 'ru' => 'Тип', 'en' => 'Type'],
    'condition-books' => ['az' => 'Vəziyyət', 'ru' => 'Состояние', 'en' => 'Condition'],
    'type-food' => ['az' => 'Tip', 'ru' => 'Тип', 'en' => 'Type'],
    'form-food' => ['az' => 'Form', 'ru' => 'Форма', 'en' => 'Form'],
    'weight-food' => ['az' => 'Çəki', 'ru' => 'Вес', 'en' => 'Weight'],
    'condition-food' => ['az' => 'Vəziyyət', 'ru' => 'Состояние', 'en' => 'Condition'],
    'flavor-food' => ['az' => 'Dad', 'ru' => 'Вкус', 'en' => 'Flavor'],
  ],
  'options' => [
    'color' => [
      'black' => ['az' => 'Qara', 'ru' => 'Черный', 'en' => 'Black'],
      'white' => ['az' => 'Ağ', 'ru' => 'Белый', 'en' => 'White'],
      'gray' => ['az' => 'Boz', 'ru' => 'Серый', 'en' => 'Gray'],
      'red' => ['az' => 'Qırmızı', 'ru' => 'Красный', 'en' => 'Red'],
      'blue' => ['az' => 'Mavi', 'ru' => 'Синий', 'en' => 'Blue'],
      'green' => ['az' => 'Yaşıl', 'ru' => 'Зеленый', 'en' => 'Green'],
      'yellow' => ['az' => 'Sarı', 'ru' => 'Желтый', 'en' => 'Yellow'],
      'pink' => ['az' => 'Çəhrayı', 'ru' => 'Розовый', 'en' => 'Pink'],
      'purple' => ['az' => 'Bənövşəyi', 'ru' => 'Фиолетовый', 'en' => 'Purple'],
      'brown' => ['az' => 'Qəhvəyi', 'ru' => 'Коричневый', 'en' => 'Brown'],
      'beige' => ['az' => 'Bej', 'ru' => 'Бежевый', 'en' => 'Beige'],
      'orange' => ['az' => 'Narıncı', 'ru' => 'Оранжевый', 'en' => 'Orange'],
    ],
    'size' => [
      'xs' => ['az' => 'XS', 'ru' => 'XS', 'en' => 'XS'],
      's' => ['az' => 'S', 'ru' => 'S', 'en' => 'S'],
      'm' => ['az' => 'M', 'ru' => 'M', 'en' => 'M'],
      'l' => ['az' => 'L', 'ru' => 'L', 'en' => 'L'],
      'xl' => ['az' => 'XL', 'ru' => 'XL', 'en' => 'XL'],
      'xxl' => ['az' => 'XXL', 'ru' => 'XXL', 'en' => 'XXL'],
      '3xl' => ['az' => '3XL', 'ru' => '3XL', 'en' => '3XL'],
    ],
    'gender' => [
      'men' => ['az' => 'Kişi', 'ru' => 'Мужской', 'en' => 'Men'],
      'women' => ['az' => 'Qadın', 'ru' => 'Женский', 'en' => 'Women'],
      'unisex' => ['az' => 'Uniseks', 'ru' => 'Унисекс', 'en' => 'Unisex'],
      'kids' => ['az' => 'Uşaqlar', 'ru' => 'Дети', 'en' => 'Kids'],
    ],
    'material' => [
      'cotton' => ['az' => 'Pambıq', 'ru' => 'Хлопок', 'en' => 'Cotton'],
      'polyester' => ['az' => 'Poliester', 'ru' => 'Полиэстер', 'en' => 'Polyester'],
      'wool' => ['az' => 'Yun', 'ru' => 'Шерсть', 'en' => 'Wool'],
      'linen' => ['az' => 'Kətan', 'ru' => 'Лён', 'en' => 'Linen'],
      'leather' => ['az' => 'Dəri', 'ru' => 'Кожа', 'en' => 'Leather'],
      'denim' => ['az' => 'Cins', 'ru' => 'Деним', 'en' => 'Denim'],
      'silk' => ['az' => 'İpək', 'ru' => 'Шёлк', 'en' => 'Silk'],
    ],
    'season' => [
      'summer' => ['az' => 'Yay', 'ru' => 'Лето', 'en' => 'Summer'],
      'winter' => ['az' => 'Qış', 'ru' => 'Зима', 'en' => 'Winter'],
      'spring' => ['az' => 'Yaz', 'ru' => 'Весна', 'en' => 'Spring'],
      'autumn' => ['az' => 'Payız', 'ru' => 'Осень', 'en' => 'Autumn'],
      'all-season' => ['az' => 'İlboyu', 'ru' => 'Всесезон', 'en' => 'All-season'],
    ],
    'fit' => [
      'slim' => ['az' => 'Dartan', 'ru' => 'Приталенный', 'en' => 'Slim'],
      'regular' => ['az' => 'Standart', 'ru' => 'Стандарт', 'en' => 'Regular'],
      'oversize' => ['az' => 'Oversize', 'ru' => 'Оверсайз', 'en' => 'Oversize'],
    ],
    'pattern' => [
      'solid' => ['az' => 'Tək rəng', 'ru' => 'Однотонный', 'en' => 'Solid'],
      'striped' => ['az' => 'Zolaqlı', 'ru' => 'В полоску', 'en' => 'Striped'],
      'checked' => ['az' => 'Damalı', 'ru' => 'В клетку', 'en' => 'Checked'],
      'printed' => ['az' => 'Printli', 'ru' => 'С принтом', 'en' => 'Printed'],
    ],
    'condition' => [
      'new' => ['az' => 'Yeni', 'ru' => 'Новый', 'en' => 'New'],
      'like-new' => ['az' => 'Təzə kimidir', 'ru' => 'Как новый', 'en' => 'Like New'],
      'used' => ['az' => 'İşlənmiş', 'ru' => 'Б/у', 'en' => 'Used'],
    ],
    'brand' => [
      'any' => ['az' => 'Fərqi yoxdur', 'ru' => 'Любой', 'en' => 'Any'],
    ],
    'brand-electronics' => [
      'apple' => ['az' => 'Apple', 'ru' => 'Apple', 'en' => 'Apple'],
      'samsung' => ['az' => 'Samsung', 'ru' => 'Samsung', 'en' => 'Samsung'],
      'xiaomi' => ['az' => 'Xiaomi', 'ru' => 'Xiaomi', 'en' => 'Xiaomi'],
      'huawei' => ['az' => 'Huawei', 'ru' => 'Huawei', 'en' => 'Huawei'],
      'lenovo' => ['az' => 'Lenovo', 'ru' => 'Lenovo', 'en' => 'Lenovo'],
      'hp' => ['az' => 'HP', 'ru' => 'HP', 'en' => 'HP'],
      'dell' => ['az' => 'Dell', 'ru' => 'Dell', 'en' => 'Dell'],
      'asus' => ['az' => 'Asus', 'ru' => 'Asus', 'en' => 'Asus'],
      'acer' => ['az' => 'Acer', 'ru' => 'Acer', 'en' => 'Acer'],
      'sony' => ['az' => 'Sony', 'ru' => 'Sony', 'en' => 'Sony'],
      'msi' => ['az' => 'MSI', 'ru' => 'MSI', 'en' => 'MSI'],
      'lg' => ['az' => 'LG', 'ru' => 'LG', 'en' => 'LG'],
    ],
    'device-type' => [
      'phone' => ['az' => 'Telefon', 'ru' => 'Телефон', 'en' => 'Phone'],
      'laptop' => ['az' => 'Noutbuk', 'ru' => 'Ноутбук', 'en' => 'Laptop'],
      'desktop' => ['az' => 'Masaüstü', 'ru' => 'Десктоп', 'en' => 'Desktop'],
      'tablet' => ['az' => 'Planşet', 'ru' => 'Планшет', 'en' => 'Tablet'],
      'monitor' => ['az' => 'Monitor', 'ru' => 'Монитор', 'en' => 'Monitor'],
      'tv' => ['az' => 'TV', 'ru' => 'TV', 'en' => 'TV'],
      'smartwatch' => ['az' => 'Ağıllı saat', 'ru' => 'Смарт-часы', 'en' => 'Smartwatch'],
      'headphones' => ['az' => 'Qulaqlıq', 'ru' => 'Наушники', 'en' => 'Headphones'],
      'printer' => ['az' => 'Printer', 'ru' => 'Принтер', 'en' => 'Printer'],
      'router' => ['az' => 'Router', 'ru' => 'Роутер', 'en' => 'Router'],
    ],
    'storage' => [
      '32gb' => ['az' => '32GB', 'ru' => '32GB', 'en' => '32GB'],
      '64gb' => ['az' => '64GB', 'ru' => '64GB', 'en' => '64GB'],
      '128gb' => ['az' => '128GB', 'ru' => '128GB', 'en' => '128GB'],
      '256gb' => ['az' => '256GB', 'ru' => '256GB', 'en' => '256GB'],
      '512gb' => ['az' => '512GB', 'ru' => '512GB', 'en' => '512GB'],
      '1tb' => ['az' => '1TB', 'ru' => '1TB', 'en' => '1TB'],
      '2tb' => ['az' => '2TB', 'ru' => '2TB', 'en' => '2TB'],
    ],
    'ram' => [
      '2gb' => ['az' => '2GB', 'ru' => '2GB', 'en' => '2GB'],
      '4gb' => ['az' => '4GB', 'ru' => '4GB', 'en' => '4GB'],
      '6gb' => ['az' => '6GB', 'ru' => '6GB', 'en' => '6GB'],
      '8gb' => ['az' => '8GB', 'ru' => '8GB', 'en' => '8GB'],
      '12gb' => ['az' => '12GB', 'ru' => '12GB', 'en' => '12GB'],
      '16gb' => ['az' => '16GB', 'ru' => '16GB', 'en' => '16GB'],
      '32gb' => ['az' => '32GB', 'ru' => '32GB', 'en' => '32GB'],
      '64gb' => ['az' => '64GB', 'ru' => '64GB', 'en' => '64GB'],
    ],
    'condition-electronics' => [
      'new' => ['az' => 'Yeni', 'ru' => 'Новый', 'en' => 'New'],
      'like-new' => ['az' => 'Təzə kimidir', 'ru' => 'Как новый', 'en' => 'Like New'],
      'used' => ['az' => 'İşlənmiş', 'ru' => 'Б/у', 'en' => 'Used'],
      'for-parts' => ['az' => 'Hissələr üçün', 'ru' => 'На запчасти', 'en' => 'For parts'],
    ],
    'warranty' => [
      'yes' => ['az' => 'Bəli', 'ru' => 'Да', 'en' => 'Yes'],
      'no' => ['az' => 'Xeyr', 'ru' => 'Нет', 'en' => 'No'],
    ],
    'connectivity' => [
      'wi-fi' => ['az' => 'Wi-Fi', 'ru' => 'Wi-Fi', 'en' => 'Wi-Fi'],
      'bluetooth' => ['az' => 'Bluetooth', 'ru' => 'Bluetooth', 'en' => 'Bluetooth'],
      'nfc' => ['az' => 'NFC', 'ru' => 'NFC', 'en' => 'NFC'],
      '4g' => ['az' => '4G', 'ru' => '4G', 'en' => '4G'],
      '5g' => ['az' => '5G', 'ru' => '5G', 'en' => '5G'],
    ],
    'sport-type' => [
      'fitness' => ['az' => 'Fitnes', 'ru' => 'Фитнес', 'en' => 'Fitness'],
      'football' => ['az' => 'Futbol', 'ru' => 'Футбол', 'en' => 'Football'],
      'basketball' => ['az' => 'Basketbol', 'ru' => 'Баскетбол', 'en' => 'Basketball'],
      'running' => ['az' => 'Qaçış', 'ru' => 'Бег', 'en' => 'Running'],
      'cycling' => ['az' => 'Velosiped', 'ru' => 'Велоспорт', 'en' => 'Cycling'],
      'swimming' => ['az' => 'Üzgüçülük', 'ru' => 'Плавание', 'en' => 'Swimming'],
      'boxing' => ['az' => 'Boks', 'ru' => 'Бокс', 'en' => 'Boxing'],
      'yoga' => ['az' => 'Yoqa', 'ru' => 'Йога', 'en' => 'Yoga'],
      'tennis' => ['az' => 'Tennis', 'ru' => 'Теннис', 'en' => 'Tennis'],
    ],
    'size-sport' => [
      'xs' => ['az' => 'XS', 'ru' => 'XS', 'en' => 'XS'],
      's' => ['az' => 'S', 'ru' => 'S', 'en' => 'S'],
      'm' => ['az' => 'M', 'ru' => 'M', 'en' => 'M'],
      'l' => ['az' => 'L', 'ru' => 'L', 'en' => 'L'],
      'xl' => ['az' => 'XL', 'ru' => 'XL', 'en' => 'XL'],
    ],
    'gender-sport' => [
      'men' => ['az' => 'Kişi', 'ru' => 'Мужской', 'en' => 'Men'],
      'women' => ['az' => 'Qadın', 'ru' => 'Женский', 'en' => 'Women'],
      'unisex' => ['az' => 'Uniseks', 'ru' => 'Унисекс', 'en' => 'Unisex'],
      'kids' => ['az' => 'Uşaqlar', 'ru' => 'Дети', 'en' => 'Kids'],
    ],
    'condition-sport' => [
      'new' => ['az' => 'Yeni', 'ru' => 'Новый', 'en' => 'New'],
      'used' => ['az' => 'İşlənmiş', 'ru' => 'Б/у', 'en' => 'Used'],
    ],
    'brand-sport' => [
      'any' => ['az' => 'Fərqi yoxdur', 'ru' => 'Любой', 'en' => 'Any'],
    ],
    'size-eu' => [
      '35' => ['az' => '35', 'ru' => '35', 'en' => '35'],
      '36' => ['az' => '36', 'ru' => '36', 'en' => '36'],
      '37' => ['az' => '37', 'ru' => '37', 'en' => '37'],
      '38' => ['az' => '38', 'ru' => '38', 'en' => '38'],
      '39' => ['az' => '39', 'ru' => '39', 'en' => '39'],
      '40' => ['az' => '40', 'ru' => '40', 'en' => '40'],
      '41' => ['az' => '41', 'ru' => '41', 'en' => '41'],
      '42' => ['az' => '42', 'ru' => '42', 'en' => '42'],
      '43' => ['az' => '43', 'ru' => '43', 'en' => '43'],
      '44' => ['az' => '44', 'ru' => '44', 'en' => '44'],
      '45' => ['az' => '45', 'ru' => '45', 'en' => '45'],
      '46' => ['az' => '46', 'ru' => '46', 'en' => '46'],
    ],
    'color-shoes' => [
      'black' => ['az' => 'Qara', 'ru' => 'Черный', 'en' => 'Black'],
      'white' => ['az' => 'Ağ', 'ru' => 'Белый', 'en' => 'White'],
      'gray' => ['az' => 'Boz', 'ru' => 'Серый', 'en' => 'Gray'],
      'red' => ['az' => 'Qırmızı', 'ru' => 'Красный', 'en' => 'Red'],
      'blue' => ['az' => 'Mavi', 'ru' => 'Синий', 'en' => 'Blue'],
      'brown' => ['az' => 'Qəhvəyi', 'ru' => 'Коричневый', 'en' => 'Brown'],
      'beige' => ['az' => 'Bej', 'ru' => 'Бежевый', 'en' => 'Beige'],
    ],
    'material-shoes' => [
      'leather' => ['az' => 'Dəri', 'ru' => 'Кожа', 'en' => 'Leather'],
      'synthetic' => ['az' => 'Süni', 'ru' => 'Синтетика', 'en' => 'Synthetic'],
      'textile' => ['az' => 'Tekstil', 'ru' => 'Текстиль', 'en' => 'Textile'],
      'suede' => ['az' => 'Zamşa', 'ru' => 'Замша', 'en' => 'Suede'],
    ],
    'condition-shoes' => [
      'new' => ['az' => 'Yeni', 'ru' => 'Новый', 'en' => 'New'],
      'used' => ['az' => 'İşlənmiş', 'ru' => 'Б/у', 'en' => 'Used'],
    ],
    'gender-shoes' => [
      'men' => ['az' => 'Kişi', 'ru' => 'Мужской', 'en' => 'Men'],
      'women' => ['az' => 'Qadın', 'ru' => 'Женский', 'en' => 'Women'],
      'unisex' => ['az' => 'Uniseks', 'ru' => 'Унисекс', 'en' => 'Unisex'],
      'kids' => ['az' => 'Uşaqlar', 'ru' => 'Дети', 'en' => 'Kids'],
    ],
    'product-type-beauty' => [
      'skincare' => ['az' => 'Dəriyə qulluq', 'ru' => 'Уход за кожей', 'en' => 'Skincare'],
      'makeup' => ['az' => 'Makiyaj', 'ru' => 'Макияж', 'en' => 'Makeup'],
      'perfume' => ['az' => 'Ətir', 'ru' => 'Парфюм', 'en' => 'Perfume'],
      'haircare' => ['az' => 'Saç baxımı', 'ru' => 'Уход за волосами', 'en' => 'Haircare'],
      'bodycare' => ['az' => 'Bədən baxımı', 'ru' => 'Уход за телом', 'en' => 'Bodycare'],
    ],
    'skin-type' => [
      'oily' => ['az' => 'Yağlı', 'ru' => 'Жирная', 'en' => 'Oily'],
      'dry' => ['az' => 'Quru', 'ru' => 'Сухая', 'en' => 'Dry'],
      'combination' => ['az' => 'Qarışıq', 'ru' => 'Комбинированная', 'en' => 'Combination'],
      'sensitive' => ['az' => 'Həssas', 'ru' => 'Чувствительная', 'en' => 'Sensitive'],
      'all' => ['az' => 'Hamısı', 'ru' => 'Все', 'en' => 'All'],
    ],
    'volume' => [
      '30ml' => ['az' => '30ml', 'ru' => '30мл', 'en' => '30ml'],
      '50ml' => ['az' => '50ml', 'ru' => '50мл', 'en' => '50ml'],
      '100ml' => ['az' => '100ml', 'ru' => '100мл', 'en' => '100ml'],
      '200ml' => ['az' => '200ml', 'ru' => '200мл', 'en' => '200ml'],
      '500ml' => ['az' => '500ml', 'ru' => '500мл', 'en' => '500ml'],
    ],
    'condition-beauty' => [
      'new' => ['az' => 'Yeni', 'ru' => 'Новый', 'en' => 'New'],
      'opened' => ['az' => 'Açılmış', 'ru' => 'Открытый', 'en' => 'Opened'],
    ],
    'brand-beauty' => [
      'any' => ['az' => 'Fərqi yoxdur', 'ru' => 'Любой', 'en' => 'Any'],
    ],
    'category-home' => [
      'furniture' => ['az' => 'Mebel', 'ru' => 'Мебель', 'en' => 'Furniture'],
      'kitchen' => ['az' => 'Mətbəx', 'ru' => 'Кухня', 'en' => 'Kitchen'],
      'decor' => ['az' => 'Dekor', 'ru' => 'Декор', 'en' => 'Decor'],
      'lighting' => ['az' => 'İşıqlandırma', 'ru' => 'Освещение', 'en' => 'Lighting'],
      'bedding' => ['az' => 'Yataq ləvaz.', 'ru' => 'Постельные', 'en' => 'Bedding'],
      'cleaning' => ['az' => 'Təmizlik', 'ru' => 'Уборка', 'en' => 'Cleaning'],
      'tools' => ['az' => 'Alətlər', 'ru' => 'Инструменты', 'en' => 'Tools'],
    ],
    'material-home' => [
      'wood' => ['az' => 'Taxta', 'ru' => 'Дерево', 'en' => 'Wood'],
      'metal' => ['az' => 'Metal', 'ru' => 'Металл', 'en' => 'Metal'],
      'plastic' => ['az' => 'Plastik', 'ru' => 'Пластик', 'en' => 'Plastic'],
      'glass' => ['az' => 'Şüşə', 'ru' => 'Стекло', 'en' => 'Glass'],
      'ceramic' => ['az' => 'Keramika', 'ru' => 'Керамика', 'en' => 'Ceramic'],
      'textile' => ['az' => 'Tekstil', 'ru' => 'Текстиль', 'en' => 'Textile'],
    ],
    'condition-home' => [
      'new' => ['az' => 'Yeni', 'ru' => 'Новый', 'en' => 'New'],
      'used' => ['az' => 'İşlənmiş', 'ru' => 'Б/у', 'en' => 'Used'],
    ],
    'color-home' => [
      'white' => ['az' => 'Ağ', 'ru' => 'Белый', 'en' => 'White'],
      'black' => ['az' => 'Qara', 'ru' => 'Черный', 'en' => 'Black'],
      'gray' => ['az' => 'Boz', 'ru' => 'Серый', 'en' => 'Gray'],
      'brown' => ['az' => 'Qəhvəyi', 'ru' => 'Коричневый', 'en' => 'Brown'],
      'beige' => ['az' => 'Bej', 'ru' => 'Бежевый', 'en' => 'Beige'],
    ],
    'age-range' => [
      '0-6m' => ['az' => '0-6 ay', 'ru' => '0-6 мес', 'en' => '0-6m'],
      '6-12m' => ['az' => '6-12 ay', 'ru' => '6-12 мес', 'en' => '6-12m'],
      '1-2y' => ['az' => '1-2 yaş', 'ru' => '1-2 г', 'en' => '1-2y'],
      '3-5y' => ['az' => '3-5 yaş', 'ru' => '3-5 лет', 'en' => '3-5y'],
      '6-8y' => ['az' => '6-8 yaş', 'ru' => '6-8 лет', 'en' => '6-8y'],
      '9-12y' => ['az' => '9-12 yaş', 'ru' => '9-12 лет', 'en' => '9-12y'],
      '13-16y' => ['az' => '13-16 yaş', 'ru' => '13-16 лет', 'en' => '13-16y'],
    ],
    'gender-kids' => [
      'boys' => ['az' => 'Oğlan', 'ru' => 'Мальчики', 'en' => 'Boys'],
      'girls' => ['az' => 'Qız', 'ru' => 'Девочки', 'en' => 'Girls'],
      'unisex' => ['az' => 'Uniseks', 'ru' => 'Унисекс', 'en' => 'Unisex'],
    ],
    'condition-kids' => [
      'new' => ['az' => 'Yeni', 'ru' => 'Новый', 'en' => 'New'],
      'used' => ['az' => 'İşlənmiş', 'ru' => 'Б/у', 'en' => 'Used'],
    ],
    'size-kids' => [
      'xs' => ['az' => 'XS', 'ru' => 'XS', 'en' => 'XS'],
      's' => ['az' => 'S', 'ru' => 'S', 'en' => 'S'],
      'm' => ['az' => 'M', 'ru' => 'M', 'en' => 'M'],
      'l' => ['az' => 'L', 'ru' => 'L', 'en' => 'L'],
      'xl' => ['az' => 'XL', 'ru' => 'XL', 'en' => 'XL'],
    ],
    'part-type' => [
      'tires' => ['az' => 'Şinlər', 'ru' => 'Шины', 'en' => 'Tires'],
      'battery' => ['az' => 'Akkumulyator', 'ru' => 'Аккумулятор', 'en' => 'Battery'],
      'oil' => ['az' => 'Yağ', 'ru' => 'Масло', 'en' => 'Oil'],
      'filters' => ['az' => 'Filtrlər', 'ru' => 'Фильтры', 'en' => 'Filters'],
      'lights' => ['az' => 'İşıqlar', 'ru' => 'Фары', 'en' => 'Lights'],
      'audio' => ['az' => 'Audio', 'ru' => 'Аудио', 'en' => 'Audio'],
      'accessories' => ['az' => 'Aksesuarlar', 'ru' => 'Аксессуары', 'en' => 'Accessories'],
      'tools' => ['az' => 'Alətlər', 'ru' => 'Инструменты', 'en' => 'Tools'],
    ],
    'condition-auto' => [
      'new' => ['az' => 'Yeni', 'ru' => 'Новый', 'en' => 'New'],
      'used' => ['az' => 'İşlənmiş', 'ru' => 'Б/у', 'en' => 'Used'],
    ],
    'brand-auto' => [
      'any' => ['az' => 'Fərqi yoxdur', 'ru' => 'Любой', 'en' => 'Any'],
    ],
    'compatibility' => [
      'universal' => ['az' => 'Universal', 'ru' => 'Универсальный', 'en' => 'Universal'],
    ],
    'language-books' => [
      'azerbaijani' => ['az' => 'Azərbaycan', 'ru' => 'Азербайджанский', 'en' => 'Azerbaijani'],
      'turkish' => ['az' => 'Türkcə', 'ru' => 'Турецкий', 'en' => 'Turkish'],
      'english' => ['az' => 'İngilis', 'ru' => 'Английский', 'en' => 'English'],
      'russian' => ['az' => 'Rus', 'ru' => 'Русский', 'en' => 'Russian'],
    ],
    'type-books' => [
      'book' => ['az' => 'Kitab', 'ru' => 'Книга', 'en' => 'Book'],
      'notebook' => ['az' => 'Dəftər', 'ru' => 'Тетрадь', 'en' => 'Notebook'],
      'pen' => ['az' => 'Qələm', 'ru' => 'Ручка', 'en' => 'Pen'],
      'office-supplies' => ['az' => 'Kanselariya', 'ru' => 'Канцтовары', 'en' => 'Office supplies'],
    ],
    'condition-books' => [
      'new' => ['az' => 'Yeni', 'ru' => 'Новый', 'en' => 'New'],
      'used' => ['az' => 'İşlənmiş', 'ru' => 'Б/у', 'en' => 'Used'],
    ],
    'type-food' => [
      'protein' => ['az' => 'Protein', 'ru' => 'Протеин', 'en' => 'Protein'],
      'creatine' => ['az' => 'Kreatin', 'ru' => 'Креатин', 'en' => 'Creatine'],
      'vitamins' => ['az' => 'Vitaminlər', 'ru' => 'Витамины', 'en' => 'Vitamins'],
      'minerals' => ['az' => 'Minerallar', 'ru' => 'Минералы', 'en' => 'Minerals'],
      'pre-workout' => ['az' => 'Pre-workout', 'ru' => 'Предтрен', 'en' => 'Pre-workout'],
      'snacks' => ['az' => 'Qəlyanaltı', 'ru' => 'Снеки', 'en' => 'Snacks'],
    ],
    'form-food' => [
      'powder' => ['az' => 'Toz', 'ru' => 'Порошок', 'en' => 'Powder'],
      'capsule' => ['az' => 'Kapsul', 'ru' => 'Капсула', 'en' => 'Capsule'],
      'tablet' => ['az' => 'Həb', 'ru' => 'Таблетка', 'en' => 'Tablet'],
      'liquid' => ['az' => 'Maye', 'ru' => 'Жидкость', 'en' => 'Liquid'],
    ],
    'weight-food' => [
      '250g' => ['az' => '250q', 'ru' => '250г', 'en' => '250g'],
      '500g' => ['az' => '500q', 'ru' => '500г', 'en' => '500g'],
      '1kg' => ['az' => '1kq', 'ru' => '1кг', 'en' => '1kg'],
      '2kg' => ['az' => '2kq', 'ru' => '2кг', 'en' => '2kg'],
    ],
    'condition-food' => [
      'new' => ['az' => 'Yeni', 'ru' => 'Новый', 'en' => 'New'],
    ],
    'flavor-food' => [
      'chocolate' => ['az' => 'Şokolad', 'ru' => 'Шоколад', 'en' => 'Chocolate'],
      'vanilla' => ['az' => 'Vanil', 'ru' => 'Ваниль', 'en' => 'Vanilla'],
      'strawberry' => ['az' => 'Çiyələk', 'ru' => 'Клубника', 'en' => 'Strawberry'],
      'unflavored' => ['az' => 'Dadsız', 'ru' => 'Без вкуса', 'en' => 'Unflavored'],
    ],
  ],
];

function localizeFilters(array $items, string $lang, array $dict): array {
  $filterNames = $dict['filters'] ?? [];
  $optionNames = $dict['options'] ?? [];
  foreach ($items as &$f) {
    $slug = $f['slug'] ?? ($f['filter_slug'] ?? null);
    if ($slug && isset($filterNames[$slug][$lang])) {
      $f['name'] = $filterNames[$slug][$lang];
      if (isset($f['filter_name'])) {
        $f['filter_name'] = $filterNames[$slug][$lang];
      }
    }
    if (!empty($f['options']) && is_array($f['options'])) {
      foreach ($f['options'] as &$o) {
        $oslug = $o['slug'] ?? null;
        if ($slug && $oslug && isset($optionNames[$slug][$oslug][$lang])) {
          $o['name'] = $optionNames[$slug][$oslug][$lang];
        }
      }
      unset($o);
    }
  }
  unset($f);
  return $items;
}

$userId = (int)($_SESSION['user_id'] ?? 0);
$filtersRaw = $filterService->listAll();
$enabledFilterIds = $filterService->getUserFilterIds($userId);
$enabledFilterIds = array_map('intval', $enabledFilterIds);
$filtersAll = localizeFilters($filtersRaw, $lang, $filterI18n);
$filters = !empty($enabledFilterIds)
  ? array_values(array_filter($filtersAll, fn($f) => in_array((int)$f['id'], $enabledFilterIds, true)))
  : [];
$productsRaw = $userId > 0 ? $productService->getProductsWithImages($userId) : [];
if (!empty($productsRaw)) {
  foreach ($productsRaw as &$pr) {
    if (!empty($pr['filters'])) {
      $pr['filters'] = localizeFilters($pr['filters'], $lang, $filterI18n);
    }
  }
  unset($pr);
}
$products = $productsRaw;
$placeholderImg = 'data:image/svg+xml;utf8,' . rawurlencode("<svg xmlns='http://www.w3.org/2000/svg' width='640' height='480'><rect width='100%' height='100%' fill='%23f1f5f9'/><text x='50%' y='50%' dominant-baseline='middle' text-anchor='middle' fill='%230a0a0a' font-family='Inter,Segoe UI,sans-serif' font-size='28'>No Image</text></svg>");

$errors = $_SESSION['errors'] ?? [];
$success = $_SESSION['success'] ?? '';
$old = $_SESSION['old'] ?? [];
unset($_SESSION['errors'], $_SESSION['success'], $_SESSION['old']);
?>

<section id="products" class="page">
  <div class="page-head">
    <div>
      <div class="eyebrow"><?php echo $t('products.eyebrow'); ?></div>
      <h1><?php echo $t('products.title'); ?></h1>
    </div>
    <div class="btn-row">
      <button class="btn ghost" id="openFilterManagerPage">Filter əlavə et</button>
      <button class="btn ghost"><?php echo $t('products.monitor'); ?></button>
      <button class="btn primary" id="openAddProduct">Məhsul əlavə et</button>
    </div>
  </div>

  <?php if (!empty($errors['general'])): ?>
    <div class="card" style="border:1px solid #ef4444;color:#ef4444;font-weight:700;">
      <?php echo $errors['general']; ?>
    </div>
  <?php endif; ?>
  <?php if (!empty($success)): ?>
    <div class="card" style="border:1px solid #16a34a;color:#166534;font-weight:700;">
      <?php echo htmlentities($success, ENT_QUOTES, 'UTF-8'); ?>
    </div>
  <?php endif; ?>

  <div class="toolbar">
    <div class="search">
      <span class="icon">⌕</span>
      <input type="search" placeholder="<?php echo $t('search_placeholder'); ?>" />
    </div>
    <div class="btn-row">
      <button class="pill active"><?php echo $t('buttons.grid'); ?></button>
      <button class="pill" disabled><?php echo $t('buttons.table'); ?></button>
      <button class="btn ghost" disabled><?php echo $t('buttons.status'); ?></button>
    </div>
  </div>

  <div class="product-grid">
    <?php foreach ($products as $product): ?>
      <?php
        $images = $product['images'] ?? [];
        $imgPaths = array_map(function($p) use ($baseUrl) {
          $clean = ltrim($p, '/');
          // Backward compatibility for older rows without the public/ prefix
          if (strpos($clean, 'public/') !== 0) {
            $clean = 'public/' . $clean;
          }
          return $baseUrl . '/' . $clean;
        }, $images);
        $first = $imgPaths[0] ?? $placeholderImg;
        $imagesJson = htmlentities(json_encode($imgPaths), ENT_QUOTES, 'UTF-8');
      ?>
      <article class="card product-card" data-id="<?php echo $product['id']; ?>" data-name="<?php echo htmlentities($product['name'], ENT_QUOTES, 'UTF-8'); ?>" data-price="<?php echo $product['price']; ?>" data-desc="<?php echo htmlentities($product['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" data-status="<?php echo $product['is_active'] ? 'active' : 'inactive'; ?>" data-images='<?php echo $imagesJson; ?>'>
        <div class="product-thumb">
          <div class="product-slider" data-images='<?php echo $imagesJson; ?>'>
            <img class="product-image" src="<?php echo $first; ?>" alt="Product" />
            <div class="slider-arrows"><button class="slider-prev">‹</button><button class="slider-next">›</button></div>
            <div class="product-slider-dots"></div>
          </div>
        </div>
        <div class="product-meta">
          <div class="product-name"><?php echo htmlentities($product['name'], ENT_QUOTES, 'UTF-8'); ?></div>
          <div class="product-price"><?php echo number_format($product['price'], 2); ?> ₼</div>
        </div>
        <div class="product-desc"><?php echo htmlentities($product['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
        <?php if (!empty($product['filters'])): ?>
          <div class="product-filters">
            <?php foreach ($product['filters'] as $pf): ?>
              <div class="product-filter-card">
                <div class="product-filter-name"><?php echo htmlentities($pf['filter_name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="product-filter-options">
                  <?php foreach (($pf['options'] ?? []) as $po): ?>
                    <span class="product-filter-chip"><?php echo htmlentities($po['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?></span>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
          </div>
        <?php endif; ?>
        <div class="product-status">
          <label class="toggle"><input type="checkbox" <?php echo $product['is_active'] ? 'checked' : ''; ?> disabled /><span></span></label>
          <span><?php echo $product['is_active'] ? $t('statuses.new') : $t('statuses.pending'); ?></span>
        </div>
        <div class="product-actions">
          <button class="link edit-btn"><?php echo $t('buttons.edit'); ?></button>
          <button class="link muted"><?php echo $t('buttons.delete'); ?></button>
        </div>
      </article>
    <?php endforeach; ?>

    <?php if (empty($products)): ?>
      <div class="card product-card" style="text-align:center;grid-column:1/-1;">
        <div class="product-name"><?php echo $t('products.empty'); ?></div>
      </div>
    <?php endif; ?>
  </div>

  <div class="modal overlay" id="addProductModal">
    <div class="modal sheet">
      <div class="modal-head">
        <h3><?php echo $t('products.modal_title'); ?></h3>
        <button class="icon-btn close-btn" onclick="closeAddProduct(); return false;">✕</button>
      </div>
      <form class="form-grid" id="addProductForm" action="<?php echo $baseUrl; ?>/public/products/store.php" method="POST" enctype="multipart/form-data">
        <label><?php echo $t('products.name'); ?>
          <input id="addName" name="name" type="text" placeholder="Məs: Latte" required value="<?php echo htmlentities($old['name'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
          <?php if (!empty($errors['name'])): ?><div class="error-text" style="color:#e5484d;font-size:13px;"><?php echo $errors['name']; ?></div><?php endif; ?>
        </label>
        <label><?php echo $t('products.price_azn'); ?>
          <input id="addPrice" name="price" type="number" placeholder="0" step="0.01" min="0" required value="<?php echo htmlentities($old['price'] ?? '', ENT_QUOTES, 'UTF-8'); ?>" />
          <?php if (!empty($errors['price'])): ?><div class="error-text" style="color:#e5484d;font-size:13px;"><?php echo $errors['price']; ?></div><?php endif; ?>
        </label>
        <label><?php echo $t('products.description'); ?>
          <textarea id="addDesc" name="description" placeholder="Qısa təsvir"><?php echo htmlentities($old['description'] ?? '', ENT_QUOTES, 'UTF-8'); ?></textarea>
        </label>
        <label><?php echo $t('products.upload'); ?>
          <div class="upload multi">
            <input id="addImages" name="images[]" type="file" accept="image/*" multiple style="display:none;" />
            <button class="btn ghost" type="button" onclick="document.getElementById('addImages').click(); return false;">Şəkil seç</button>
            <div class="thumbs" id="addThumbs"></div>
          </div>
          <?php if (!empty($errors['images'])): ?><div class="error-text" style="color:#e5484d;font-size:13px;"><?php echo $errors['images']; ?></div><?php endif; ?>
        </label>
        <label class="toggle-row"><?php echo $t('products.active'); ?>
          <label class="toggle"><input id="addActive" name="is_active" type="checkbox" value="1" <?php echo !isset($old['is_active']) || (int)($old['is_active']) === 1 ? 'checked' : ''; ?> /><span></span></label>
        </label>
        <div class="filter-select">
          <div class="filter-select-head">
            <span>Filterlər</span>
            <div class="btn-row" style="gap:6px;">
              <button class="btn ghost" type="button" id="openFilterManager">Filter əlavə et</button>
              <button class="btn ghost" type="button" id="refreshFilters">Yenilə</button>
            </div>
          </div>
          <div id="filterCheckboxesAdd" class="filter-checkboxes">
            <?php foreach ($filters as $f): ?>
              <div class="filter-block">
                <div class="filter-title"><?php echo htmlentities($f['name'], ENT_QUOTES, 'UTF-8'); ?></div>
                <div class="filter-options">
                  <?php foreach ($f['options'] as $o): ?>
                    <?php $checked = !empty($old['filter_option_ids']) && in_array($o['id'], $old['filter_option_ids'], true); ?>
                    <label class="pill">
                      <input type="checkbox" name="filter_option_ids[]" value="<?php echo (int)$o['id']; ?>" <?php echo $checked ? 'checked' : ''; ?> />
                      <?php echo htmlentities($o['name'], ENT_QUOTES, 'UTF-8'); ?>
                    </label>
                  <?php endforeach; ?>
                </div>
              </div>
            <?php endforeach; ?>
            <?php if (empty($filters)): ?>
              <p style="color:#475569;font-size:14px;">Filter yoxdur. "Filter əlavə et" düyməsini basın.</p>
            <?php endif; ?>
          </div>
        </div>
        <div class="form-actions">
          <button class="btn ghost" type="button" onclick="closeAddProduct(); return false; "><?php echo $t('buttons.dismiss'); ?></button>
          <button class="btn primary" type="submit"><?php echo $t('buttons.save'); ?></button>
        </div>
      </form>
    </div>
  </div>

  <div class="modal overlay" id="editProductModal">
    <div class="modal sheet">
      <div class="modal-head">
        <h3><?php echo $t('buttons.edit'); ?></h3>
        <button class="icon-btn close-btn" onclick="document.getElementById('editProductModal').classList.remove('active'); return false;">✕</button>
      </div>
      <form class="form-grid" id="editProductForm" action="<?php echo $baseUrl; ?>/public/products/update.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="product_id" id="editProductId" />
        <label><?php echo $t('products.name'); ?>
          <input id="editName" name="name" type="text" required />
        </label>
        <label><?php echo $t('products.price_azn'); ?>
          <input id="editPrice" name="price" type="number" step="0.01" min="0" required />
        </label>
        <label><?php echo $t('products.description'); ?>
          <textarea id="editDesc" name="description"></textarea>
        </label>
        <label class="toggle-row"><?php echo $t('products.active'); ?>
          <label class="toggle"><input id="editActive" name="is_active" type="checkbox" value="1" /><span></span></label>
        </label>
        <div class="filter-select">
          <div class="filter-select-head">
            <span>Filterlər</span>
            <div class="btn-row" style="gap:6px;">
              <button class="btn ghost" type="button" id="openFilterManagerEdit">Filter əlavə et</button>
              <button class="btn ghost" type="button" id="refreshFiltersEdit">Yenilə</button>
            </div>
          </div>
          <div id="filterCheckboxesEdit" class="filter-checkboxes"></div>
        </div>
        <label><?php echo $t('products.upload'); ?>
          <div class="product-thumb" style="height: 200px;">
            <div class="product-slider" id="editSlider" data-images="[]">
              <img class="product-image" src="" alt="Product" />
              <div class="slider-arrows"><button class="slider-prev">‹</button><button class="slider-next">›</button></div>
              <div class="product-slider-dots"></div>
            </div>
          </div>
          <p style="margin:8px 0 4px;font-size:13px;color:#475569;">Yeni şəkillər seçsən, köhnələr əvəzlənəcək.</p>
          <div class="upload multi">
            <input id="editImages" name="images[]" type="file" accept="image/*" multiple style="display:none;" />
            <button class="btn ghost" type="button" onclick="document.getElementById('editImages').click(); return false;">Yeni şəkil seç (optional)</button>
            <div class="thumbs" id="editThumbs"></div>
          </div>
        </label>
        <div class="form-actions">
          <button class="btn ghost" type="button" onclick="document.getElementById('editProductModal').classList.remove('active'); return false; "><?php echo $t('buttons.dismiss'); ?></button>
          <button class="btn primary" type="submit"><?php echo $t('buttons.save'); ?></button>
        </div>
      </form>
    </div>
  </div>

  <form id="deleteProductForm" action="<?php echo $baseUrl; ?>/public/products/delete.php" method="POST" style="display:none;">
    <input type="hidden" name="product_id" id="deleteProductId" />
  </form>

  <div class="modal overlay" id="filterManagerModal">
    <div class="modal sheet">
      <div class="modal-head">
        <h3>Filterlər</h3>
        <button class="icon-btn close-btn close-circle" type="button" onclick="closeFilterManager(); return false;">✕</button>
      </div>
      <div class="tabs" style="margin-bottom:12px;">
        <button class="tab active" data-tab="create-filter">Filter yarat</button>
        <button class="tab" data-tab="manage-options">Option idarə et</button>
        <button class="tab" data-tab="bulk-assign">Məhsullara tətbiq et</button>
      </div>
      <div class="tab-body" id="tab-create-filter">
        <div class="fm-grid">
          <div class="fm-card">
            <div class="fm-title">Filter yarat</div>
            <label class="fm-label">Filter adı</label>
            <input id="filterNameInput" type="text" placeholder="Məs: Colors" />
            <button class="btn primary" type="button" id="btnCreateFilter">Yarat</button>
            <p id="filterCreateMsg" class="msg"></p>
          </div>
          <div class="fm-card">
            <div class="fm-title">Bu mağazada istifadə ediləcək filter növləri</div>
            <p class="hint">Seçdiyiniz filterlər bütün hissələrdə görünəcək.</p>
            <div id="filterScopeList" class="filter-scope"></div>
            <div class="fm-actions">
              <button class="btn primary" type="button" id="btnSaveFilterScope">Seçilənləri yadda saxla</button>
              <p id="filterScopeMsg" class="msg"></p>
            </div>
          </div>
        </div>
        <div class="fm-card full">
          <div class="fm-title">Mövcud filterlər</div>
          <div id="filtersSummary" class="filters-summary"></div>
        </div>
      </div>
      <div class="tab-body hidden" id="tab-manage-options">
        <div class="fm-grid" style="grid-template-columns:380px 1fr; align-items:start;">
          <div class="fm-card">
            <p class="hint">Yalnız "Bu mağazada istifadə ediləcək" bölməsində seçdiyiniz filterlər burada görünəcək.</p>
            <div class="form-stack">
              <label class="fm-label">Filter seç</label>
              <select id="filterSelect"></select>
              <label class="fm-label">Option adı</label>
              <input id="optionNameInput" type="text" placeholder="Məs: Red" />
              <button class="btn primary" type="button" id="btnCreateOption">Option əlavə et</button>
              <p id="optionCreateMsg" class="msg"></p>
            </div>
          </div>
          <div class="fm-card">
            <div class="fm-title">Mövcud optionlar</div>
            <div id="optionList" class="options-list"></div>
          </div>
        </div>
      </div>
      <div class="tab-body hidden" id="tab-bulk-assign">
        <div class="fm-grid" style="grid-template-columns:1fr 1fr;">
          <div class="fm-card">
            <div class="filters-summary-head" style="margin-top:4px;">Filter və option seç</div>
            <p class="hint">Birdən çox filterdən birdən çox option seçib aşağıdakı məhsullara tətbiq edə bilərsiniz.</p>
            <div id="bulkOptionList" class="filter-scope"></div>
          </div>
          <div class="fm-card">
            <div class="filters-summary-head" style="margin-top:4px;">Məhsul seç</div>
            <div class="bulk-product-toolbar">
              <input type="search" id="bulkProductSearch" placeholder="Məhsul adı ilə axtar" />
              <div class="btn-row" style="gap:8px;">
                <button class="btn ghost" type="button" id="bulkProductSelectAll">Hamısını seç</button>
                <button class="btn ghost" type="button" id="bulkProductClear">Təmizlə</button>
              </div>
            </div>
            <div id="bulkProductList" class="bulk-product-list"></div>
            <button class="btn primary" type="button" id="btnBulkAssign" style="margin-top:8px;">Seçilənləri tətbiq et</button>
            <p id="bulkAssignMsg" class="msg"></p>
          </div>
        </div>
      </div>
    </div>
  </div>

  <style>
    /* Filter UI styling — refreshed, roomier layout */
    .filter-select { border:1px solid #e2e8f0; border-radius:14px; padding:14px; background:#fff; box-shadow:0 16px 40px rgba(15,23,42,0.12); }
    .filter-select-head { display:flex; justify-content:space-between; align-items:center; gap:12px; margin-bottom:10px; }
    .filter-select-head span { font-weight:600; color:#0f172a; }
    .filter-checkboxes { display:flex; flex-direction:column; gap:10px; }
    .filter-block { border:1px solid #e2e8f0; border-radius:12px; padding:12px; background:linear-gradient(145deg,#fff,#f8fafc); box-shadow:0 10px 30px rgba(15,23,42,0.08); }
    .filter-title { font-weight:650; margin-bottom:8px; color:#0f172a; letter-spacing:0.2px; }
    .filter-options { display:flex; flex-wrap:wrap; gap:10px; }
    .pill { display:inline-flex; align-items:center; gap:8px; padding:9px 13px; border-radius:999px; border:1px solid #d8dee9; background:#fff; color:#0f172a; box-shadow:0 6px 16px rgba(15,23,42,0.06); transition:all .15s ease; }
    .pill:hover { transform:translateY(-1px); box-shadow:0 12px 30px rgba(15,23,42,0.10); border-color:#0ea5e9; }
    .pill input { margin:0; accent-color:#0ea5e9; }
    .tabs { display:flex; gap:12px; }
    .tab { flex:1; padding:14px; border-radius:14px; border:1px solid #e2e8f0; background:#f8fafc; cursor:pointer; font-weight:650; color:#0f172a; transition:all .18s ease; }
    .tab:hover { box-shadow:0 12px 28px rgba(15,23,42,0.08); transform:translateY(-1px); }
    .tab.active { background:linear-gradient(135deg,#0ea5e9,#0284c7); color:#fff; border-color:#0ea5e9; box-shadow:0 18px 36px rgba(14,165,233,0.25); }
    .tab-body { display:flex; flex-direction:column; gap:18px; padding:6px 2px 10px; }
    .tab-body.hidden { display:none; }
    .msg { min-height:18px; color:#475569; font-size:13px; }
    .fm-grid { display:grid; grid-template-columns:repeat(auto-fit, minmax(360px, 1fr)); gap:18px; }
    .fm-card { border:1px solid #e2e8f0; border-radius:16px; padding:20px 20px; background:#fff; box-shadow:0 16px 36px rgba(15,23,42,0.12); }
    .fm-card.full { grid-column:1 / -1; }
    .fm-title { font-weight:700; color:#0f172a; margin-bottom:8px; font-size:15px; letter-spacing:0.2px; }
    .fm-label { font-weight:600; color:#0f172a; margin-top:6px; }
    .fm-actions { display:flex; align-items:center; gap:10px; flex-wrap:wrap; margin-top:8px; }
    .form-stack { display:flex; flex-direction:column; gap:10px; margin-top:6px; }
    .form-stack select, .form-stack input, .form-stack button { width:100%; }
    .form-stack button { justify-content:center; }
    .bulk-product-list { display:grid; grid-template-columns:repeat(auto-fit, minmax(180px,1fr)); gap:12px; margin-top:8px; }
    .bulk-product-card { position:relative; display:flex; align-items:center; gap:10px; padding:10px; border:1px solid #e2e8f0; border-radius:12px; background:#fff; box-shadow:0 8px 18px rgba(15,23,42,0.1); min-height:90px; }
    .bulk-product-card img { width:60px; height:60px; object-fit:cover; border-radius:10px; background:#f1f5f9; }
    .bulk-product-info { flex:1; min-width:0; }
    .bulk-product-name { font-weight:600; color:#0f172a; font-size:13px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap; }
    .bulk-product-meta { color:#64748b; font-size:12px; margin-top:2px; }
    .bulk-product-card input[type="checkbox"] { width:18px; height:18px; accent-color:#0ea5e9; }
    .options-list { margin-top:12px; padding:16px; border:1px solid #e2e8f0; border-radius:14px; max-height:320px; min-height:160px; overflow:auto; background:linear-gradient(145deg,#fff,#f8fafc); box-shadow:inset 0 1px 0 rgba(255,255,255,0.7); }
    .filters-summary-head { margin-top:6px; font-weight:600; color:#0f172a; }
    .hint { margin:0 0 6px 0; color:#475569; font-size:13px; }
    .filters-summary { display:grid; grid-template-columns:repeat(auto-fit, minmax(200px,1fr)); gap:10px; margin-top:6px; }
    .filter-chip-card { border:1px solid #e2e8f0; border-radius:12px; padding:10px 12px; background:#fff; box-shadow:0 8px 20px rgba(15,23,42,0.07); min-width:180px; }
    .filter-chip-title { font-weight:650; color:#0f172a; margin-bottom:8px; font-size:14px; }
    .filter-chip-options { display:flex; flex-wrap:wrap; gap:10px; }
    .option-chip, .product-filter-chip { display:inline-flex; align-items:center; padding:7px 11px; border-radius:999px; background:#0f172a; color:#fff; font-size:13px; box-shadow:0 8px 18px rgba(15,23,42,0.16); }
    .selected-filter-pill { display:inline-flex; align-items:center; justify-content:space-between; gap:8px; padding:10px 12px; border:1px solid #e2e8f0; border-radius:10px; background:linear-gradient(145deg,#fff,#f8fafc); font-weight:600; color:#0f172a; }
    .options-list .empty { color:#64748b; font-size:13px; }
    .product-filters { display:flex; flex-wrap:wrap; gap:10px; margin-top:8px; }
    .product-filter-card { border:1px solid #e2e8f0; border-radius:10px; padding:8px 10px; background:#f8fafc; box-shadow:0 6px 14px rgba(15,23,42,0.08); }
    .product-filter-name { font-weight:600; color:#0f172a; margin-bottom:6px; font-size:13px; }
    .product-filter-options { display:flex; gap:6px; flex-wrap:wrap; }
    .product-filter-chip { background:#0ea5e9; box-shadow:0 10px 24px rgba(14,165,233,0.25); }
    .bulk-product-toolbar { display:flex; gap:10px; align-items:center; flex-wrap:wrap; margin:6px 0; }
    .bulk-product-toolbar input[type="search"] { flex:1; min-width:220px; padding:10px 12px; border:1px solid #e2e8f0; border-radius:10px; font-size:14px; }
    /* Header filter button custom look */
    #openFilterManagerPage.btn.ghost { background:#e0f2fe; border-color:#bae6fd; color:#0f172a; box-shadow:0 6px 16px rgba(14,165,233,0.18); transition:all .15s ease; }
    #openFilterManagerPage.btn.ghost:hover { background:linear-gradient(135deg,#0ea5e9,#0284c7); border-color:#0284c7; color:#fff; box-shadow:0 10px 28px rgba(14,165,233,0.3); transform:translateY(-1px); }
    /* Modal tweaks for consistency */
    #filterManagerModal .modal.sheet { border-radius:20px; box-shadow:0 26px 72px rgba(15,23,42,0.26); width:min(1240px, 96vw); max-height:92vh; overflow:auto; padding:22px 24px; }
    #filterManagerModal .modal-head { position:sticky; top:0; background:#fff; z-index:3; padding-bottom:10px; margin-bottom:6px; border-bottom:1px solid #e2e8f0; }
    #filterManagerModal .tabs { position:sticky; top:58px; z-index:2; background:#fff; padding:6px 0 10px; margin-bottom:4px; }
    #filterManagerModal input, #filterManagerModal select { border-radius:12px; border:1px solid #d8dee9; padding:12px 14px; font-size:14px; background:#f8fafc; }
    #filterManagerModal button.btn.primary { border-radius:12px; font-weight:700; padding:11px 16px; }
    #filterManagerModal .close-circle { width:38px; height:38px; border-radius:50%; background:#f8fafc; border:1px solid #e2e8f0; display:inline-flex; align-items:center; justify-content:center; font-weight:700; color:#0f172a; transition:all .15s ease; box-shadow:0 6px 16px rgba(15,23,42,0.12); }
    #filterManagerModal .close-circle:hover { background:#fee2e2; border-color:#ef4444; color:#b91c1c; box-shadow:0 0 0 4px rgba(239,68,68,0.15), 0 12px 26px rgba(15,23,42,0.16); transform:translateY(-1px); }
    .filter-scope { display:flex; flex-wrap:wrap; gap:10px; margin-top:8px; }
    .filter-scope .scope-chip { display:inline-flex; align-items:center; gap:8px; padding:9px 12px; border-radius:12px; border:1px solid #e2e8f0; background:#fff; box-shadow:0 6px 16px rgba(15,23,42,0.08); cursor:pointer; transition:all .15s ease; }
    .filter-scope .scope-chip:hover { box-shadow:0 12px 28px rgba(15,23,42,0.14); }
    .filter-scope .scope-chip input { accent-color:#0ea5e9; }
    #bulkProductList .scope-chip { min-width: 180px; }
  </style>

  <script>
    (function() {
      const addModal = document.getElementById('addProductModal');
      const openAddBtn = document.getElementById('openAddProduct');
      const addImages = document.getElementById('addImages');
      const addThumbs = document.getElementById('addThumbs');
      const addForm = document.getElementById('addProductForm');
      const filterI18n = <?php echo json_encode($filterI18n, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
      const currentLang = "<?php echo $lang; ?>";
      const productsList = <?php echo json_encode(array_map(function($p) use ($baseUrl, $placeholderImg) {
        $images = $p['images'] ?? [];
        $imgPaths = array_map(function($path) use ($baseUrl) {
          $clean = ltrim($path, '/');
          if (strpos($clean, 'public/') !== 0) {
            $clean = 'public/' . $clean;
          }
          return rtrim($baseUrl, '/') . '/' . $clean;
        }, $images);
        $thumb = $imgPaths[0] ?? $placeholderImg;
        $hasFilters = !empty($p['filters']);
        return ['id' => $p['id'], 'name' => $p['name'], 'thumb' => $thumb, 'has_filters' => $hasFilters];
      }, $productsRaw), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
      const searchInput = document.querySelector('#products .toolbar .search input');
      const productGrid = document.querySelector('#products .product-grid');
      const productCards = Array.from(document.querySelectorAll('#products .product-card[data-id]'));
      const filterCheckboxesAdd = document.getElementById('filterCheckboxesAdd');
      const filterCheckboxesEdit = document.getElementById('filterCheckboxesEdit');
      const btnOpenFilterManager = document.getElementById('openFilterManager');
      const btnOpenFilterManagerEdit = document.getElementById('openFilterManagerEdit');
      const btnOpenFilterManagerPage = document.getElementById('openFilterManagerPage');
      const btnRefreshFilters = document.getElementById('refreshFilters');
      const btnRefreshFiltersEdit = document.getElementById('refreshFiltersEdit');
      const filterManagerModal = document.getElementById('filterManagerModal');
      const filterTabs = filterManagerModal.querySelectorAll('.tab');
      const tabBodies = filterManagerModal.querySelectorAll('.tab-body');
      const filterNameInput = document.getElementById('filterNameInput');
      const filterCreateMsg = document.getElementById('filterCreateMsg');
      const optionNameInput = document.getElementById('optionNameInput');
      const optionCreateMsg = document.getElementById('optionCreateMsg');
      const filterSelect = document.getElementById('filterSelect');
      const optionList = document.getElementById('optionList');
      const filtersSummary = document.getElementById('filtersSummary');
      const selectedFilterBadge = document.getElementById('selectedFilterBadge');
      const bulkOptionList = document.getElementById('bulkOptionList');
      const bulkProductList = document.getElementById('bulkProductList');
      const bulkProductSearch = document.getElementById('bulkProductSearch');
      const bulkProductSelectAll = document.getElementById('bulkProductSelectAll');
      const bulkProductClear = document.getElementById('bulkProductClear');
      const bulkAssignMsg = document.getElementById('bulkAssignMsg');
      const baseUrl = "<?php echo $baseUrl; ?>";
      const filtersApi = {
        list: baseUrl + '/public/admin/filters_list.php',
        create: baseUrl + '/public/admin/filters_create.php',
        optionCreate: baseUrl + '/public/admin/filter_options_create.php',
        productEdit: baseUrl + '/public/admin/product_edit_data.php',
        userFiltersGet: baseUrl + '/public/admin/filter_user_get.php',
        userFiltersSet: baseUrl + '/public/admin/filter_user_set.php',
        bulkAssign: baseUrl + '/public/admin/product_bulk_options.php'
      };
      function translateFiltersClient(list) {
        const fNames = filterI18n.filters || {};
        const oNames = filterI18n.options || {};
        return (list || []).map(f => {
          const slug = f.slug || f.filter_slug || '';
          if (slug && fNames[slug] && fNames[slug][currentLang]) {
            f.name = fNames[slug][currentLang];
            if (typeof f.filter_name !== 'undefined') {
              f.filter_name = fNames[slug][currentLang];
            }
          }
          f.options = (f.options || []).map(o => {
            const oslug = o.slug || '';
            if (slug && oslug && oNames[slug] && oNames[slug][oslug] && oNames[slug][oslug][currentLang]) {
              o.name = oNames[slug][oslug][currentLang];
            }
            return o;
          });
          return f;
        });
      }

      let filtersCache = translateFiltersClient(<?php echo json_encode($filtersAll ?? $filters ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>);
      let enabledFilterIds = <?php echo json_encode($enabledFilterIds, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES); ?>;
      let editSelectedOptionIds = [];
      let currentFilterTab = 'create-filter';
      let addSelectedFiles = [];

      function setFilterTab(tabId) {
        currentFilterTab = tabId;
        filterTabs.forEach(t => t.classList.toggle('active', t.dataset.tab === tabId));
        tabBodies.forEach(b => b.classList.toggle('hidden', b.id !== 'tab-' + tabId));
      }

      filterTabs.forEach(t => t.addEventListener('click', () => setFilterTab(t.dataset.tab)));

      // Close on overlay click
      filterManagerModal.addEventListener('click', (e) => {
        if (e.target === filterManagerModal) {
          closeFilterManager();
        }
      });

      function closeFilterManager() {
        filterManagerModal.classList.remove('active');
      }

      window.closeFilterManager = closeFilterManager;

      function renderFilterSelect() {
        if (filterSelect) {
          filterSelect.innerHTML = '';
          const source = enabledFilterIds.length ? filtersCache.filter(f => enabledFilterIds.includes(Number(f.id))) : [];
          if (!source.length) {
            const opt = document.createElement('option');
            opt.value = '';
            opt.textContent = 'Əvvəlcə istifadə ediləcək filterləri seç';
            filterSelect.appendChild(opt);
          } else {
            source.forEach(f => {
              const opt = document.createElement('option');
              opt.value = f.id;
              opt.textContent = f.name;
              filterSelect.appendChild(opt);
            });
          }
        }
        renderOptionList();
        renderFiltersSummary();
        renderFilterScope();
        renderBulkOptions();
        renderBulkProducts();
      }

      function renderOptionList() {
        optionList.innerHTML = '';
        if (!enabledFilterIds.length) {
          const info = document.createElement('div');
          info.className = 'empty';
          info.textContent = 'Əvvəlcə "Bu mağazada istifadə ediləcək filter növləri" bölməsindən filter seçin.';
          optionList.appendChild(info);
          return;
        }
        const fid = parseInt(filterSelect.value || '0', 10);
        const f = filtersCache.find(x => x.id === fid);
        setSelectedFilterBadge(f);
        if (!f) {
          const empty = document.createElement('div');
          empty.className = 'empty';
          empty.textContent = 'Filter yoxdur';
          optionList.appendChild(empty);
          return;
        }
        const opts = f.options || [];
        if (!opts.length) {
          const empty = document.createElement('div');
          empty.className = 'empty';
          empty.textContent = 'Option yoxdur';
          optionList.appendChild(empty);
          return;
        }
        opts.forEach(o => {
          const chip = document.createElement('span');
          chip.className = 'option-chip';
          chip.textContent = o.name;
          optionList.appendChild(chip);
        });
      }

      filterSelect.addEventListener('change', renderOptionList);

      function renderBulkOptions() {
        if (!bulkOptionList) return;
        bulkOptionList.innerHTML = '';
        const source = enabledFilterIds.length
          ? filtersCache.filter(f => enabledFilterIds.includes(Number(f.id)))
          : [];
        if (!source.length) {
          const empty = document.createElement('div');
          empty.className = 'empty';
          empty.textContent = 'Əvvəlcə istifadə ediləcək filterləri seçin (yuxarıdakı siyahıdan).';
          bulkOptionList.appendChild(empty);
          return;
        }
        source.forEach(f => {
          const block = document.createElement('div');
          block.className = 'filter-block';
          const title = document.createElement('div');
          title.className = 'filter-title';
          title.textContent = f.name;
          block.appendChild(title);
          const wrap = document.createElement('div');
          wrap.className = 'filter-options';
          const opts = f.options || [];
          if (!opts.length) {
            const empty = document.createElement('div');
            empty.className = 'empty';
            empty.textContent = 'Option yoxdur';
            wrap.appendChild(empty);
          } else {
            opts.forEach(o => {
              const chip = document.createElement('label');
              chip.className = 'pill';
              const cb = document.createElement('input');
              cb.type = 'checkbox';
              cb.value = o.id;
              chip.appendChild(cb);
              const span = document.createElement('span');
              span.textContent = o.name;
              chip.appendChild(span);
              wrap.appendChild(chip);
            });
          }
          block.appendChild(wrap);
          bulkOptionList.appendChild(block);
        });
      }

      function renderBulkProducts() {
        if (!bulkProductList) return;
        bulkProductList.innerHTML = '';
        const term = (bulkProductSearch?.value || '').toLowerCase();
        const filtered = (term
          ? productsList.filter(p => (p.name || '').toLowerCase().includes(term))
          : productsList
        ).filter(p => !p.has_filters);

        if (!productsList.length) {
          const empty = document.createElement('div');
          empty.className = 'empty';
          empty.textContent = 'Məhsul yoxdur';
          bulkProductList.appendChild(empty);
          return;
        }
        if (!filtered.length) {
          const empty = document.createElement('div');
          empty.className = 'empty';
          empty.textContent = 'Filteri olmayan məhsul tapılmadı';
          bulkProductList.appendChild(empty);
          return;
        }

        filtered.forEach(p => {
          const card = document.createElement('label');
          card.className = 'bulk-product-card';

          const img = document.createElement('img');
          img.src = p.thumb || '';
          img.alt = p.name || 'Product';
          card.appendChild(img);

          const info = document.createElement('div');
          info.className = 'bulk-product-info';
          const name = document.createElement('div');
          name.className = 'bulk-product-name';
          name.textContent = p.name;
          info.appendChild(name);
          const meta = document.createElement('div');
          meta.className = 'bulk-product-meta';
          meta.textContent = `ID ${p.id}`;
          info.appendChild(meta);
          card.appendChild(info);

          const cb = document.createElement('input');
          cb.type = 'checkbox';
          cb.value = p.id;
          card.appendChild(cb);

          bulkProductList.appendChild(card);
        });
      }

      bulkProductSearch?.addEventListener('input', renderBulkProducts);
      bulkProductSelectAll?.addEventListener('click', () => {
        bulkProductList?.querySelectorAll('input[type="checkbox"]').forEach(cb => { cb.checked = true; });
      });
      bulkProductClear?.addEventListener('click', () => {
        bulkProductList?.querySelectorAll('input[type="checkbox"]').forEach(cb => { cb.checked = false; });
      });

      function setSelectedFilterBadge(filter) {
        if (!selectedFilterBadge) return;
        if (!filter) {
          selectedFilterBadge.textContent = 'Filter seç';
          return;
        }
        selectedFilterBadge.textContent = filter.name + ' filteri';
      }

      function renderFiltersSummary() {
        if (!filtersSummary) return;
        filtersSummary.innerHTML = '';
        if (!filtersCache.length) {
          const empty = document.createElement('div');
          empty.className = 'empty';
          empty.textContent = 'Filter yoxdur.';
          filtersSummary.appendChild(empty);
          return;
        }
        filtersCache.forEach(f => {
          const card = document.createElement('div');
          card.className = 'filter-chip-card';
          const title = document.createElement('div');
          title.className = 'filter-chip-title';
          title.textContent = f.name;
          const optsWrap = document.createElement('div');
          optsWrap.className = 'filter-chip-options';
          (f.options || []).forEach(o => {
            const chip = document.createElement('span');
            chip.className = 'option-chip';
            chip.textContent = o.name;
            optsWrap.appendChild(chip);
          });
          card.appendChild(title);
          card.appendChild(optsWrap);
          filtersSummary.appendChild(card);
        });
      }

      function renderFilterScope() {
        const scope = document.getElementById('filterScopeList');
        if (!scope) return;
        scope.innerHTML = '';
        if (!filtersCache.length) {
          const empty = document.createElement('div');
          empty.className = 'empty';
          empty.textContent = 'Filter yoxdur.';
          scope.appendChild(empty);
          return;
        }
        filtersCache.forEach(f => {
          const chip = document.createElement('label');
          chip.className = 'scope-chip';
          const cb = document.createElement('input');
          cb.type = 'checkbox';
          cb.value = f.id;
          cb.checked = enabledFilterIds.includes(Number(f.id));
          cb.addEventListener('change', () => {
            if (cb.checked) {
              if (!enabledFilterIds.includes(Number(f.id))) enabledFilterIds.push(Number(f.id));
            } else {
              enabledFilterIds = enabledFilterIds.filter(x => x !== Number(f.id));
            }
          });
          chip.appendChild(cb);
          const txt = document.createElement('span');
          txt.textContent = f.name;
          chip.appendChild(txt);
          scope.appendChild(chip);
        });
      }

      function renderCheckboxes(container, selectedIds) {
        if (!container) return;
        container.innerHTML = '';
        const visible = filtersCache.filter(f => enabledFilterIds.includes(Number(f.id)));
        const source = enabledFilterIds.length ? visible : [];

        if (!filtersCache.length) {
          const p = document.createElement('p');
          p.textContent = 'Filter yoxdur. "Filter əlavə et" düyməsini basın.';
          p.style.color = '#475569';
          p.style.fontSize = '14px';
          container.appendChild(p);
          return;
        }
        if (!source.length) {
          const p = document.createElement('p');
          p.textContent = 'Heç bir filter seçilməyib. "Filterlər" bölməsində istifadə edəcəyiniz filterləri aktivləşdirin.';
          p.style.color = '#ef4444';
          p.style.fontSize = '14px';
          container.appendChild(p);
          return;
        }

        source.forEach(f => {
          const block = document.createElement('div');
          block.className = 'filter-block';
          const title = document.createElement('div');
          title.className = 'filter-title';
          title.textContent = f.name;
          const optsWrap = document.createElement('div');
          optsWrap.className = 'filter-options';
          (f.options || []).forEach(o => {
            const label = document.createElement('label');
            label.className = 'pill';
            const cb = document.createElement('input');
            cb.type = 'checkbox';
            cb.name = 'filter_option_ids[]';
            cb.value = o.id;
            if (selectedIds.includes(Number(o.id))) cb.checked = true;
            label.appendChild(cb);
            label.appendChild(document.createTextNode(o.name));
            optsWrap.appendChild(label);
          });
          block.appendChild(title);
          block.appendChild(optsWrap);
          container.appendChild(block);
        });
      }

      async function loadFilters() {
        try {
          const res = await fetch(filtersApi.list, { credentials: 'same-origin' });
          const data = await res.json();
          if (!data.ok) return;
          filtersCache = translateFiltersClient(data.data || []);
          await loadUserFilterScope();
          renderFilterSelect();
          renderCheckboxes(filterCheckboxesAdd, []);
          renderCheckboxes(filterCheckboxesEdit, editSelectedOptionIds);
          renderBulkOptions();
          renderBulkProducts();
        } catch (e) {
          console.error('Filters load failed', e);
        }
      }

      async function loadUserFilterScope() {
        try {
          const res = await fetch(filtersApi.userFiltersGet, { credentials: 'same-origin' });
          const data = await res.json();
          if (data.ok && data.data && Array.isArray(data.data.filter_ids)) {
            enabledFilterIds = data.data.filter_ids.map(Number);
          }
        } catch (e) {
          console.error('User filter scope load failed', e);
        }
      }

      renderCheckboxes(filterCheckboxesAdd, []);
      renderFilterScope();

      btnOpenFilterManager && btnOpenFilterManager.addEventListener('click', (e) => {
        e.preventDefault();
        renderFilterSelect();
        renderOptionList();
        renderFilterScope();
        filterManagerModal.classList.add('active');
      });

      btnOpenFilterManagerEdit && btnOpenFilterManagerEdit.addEventListener('click', (e) => {
        e.preventDefault();
        renderFilterSelect();
        renderOptionList();
        renderFilterScope();
        filterManagerModal.classList.add('active');
      });

      btnOpenFilterManagerPage && btnOpenFilterManagerPage.addEventListener('click', (e) => {
        e.preventDefault();
        loadFilters();
        renderFilterSelect();
        renderOptionList();
        renderFilterScope();
        filterManagerModal.classList.add('active');
      });

      btnRefreshFilters && btnRefreshFilters.addEventListener('click', (e) => { e.preventDefault(); loadFilters(); });
      btnRefreshFiltersEdit && btnRefreshFiltersEdit.addEventListener('click', (e) => { e.preventDefault(); loadFilters(); });

      document.getElementById('btnCreateFilter')?.addEventListener('click', async () => {
        filterCreateMsg.textContent = '';
        const name = filterNameInput.value.trim();
        if (!name) { filterCreateMsg.textContent = 'Ad tələb olunur'; return; }
        const res = await fetch(filtersApi.create, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ filter_name: name }) });
        const data = await res.json();
        if (!data.ok) { filterCreateMsg.textContent = data.message || 'Xəta'; return; }
        filterCreateMsg.textContent = 'Yaradıldı';
        filterNameInput.value = '';
        await loadFilters();
      });

      document.getElementById('btnCreateOption')?.addEventListener('click', async () => {
        optionCreateMsg.textContent = '';
        const fid = parseInt(filterSelect.value || '0', 10);
        const name = optionNameInput.value.trim();
        if (!fid || !name) { optionCreateMsg.textContent = 'Filter və ad tələb olunur'; return; }
        const res = await fetch(filtersApi.optionCreate, { method: 'POST', headers: { 'Content-Type': 'application/json' }, body: JSON.stringify({ filter_id: fid, option_name: name }) });
        const data = await res.json();
        if (!data.ok) { optionCreateMsg.textContent = data.message || 'Xəta'; return; }
        optionCreateMsg.textContent = 'Əlavə edildi';
        optionNameInput.value = '';
        await loadFilters();
      });

      document.getElementById('btnSaveFilterScope')?.addEventListener('click', async () => {
        const scopeMsg = document.getElementById('filterScopeMsg');
        scopeMsg.textContent = '';
        try {
          const res = await fetch(filtersApi.userFiltersSet, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ filter_ids: enabledFilterIds })
          });
          const data = await res.json();
          if (!data.ok) {
            scopeMsg.textContent = data.message || 'Xəta';
            return;
          }
          enabledFilterIds = (data.data?.filter_ids || []).map(Number);
          scopeMsg.textContent = 'Yadda saxlanıldı';
          renderCheckboxes(filterCheckboxesAdd, []);
          renderCheckboxes(filterCheckboxesEdit, editSelectedOptionIds);
          renderFilterScope();
          renderBulkOptions();
        } catch (e) {
          scopeMsg.textContent = 'Xəta baş verdi';
        }
      });

      document.getElementById('btnBulkAssign')?.addEventListener('click', async () => {
        if (!bulkAssignMsg) return;
        bulkAssignMsg.textContent = '';
        if (!bulkOptionList || !bulkProductList) {
          bulkAssignMsg.textContent = 'UI yüklənmədi';
          return;
        }
        const optionIds = Array.from(bulkOptionList.querySelectorAll('input[type="checkbox"]:checked')).map(cb => parseInt(cb.value, 10)).filter(n => n > 0);
        const productIds = Array.from(bulkProductList.querySelectorAll('input[type="checkbox"]:checked')).map(cb => parseInt(cb.value, 10)).filter(n => n > 0);
        if (!optionIds.length || !productIds.length) {
          bulkAssignMsg.textContent = 'Məhsul və option seçin';
          return;
        }
        const btn = document.getElementById('btnBulkAssign');
        if (btn) { btn.disabled = true; btn.textContent = 'Gözləyin...'; }
        try {
          const res = await fetch(filtersApi.bulkAssign, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ product_ids: productIds, filter_option_ids: optionIds })
          });
          let data;
          try {
            data = await res.json();
          } catch (err) {
            bulkAssignMsg.textContent = 'Server cavabı oxuna bilmədi';
            return;
          }
          if (!data.ok) {
            bulkAssignMsg.textContent = data.message || 'Xəta';
            if (btn) { btn.disabled = false; btn.textContent = 'Seçilənləri tətbiq et'; }
            return;
          }
          bulkAssignMsg.textContent = 'Əlavə edildi';
          // Refresh UI so chips reflect new links
          await loadFilters();
          renderCheckboxes(filterCheckboxesAdd, []);
          renderCheckboxes(filterCheckboxesEdit, editSelectedOptionIds);
          renderBulkOptions();
          renderBulkProducts();
        } catch (e) {
          console.error('Bulk assign failed', e);
          bulkAssignMsg.textContent = 'Xəta baş verdi';
        } finally {
          if (btn) { btn.disabled = false; btn.textContent = 'Seçilənləri tətbiq et'; }
        }
      });

      function applySearch(term) {
        const q = (term || '').toLowerCase();
        productCards.forEach((card) => {
          const name = (card.dataset.name || '').toLowerCase();
          const match = !q || name.includes(q);
          card.style.display = match ? '' : 'none';
        });

        // Recount visible after display changes (rely on offsetParent to avoid hidden elements)
        const visible = productCards.reduce((cnt, card) => card.offsetParent ? cnt + 1 : cnt, 0);

        let noRes = productGrid.querySelector('.no-results');
        if (!noRes) {
          noRes = document.createElement('div');
          noRes.className = 'card product-card no-results';
          noRes.style.gridColumn = '1 / -1';
          noRes.style.textAlign = 'center';
          noRes.textContent = 'Nəticə tapılmadı';
          productGrid.appendChild(noRes);
        }

        const shouldShow = productCards.length > 0 && visible === 0;
        noRes.style.display = shouldShow ? '' : 'none';
      }

      searchInput && searchInput.addEventListener('input', (e) => applySearch(e.target.value));
      applySearch('');

      window.closeAddProduct = function() {
        addModal.classList.remove('active');
      };

      openAddBtn && openAddBtn.addEventListener('click', (e) => {
        e.preventDefault();
        loadFilters();
        addModal.classList.add('active');
      });

      function syncInputFiles() {
        const dt = new DataTransfer();
        addSelectedFiles.forEach((f) => dt.items.add(f));
        addImages.files = dt.files;
      }

      function renderThumbs(files) {
        addThumbs.innerHTML = '';
        files.forEach((file, idx) => {
          const url = URL.createObjectURL(file);
          const box = document.createElement('div');
          box.className = 'thumb';

          const img = document.createElement('img');
          img.src = url;
          img.alt = file.name;

          const removeBtn = document.createElement('button');
          removeBtn.type = 'button';
          removeBtn.className = 'remove-thumb';
          removeBtn.textContent = '✕';
          removeBtn.addEventListener('click', () => {
            addSelectedFiles = addSelectedFiles.filter((_, i) => i !== idx);
            syncInputFiles();
            renderThumbs(addSelectedFiles);
          });

          box.appendChild(img);
          box.appendChild(removeBtn);
          addThumbs.appendChild(box);
        });
      }

      addImages && addImages.addEventListener('change', (e) => {
        addSelectedFiles = Array.from(e.target.files || []);
        renderThumbs(addSelectedFiles);
      });

      function initSlider(slider) {
        let images;
        try { images = JSON.parse(slider.dataset.images || '[]'); } catch(e) { images = []; }
        if (!images.length) return;

        // reset markup to avoid stacked listeners
        slider.innerHTML = "<img class=\"product-image\" src=\"\" alt=\"Product\" />" +
          "<div class=\"slider-arrows\"><button class=\"slider-prev\">‹</button><button class=\"slider-next\">›</button></div>" +
          "<div class=\"product-slider-dots\"></div>";

        const imgEl = slider.querySelector('.product-image');
        const dotsEl = slider.querySelector('.product-slider-dots');
        const prev = slider.querySelector('.slider-prev');
        const next = slider.querySelector('.slider-next');
        let current = 0;

        function renderDots() {
          dotsEl.innerHTML = '';
          images.forEach((_, idx) => {
            const dot = document.createElement('span');
            if (idx === current) dot.classList.add('active');
            dot.addEventListener('click', () => setSlide(idx));
            dotsEl.appendChild(dot);
          });
        }

        function setSlide(i) {
          current = (i + images.length) % images.length;
          imgEl.src = images[current];
          Array.from(dotsEl.children).forEach((dot, idx) => {
            dot.classList.toggle('active', idx === current);
          });
        }

        prev && prev.addEventListener('click', (e) => { e.preventDefault(); setSlide(current - 1); });
        next && next.addEventListener('click', (e) => { e.preventDefault(); setSlide(current + 1); });

        renderDots();
        setSlide(0);
      }

      document.querySelectorAll('.product-slider').forEach(initSlider);

      const editModal = document.getElementById('editProductModal');
      const editName = document.getElementById('editName');
      const editPrice = document.getElementById('editPrice');
      const editDesc = document.getElementById('editDesc');
      const editSlider = document.getElementById('editSlider');
      const editActive = document.getElementById('editActive');
      const editForm = document.getElementById('editProductForm');
      const editImages = document.getElementById('editImages');
      const editThumbs = document.getElementById('editThumbs');
      const editId = document.getElementById('editProductId');
      let editSelectedFiles = [];

      function syncEditFiles() {
        const dt = new DataTransfer();
        editSelectedFiles.forEach((f) => dt.items.add(f));
        editImages.files = dt.files;
      }

      function renderEditThumbs(files) {
        editThumbs.innerHTML = '';
        files.forEach((file, idx) => {
          const url = URL.createObjectURL(file);
          const box = document.createElement('div');
          box.className = 'thumb';

          const img = document.createElement('img');
          img.src = url;
          img.alt = file.name;

          const removeBtn = document.createElement('button');
          removeBtn.type = 'button';
          removeBtn.className = 'remove-thumb';
          removeBtn.textContent = '✕';
          removeBtn.addEventListener('click', () => {
            editSelectedFiles = editSelectedFiles.filter((_, i) => i !== idx);
            syncEditFiles();
            renderEditThumbs(editSelectedFiles);
          });

          box.appendChild(img);
          box.appendChild(removeBtn);
          editThumbs.appendChild(box);
        });
      }

      editImages && editImages.addEventListener('change', (e) => {
        editSelectedFiles = Array.from(e.target.files || []);
        renderEditThumbs(editSelectedFiles);
      });

      document.querySelectorAll('.product-card .edit-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          const card = btn.closest('.product-card');
          if (!card) return;
          const pid = card.dataset.id || card.getAttribute('data-id') || card.dataset.productId || '';
          if (!pid) return;
          fetch(filtersApi.productEdit + '?id=' + pid, { credentials: 'same-origin' })
            .then(r => r.json())
            .then(data => {
              if (!data.ok) return;
              const p = data.data.product;
              const selected = data.data.selected_option_ids || [];
              editSelectedOptionIds = selected.map(Number);
              filtersCache = translateFiltersClient(data.data.filters || filtersCache);
              // keep enabledFilterIds as currently selected; no change here
              editSelectedFiles = [];
              editId.value = p.id;
              editName.value = p.name || '';
              editPrice.value = p.price || '';
              editDesc.value = p.description || '';
              editActive.checked = (p.is_active ?? 1) == 1;
              editSlider.dataset.images = card.dataset.images || '[]';
              renderCheckboxes(filterCheckboxesEdit, editSelectedOptionIds);
              initSlider(editSlider);
              renderEditThumbs(editSelectedFiles);
              editModal.classList.add('active');
            })
            .catch(() => {});
        });
      });

      const deleteForm = document.getElementById('deleteProductForm');
      const deleteInput = document.getElementById('deleteProductId');
      document.querySelectorAll('.product-card .muted').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
          e.preventDefault();
          const card = btn.closest('.product-card');
          if (!card) return;
          const id = card.dataset.id || card.getAttribute('data-id') || card.dataset.productId || '';
          if (!id) return;
          if (!confirm('Məhsulu silmək istəyirsən?')) return;
          deleteInput.value = id;
          deleteForm.submit();
        });
      });
    })();
  </script>
</section>
