<?php

namespace App\Http\Controllers\Api;

use DB;

session_start();

class CartController extends Controller
{
    public function addToCart($id)
    {
        $product = DB::table('products')->select(['id', 'name'])->find($id);
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
            } elseif (($id == $value['id'] && $type === 'one' && $_SESSION['cart'][$key]['quantity'] === 1) || ($id === $value['id'] && $type === 'all')) {
                unset($_SESSION['cart'][$key]);
            }else{
              return sendMessage('Please try again !');
            }
        }
        return true;
    }
}
