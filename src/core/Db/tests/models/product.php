<?php

/**
 * To make IDEs autocomplete happy
 *
 * @property int id
 * @property int userid
 * @property int customerId
 * @property string productName
 */
class product extends core\Db\dbObject {
    protected $dbTable = 'products';
    protected $primaryKey = 'id';
    protected $dbFields = [
        'userId' => ['int', 'required'],
        'customerId' => ['int', 'required'],
        'productName' => ['text','required']
    ];
    protected $relations = [
        'userId' => ['hasOne', 'user'],
        'user' => ['hasOne', 'user', 'userId']
    ];

    public function last () {
        $this->where ('id' , 130, '>');
        return $this;
    }
}