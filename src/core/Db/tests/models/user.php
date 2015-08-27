<?php

/**
 * To make IDEs autocomplete happy
 *
 * @property int id
 * @property string login
 * @property bool active
 * @property string customerId
 * @property string firstName
 * @property string lastName
 * @property string password
 * @property string createdAt
 * @property string updatedAt
 * @property string expires
 * @property int loginCount
 */
class user extends core\Db\dbObject {
    protected $dbTable = 'users';
    protected $dbFields = [
        'login' => ['text', 'required'],
        'active' => ['bool'],
        'customerId' => ['int'],
        'firstName' => ['/[a-zA-Z0-9 ]+/'],
        'lastName' => ['text'],
        'password' => ['text'],
        'createdAt' => ['datetime'],
        'updatedAt' => ['datetime'],
        'expires' => ['datetime'],
        'loginCount' => ['int']
    ];

    protected $timestamps = ['createdAt', 'updatedAt'];
    protected $relations = [
        'products' => ['hasMany', 'product', 'userid']
    ];
}