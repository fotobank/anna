<?php
error_reporting( E_ALL );
include(__DIR__ . "/Db.php");

$db = new Db( Db::getParam() );
if ( ! $db ) die( "Database error" );

$prefix = 't_';
$db->setPrefix( $prefix );

$tables = [
		'users'    => [
				'login'      => 'char(10) not null',
				'active'     => 'bool default 0',
				'customerId' => 'int(10) not null',
				'firstName'  => 'char(10) not null',
				'lastName'   => 'char(10)',
				'password'   => 'text not null',
				'createdAt'  => 'datetime',
				'expires'    => 'datetime',
				'loginCount' => 'int(10) default 0'
		],
		'products' => [
				'customerId'  => 'int(10) not null',
				'userId'      => 'int(10) not null',
				'productName' => 'char(50)'
		]
];
$data = [
		'users'    => [
				[ 'login'      => 'user1',
							 'customerId' => 10,
							 'firstName'  => 'John',
							 'lastName'   => 'Doe',
							 'password'   => $db->func( 'SHA1(?)', [ "secretpassword+salt" ] ),
							 'createdAt'  => $db->now(),
							 'expires'    => $db->now( '+1Y' ),
							 'loginCount' => $db->inc()
				],
				[ 'login'      => 'user2',
							 'customerId' => 10,
							 'firstName'  => 'Mike',
							 'lastName'   => NULL,
							 'password'   => $db->func( 'SHA1(?)', [ "secretpassword2+salt" ] ),
							 'createdAt'  => $db->now(),
							 'expires'    => $db->now( '+1Y' ),
							 'loginCount' => $db->inc( 2 )
				],
				[ 'login'      => 'user3',
							 'active'     => true,
							 'customerId' => 11,
							 'firstName'  => 'Pete',
							 'lastName'   => 'D',
							 'password'   => $db->func( 'SHA1(?)', [ "secretpassword2+salt" ] ),
							 'createdAt'  => $db->now(),
							 'expires'    => $db->now( '+1Y' ),
							 'loginCount' => $db->inc( 3 )
				]
		],
		'products' => [
				[ 'customerId'  => 1,
							 'userId'      => 1,
							 'productName' => 'product1',
				],
				[ 'customerId'  => 1,
							 'userId'      => 1,
							 'productName' => 'product2',
				],
				[ 'customerId'  => 1,
							 'userId'      => 1,
							 'productName' => 'product3',
				],
				[ 'customerId'  => 1,
							 'userId'      => 2,
							 'productName' => 'product4',
				],
				[ 'customerId'  => 1,
							 'userId'      => 2,
							 'productName' => 'product5',
				],

		]
];

/**
 * @param $name
 * @param $data
 */
function createTable( $name, $data ) {
	global $db;
	//$q = "CREATE TABLE $name (id INT(9) UNSIGNED PRIMARY KEY NOT NULL";
	$q = "CREATE TABLE $name (id INT(9) UNSIGNED PRIMARY KEY AUTO_INCREMENT";
	foreach ( $data as $k => $v ) {
		$q .= ", $k $v";
	}
	$q .= ")";
	$db->rawQuery( $q );
}

foreach ( $tables as $name => $fields ) {
	$db->rawQuery( "DROP TABLE " . $prefix . $name );
	createTable( $prefix . $name, $fields );
}


foreach ( $data as $name => $datas ) {
	foreach ( $datas as $d ) {
		$id = $db->insert( $name, $d );
		if ( $id )
			$d['id'] = $id;
		else {
			echo "failed to insert: " . $db->getLastQuery() . "\n" . $db->getLastError();
		}
	}
}

$db->orderBy( "id", "asc" );
$users = $db->get( "users" );
if ( $db->count != 3 ) {
	echo "Invalid total insert count";
	exit;
}
$db->where( "active", true );
$users = $db->get( "users" );
if ( $db->count != 1 ) {
	echo "Invalid total insert count with boolean";
	exit;
}

$db->where( "active", false );
$db->update( "users", [ "active" => $db->not() ] );

$db->where( "active", true );
$users = $db->get( "users" );
if ( $db->count != 3 ) {
	echo "Invalid total insert count with boolean";
	exit;
}

// TODO
//$db->where("createdAt", Array (">" => $db->interval("-1h")));
//$users = $db->get("users");
//print_r ($users);

$db->where( "firstname", '%John%', 'LIKE' );
$users = $db->get( "users" );
if ( $db->count != 1 ) {
	echo "Invalid insert count in LIKE: " . $db->count;
	print_r( $users );
	echo $db->getLastQuery();
	exit;
}

$db->groupBy( "customerId" );
$cnt = $db->get( "users", null, "customerId, count(id) as cnt" );
if ( $db->count != 2 ) {
	echo "Invalid records count with group by";
}


$upData = [
		'expires'    => $db->now( "+5M", "expires" ),
		'loginCount' => $db->inc()
];
$db->where( "id", 1 );
$cnt = $db->update( "users", $upData );

$db->where( "id", 1 );
$r = $db->getOne( "users" );
if ( $db->count != 1 ) {
	echo "Invalid users count on getOne()";
	exit;
}
if ( $r['password'] != '546f98b24edfdc3b9bbe0d241bd8b29783f71b32' ) {
	echo "Invalid password were set" .
			exit;
}

$db->where( "id", [ '1', '2', '3' ], 'IN' );
$db->get( "users" );
if ( $db->count != 3 ) {
	echo "Invalid users count on where() with in ";
	exit;
}

$db->where( "id", [ '2', '3' ], 'between' );
$db->get( "users" );
if ( $db->count != 2 ) {
	echo "Invalid users count on where() with between";
	exit;
}

$db->where( "id", 2 );
$db->orWhere( "customerId", 11 );
$r = $db->get( "users" );
if ( $db->count != 2 ) {
	echo "Invalid users count on orWhere()";
	exit;
}

$db->where( "lastName", NULL, '<=>' );
$r = $db->get( "users" );
if ( $db->count != 1 ) {
	echo "Invalid users count on null where()";
	exit;
}

$db->join( "users u", "p.userId=u.id", "LEFT" );
$db->where( "u.login", 'user2' );
$db->orderBy( "CONCAT(u.login, u.firstName)" );
$products = $db->get( "products p", null, "u.login, p.productName" );
if ( $db->count != 2 ) {
	echo "Invalid products count on join ()";
	exit;
}

$db->where( "id = ? or id = ?", [ 1, 2 ] );
$res = $db->get( "users" );
if ( $db->count != 2 ) {
	echo "Invalid users count on select with multiple params";
	exit;
}

$db->where( "id = 1 or id = 2" );
$res = $db->get( "users" );
if ( $db->count != 2 ) {
	echo "Invalid users count on select with multiple params";
	exit;
}

/*$usersQ = $db->subQuery();
$usersQ->where( "login", "user2" );
$usersQ->getOne( "users", "id" );

$db2 = $db->copy();
$db2->where( "userId", $usersQ );
$res = $db2->getOne( "products", "count(id) as cnt" );  // проблемная строка
if ( $res['cnt'] != 2 ) {
	echo "Invalid select result with subquery";
	exit;
}*/
//TODO: insert test
$db->delete( "users" );
$db->get( "users" );
if ( $db->count != 0 ) {
	echo "Invalid users count after delete";
	exit;
}
$db->delete( "products" );
echo "All done";

// print_r($db->rawQuery("CALL simpleproc(?)", [ "test" ] ));