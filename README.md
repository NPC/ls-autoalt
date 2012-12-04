# AutoAlt

Плагин для Livestreet (1.0.1)

Добавляет название блога топика к ALT-атрибуту изображений топиков (при сохранении топика).

Работает по следующему сценарию:
- Если alt-а нет или он пустой, то создаёт его со значением "Название блога: Название топика".
- Если alt есть и не содержит название блога, то добавляет "Название блога: Существующий ALT".
Иначе alt не изменяется.

В config/config.php можно отключить функцию добавления блога.

Этот плагин не влияет на фотосеты.

---

AutoAlt
Plugin for Livestreet (1.0.1)

Adds blog name to the ALT-attribute of images of topics (applied when saving the topic).

Follows these scenarios:
- If there is no ALT or it is empty, then it is created with the pattern "Blog name: Topic title".
- If the ALT is present and does not contain blog name, then adds "Blog name: Existing ALT".
Otherwise the ALT is not modified.

It is possible to diable adding blog name in the config/config.php file.

Photosets are not affected by this plugin.