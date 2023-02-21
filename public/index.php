<?php require_once 'core/crud.php'; ?>
<?php
$db = new Database(); // $db->connect();
echo 'all done<br>'; /*
$db->sql("insert into users (email, passwd) values('ozzi@ukr.net','password');");
$db->sql('SELECT * FROM users');
$db->select('users');
$db->insert('users',array('email'=>'Nam','passwd'=>'pppppssssss'));
$db->delete('users','id=11');
*/
$db->update('users', ['email' => 'name4@email.com'], 'id=8');
$res = $db->getResult();
foreach ($res as $output) {
    // echo $output['email'] . '<br />';
    echo json_encode($output) . '<br/>';
}
$db = null;
 ?>
