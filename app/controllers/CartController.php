<?php

namespace App\Controllers;

use App\Main\Controller;
use App\Main\QueryBuilder as DB;

session_start();

class CartController extends Controller
{
    private $cart = [];

    public function __construct()
    {

    }

    public function addToCart($id)
    {
        $product = DB::table('products')->select(['id', 'name'])->find('id', $id);
        if (!$product) {
            return sendMessage('Product not found !');
        }
        $flag = false;
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($id == $value['id']) {
                $_SESSION['cart'][$key]['quantity'] += 1;
                $flag = true;
            }
        }
        if ($flag === false) {
            $_SESSION['cart'][] = [
                'id' => $id,
                'product' => json_encode($product),
                'quantity' => 1,
            ];
        }
        print_r($_SESSION['cart']);
        return true;
    }

    public function getCart()
    {
        if (!isset($_SESSION['cart'])) {
            return null;
        }
        return $_SESSION['cart'];
    }

    public function removeCart($id, $type = 'one')
    {
        if (!isset($_SESSION['cart'])) {
            return sendMessage('Got error !');
        }
        foreach ($_SESSION['cart'] as $key => $value) {
            if ($id == $value['id'] && $type === 'one' && $_SESSION['cart'][$key]['quantity'] > 1) {
                $_SESSION['cart'][$key]['quantity'] -= 1;
            } elseif (($id == $value['id'] && $type === 'one' && $_SESSION['cart'][$key]['quantity'] == 1) || ($id === $value['id'] && $type === 'all')) {
                unset($_SESSION['cart'][$key]);
            }
        }
        print_r($_SESSION['cart']);
        return true;
    }
}
