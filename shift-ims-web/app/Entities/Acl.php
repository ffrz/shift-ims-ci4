<?php

namespace App\Entities;

class Acl
{
    const CHANGE_SYSTEM_SETTINGS = '001';

    const VIEW_USER_GROUPS  = '011';
    const VIEW_USER_GROUP   = '012';
    const ADD_USER_GROUP    = '013';
    const EDIT_USER_GROUP   = '014';
    const DELETE_USER_GROUP = '015';

    const VIEW_USERS  = '021';
    const VIEW_USER   = '022';
    const ADD_USER    = '023';
    const EDIT_USER   = '024';
    const DELETE_USER = '025';

    const VIEW_PURCHASE_ORDERS  = '101';
    const VIEW_PURCHASE_ORDER   = '102';
    const ADD_PURCHASE_ORDER    = '103';
    const EDIT_PURCHASE_ORDER   = '104';
    const DELETE_PURCHASE_ORDER = '105';

    const VIEW_SUPPLIERS  = '111';
    const VIEW_SUPPLIER   = '112';
    const ADD_SUPPLIER    = '113';
    const EDIT_SUPPLIER   = '114';
    const DELETE_SUPPLIER = '115';

    const MANAGE_INVENTORY = '200';

    const VIEW_PRODUCT_CATEGORIES = '201';
    const VIEW_PRODUCT_CATEGORY     = '202';
    const ADD_PRODUCT_CATEGORY      = '203';
    const EDIT_PRODUCT_CATEGORY     = '204';
    const DELETE_PRODUCT_CATEGORY   = '205';

    const VIEW_PRODUCTS    = '211';
    const VIEW_PRODUCT     = '212';
    const ADD_PRODUCT      = '213';
    const EDIT_PRODUCT     = '214';
    const DELETE_PRODUCT   = '215';

    const VIEW_REPORTS = '901';

    protected static $_resources = [
        self::CHANGE_SYSTEM_SETTINGS,

        self::VIEW_USER_GROUPS,
        self::VIEW_USER_GROUP,
        self::ADD_USER_GROUP,
        self::EDIT_USER_GROUP,
        self::DELETE_USER_GROUP,

        self::VIEW_USERS,
        self::VIEW_USER,
        self::ADD_USER,
        self::EDIT_USER,
        self::DELETE_USER,

        self::VIEW_PURCHASE_ORDERS,
        self::VIEW_PURCHASE_ORDER,
        self::ADD_PURCHASE_ORDER,
        self::EDIT_PURCHASE_ORDER,
        self::DELETE_PURCHASE_ORDER,

        self::VIEW_SUPPLIERS,
        self::VIEW_SUPPLIER,
        self::ADD_SUPPLIER,
        self::EDIT_SUPPLIER,
        self::DELETE_SUPPLIER,

        self::MANAGE_INVENTORY,

        self::VIEW_PRODUCT_CATEGORIES,
        self::VIEW_PRODUCT_CATEGORY,
        self::ADD_PRODUCT_CATEGORY,
        self::EDIT_PRODUCT_CATEGORY,
        self::DELETE_PRODUCT_CATEGORY,

        self::VIEW_PRODUCTS,
        self::VIEW_PRODUCT,
        self::ADD_PRODUCT,
        self::EDIT_PRODUCT,
        self::DELETE_PRODUCT,

        self::VIEW_REPORTS,
    ];

    /**
     * @return array
     */
    public static function getResources()
    {
        return static::$_resources;
    }

    /**
     * @return array
     */
    public static function createResources()
    {
        $result = [];
        foreach (static::$_resources as $resource) {
            $result[$resource] = 0;
        }
        return $result;
    }
}