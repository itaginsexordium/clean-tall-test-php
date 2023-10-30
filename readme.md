
Технические задания


Предложить оптимальное по скорости решение для выбора наиболее подходящей сети
для IP-адресов как IPv4 так и IPv6.
a. Создать структуру таблицы/таблиц MySQL для хранения данных по IP-сетям -
сеть, маска, код страны.
b. Создать SQL-запрос(ы) для выборки сети с наименьшей маской для заданного
IP-адреса (IPv4 и IPv6).

Написать на Go и PHP сервисы для вывода данных по стране принадлежности.

Мой вариант предоставлен только для IPv4 в данный момент из за технических проблем. 

Структура таблиц  : 
`CREATE TABLE `ip_data_v4` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip_address` varchar(15) DEFAULT NULL,
  `subnet_mask` int DEFAULT NULL,
  `geoname_id` int DEFAULT NULL,
  `registered_country_geoname_id` int DEFAULT NULL,
  `represented_country_geoname_id` int DEFAULT NULL,
  `is_anonymous_proxy` tinyint DEFAULT NULL,
  `is_satellite_provider` tinyint DEFAULT NULL,
  `network_as_number` bigint unsigned GENERATED ALWAYS AS (inet_aton(`ip_address`)) STORED,
  PRIMARY KEY (`id`),
  KEY `ip_data_v4_network_as_number_IDX` (`network_as_number`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=46201 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
`

таблица для стран и регионов 
`CREATE TABLE `geonames` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `geoname_id` int NOT NULL,
  `locale_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `continent_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `continent_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_iso_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_in_european_union` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `geonames_geoname_id_index` (`geoname_id`),
  KEY `geonames_country_iso_code_index` (`country_iso_code`)
) ENGINE=InnoDB AUTO_INCREMENT=505 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;`

А теперь собстно вытаскивание данных из  базы данных 

`SELECT DISTINCT  idv.ip_address, idv.subnet_mask, g.country_name AS country
            FROM ip_data_v4 AS idv 
            JOIN geonames AS g ON idv.geoname_id = g.geoname_id
            WHERE idv.network_as_number <= INET_ATON('{{ ТУТ ВСТАВИТЬ IP АДРЕС   }}') 
            ORDER BY idv.subnet_mask ASC
            LIMIT 50;`

Мой вариант выборки из базы данных является оптимальным по 1  причине в том что у нас вычисляется атрибут целого числа по ip он всегда уникальный и при запросе на выборку он по индексу быстро отрабатывает  и  сортировка по возрастанию т.е от малого к большему .



В данный момент приложение на PHP работает в очень специфичной среде из за утраты моего generic проекта и не может быть развёрнуто .


