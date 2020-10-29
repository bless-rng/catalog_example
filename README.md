# catalog_example

Написать приложение на Symfony. 
Реализовать простейшую структуру хранения товаров в древовидном каталоге, объяснить причину выбора дерева. 

Сущности (ниже представлены, только видимые пользователю поля, структура данных может отличаться)
```
Категория - имеет вложенные категории
  Название (255 символов)
  Родительская категория
  Минимальная цена товара в данной категории
```
```
Товар - принадлежит категории
  Название (255 символов)
  Цена
  Категория
```
Необходимо:
Реализовать минимально и быстро.

Опционально:
API - в данном случае, это реализация нескольких методов для добавления категории и товара, формат передачи JSON.
Условие добавления товара в категорию
При добавлении товара к категории, нужно производить проверку на минимальную цену в указанной категории. 
При добавлении товара слать E-mail на N заданных адресов и писать в лог

```
docker-compose up -d --build

docker-compose exec app php bin/console d:m:m
```
