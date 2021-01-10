# EX-MDB - PHP

*Install*
```
composer install
```

*Running tests*
```
composer test
```

---

**Create Database**

```php
$db = new Database("my-db");
```

**Add data**

```add(object $data): $this```

```php
$db->add([
    "name" => "foo",
])->add([
    "name" => "bar"
])->add([
    "name" => "span"
])->add([
    "name" => "fuzz"
]);
```

**Get all data**

```getAll(): {data: Array}```

```php
$allData = $db->getAll();
```

**Query**

```query(condition(object $item): boolean): object```

```php
$result1 = $db->query(fn($item) => $item->name === "foo");
$result2 = $db->query(fn($item) => $item->name === "bar");
$result3 = $db->query(fn($item) => $item->name === "span");
```

**Update**

```update(string $id, object $newValue): boolean```

```php
$db->update(2, ["name" => "UPDATED-SPAN"]);
```

**Delete**

```delete(string $id): boolean```
```
$db->delete("1")
```
