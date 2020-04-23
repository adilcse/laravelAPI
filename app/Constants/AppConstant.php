<?php
namespace App\Constants\AppConstant;
class AppConstant
{
    public static $LOGIN_URL='login';
    public static $REGISTER_URL='register';
    public static $USER_ADD_TO_CART_URL='addToCart';
    public static $USER_LACE_ORDER_URL='placeOrder';
    public static $GET_ORDER_URL='getOrders/{per_page}';
    public static $UPDATE_USER_ADDRESS_URL='updateAddress';
    public static $REMOVE_FROM_USER_CART_URL='removeFromCart/{id}/delete';
    public static $UPDATE_USER_CART_URL='updateCart';
    public static $GET_NEARBY_SELLER_URL='nearbySellers';
    public static $GET_CATAGORY='getCatagory';
    public static $GET_SELLER_ITEMS='getItems';
    public static $SELLER_ADD_ITEM='addItem';
    public static $SELLER_UPDATE_ITEM='updateItem/{id}';
    public static $SELLER_ACCEPT_REJECT_ORDER='orderAcceptReject/{id}';
    public static $SELLER_UPDATE_STATUS='updateOrderStatus/{id}';
    public static $SELLER_DELETE_ITEMS='deleteItems';
    public static $SELLER_GET_ORDER='getOrders/{perPage}';
}