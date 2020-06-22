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
