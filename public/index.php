<?php require_once 'core/crud.php'; ?>
<?php
$db = new Database(); /*
$db->sql('SELECT * FROM users');
$db->sql("insert into users (email, passwd) values('ozi@ukr.net','password');");
$db->select('users');
$db->insert('users',array('email'=>'Nam','passwd'=>'pppppssssss'));
$db->delete('users','id=11');
$db->sql( 'SELECT * FROM users'); // $db->sql( 'DESCRIBE users'); // $db->update('users', ['email' => 'name4@email.com'], 'id=8');
$db->update('users',array('email'=>"name@email.com",'passwd'=>"com"),'id="83"');
*/ 
$db->update('users',array('passwd'=>"com"),'id>"81"');
$res = $db->getResult();
echo $db->numRows() . '<br/>';
foreach ($res as $output) {
    echo json_encode($output);
}
 ?>
