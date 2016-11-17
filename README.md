# Simplepushio

Basic interface for https://simplepush.io/ API

```php
<?php
$Simplepush = new Simplepushio\Api('mykey');

try {
    $Simplepush->sendRequest(['msg' => 'Test']);
    echo "Sent!";
} catch (Exception $e) {
    echo $e->getMessage();
}
```
