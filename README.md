# QueryX LiteDatabase
เป็นฐานข้อมูลที่พื้นฐานในรูปแบบ NoSQL โดยมีชื่อว่า LiteDatabase ในขณะนี้อยู่ในช่วงพัฒนาเวอร์ชั่น 1 โดยในอนาคตจะถูกรวมเข้ากับ QueryX Framework อย่างแน่นอน
ในเวอร์ชั่นนี้จะยังไม่ถูกนำไปใช้เนื่องจากยังไม่มีระบบล็อกอินเพื่อดูฐานข้อมูล จึงไม่แนะนำให้นำไปใช้กับงานจริง แต่สามารถนำไปต่อยอดหรือศึกษาการใช้งานคร่าว ๆ ก่อนที่จะปล่อยเวอร์ชั่นหลักได้

### วิธีการใช้งานเบื้องต้น
```php
<?php
  use QueryX\Common\LiteDatabase;
  require_once __DIR__ . '/path/QueryxLite.php';
  
  $db = new LiteDatabase;
  $db->set('collectionDir', 'path/for/keep/collection/files/'); // ต้องลงท้ายด้วย "/"
  $db->set('configFile', 'path/for/keep/config/fileName'); // ไม่ต้องใส่นามสกุลไฟล์
?>
```
จากโค๊ดข้างบน `$db->set('collectionDir', 'path/for/keep/collection/files/')` หมายถึงโฟลเดอร์ที่จะทำการเก็บข้อมูลหรือใช้เก็บฐานข้อมูล
และ `$db->set('configFile', 'path/for/keep/config/fileName')` หมายถึงที่อยู่ของไฟล์ Username และ Password (ในขณะนี้ยังไม่มีฟังก์ชั่นเข้าสู่ระบบ สามารถข้ามไปได้)

### เมธอดต่างๆ
#### เมธอดสร้างไฟล์ Config | createConfig($username, $password)
ก่อนจะใช้งานเมธอดนี้ จำเป็นที่จะต้องกำหนดที่อยู่ของไฟล์โดยใช้เมธอด `$db->set('configFile', 'path')` เสียก่อน
```php
$db->createConfig('username', 'password');
```

#### เมธอดสร้าง Collection | createCollection($collectionName)
ก่อนจะใช้งานเมธอดนี้ จำเป็นที่จะต้องกำหนดที่อยู่ของคอลเล็คชั่นโดยใช้เมธอด `$db->set('collectionDir', 'path')` เสียก่อน
```php
$db->createCollection('collectionName');
```

#### เมธอดดูการตั้งค่าต่างๆ (ไม่รวม Username และ Password) | getConfig($encoded = true)
เมธอดนี้ถ้าหากกำหนด Argument เป็น true (ซึ่งเป็นค่าเริ่มต้น) จะส่งค่าที่ถูกการเข้ารหัสไว้ แต่ถ้าหากใส่เป็น false จะได้ออกมาเป็นค่า Array
```php
$db->getConfig(true or false);
```

#### เมธอดเข้าถึงคอลเล็คชั่น | collection($collectionName)
เป็นเมธอดที่จะเข้าถึงคอลเล็คชั่นต่างๆที่มีอยู่ในระบบ โดยจะคืนค่ามาเป็น Instance ของคลาสทำให้เราสามารถเรียกใช้เมธอดอื่นๆภายในคลาสต่อได้เลย
```php
$db->collection('collect')->otherMethods();
```

#### เมธอดลบคอลเล็คชั่นและด็อคคิวเมนท์ | deleteThis()
โดยเมธอด deleteThis() จะสามารถลบได้ทั้ง document และ collection ขึ้นอยู่กับการใช้งาน โดยให้ดูจากตัวอย่าง
```php
$db->collection('collect')->deleteThis(); // เป็นการลบคอลเล็คชั่น
$db->collection('collect')->document('doc')->deleteThis(); // เป็นการลบด็อคคิวเมนท์
```

#### เมธอดอ่านข้อมูลทั้งหมดในคอลเล็คชั่น | collection($collectionName)->readAll()
ก่อนที่จะใช้งานเมธอดนี้ จำเป็นที่จะต้องเข้าถึงคอลเล็คชั่นเสียก่อน โดยจะคืนค่าออกมาเป็น Array
```php
print_r($db->collection('collect')->readAll());
```

#### เมธอดเข้าถึงด็อคคิวเมนท์และเมธอดสำหรับแก้ไขด็อคคิวเมนท์ | collection($collectionName)->document($documentName)
เมื่อเราเข้าถึง document ได้แล้ว เมธอดจะคืนค่า Instance ของคลาสออกมาทำให้เราสามารถใช้งานฟังก์ชั่นที่เกี่ยวกับด็อคคิวเมนท์ได้ต่อทันที
```php
$db->collection('collect')->document('doc')->update(['age' => 8]); // แก้ไขข้อมูล
$db->collection('collect')->document('doc')->read(); // อ่านข้อมูล
```
