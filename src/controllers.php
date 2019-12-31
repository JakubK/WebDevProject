<?php
require_once 'business.php';
require_once 'controller_utils.php';


function products(&$model)
{
    $products = get_products();
    $model['products'] = $products;

    return 'products_view';
}

function product(&$model)
{
    if (!empty($_GET['id'])) {
        $id = $_GET['id'];

        if ($product = get_product($id)) {
            $model['product'] = $product;
            return 'product_view';
        }
    }

    http_response_code(404);
    exit;
}

function edit(&$model)
{
    $product = [
        'name' => null,
        'price' => null,
        'description' => null,
        '_id' => null
    ];

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (!empty($_POST['name']) &&
            !empty($_POST['price']) /* && ...*/
        ) {
            $id = isset($_POST['id']) ? $_POST['id'] : null;

            $product = [
                'name' => $_POST['name'],
                'price' => (int)$_POST['price'],
                'description' => $_POST['description']
            ];

            if (save_product($id, $product)) {
                return 'redirect:products';
            }
        }
    } elseif (!empty($_GET['id'])) {
        $product = get_product($_GET['id']);
    }

    $model['product'] = $product;

    return 'edit_view';
}

function delete(&$model)
{
    if (!empty($_REQUEST['id'])) {
        $id = $_REQUEST['id'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            delete_product($id);
            return 'redirect:products';

        } else {
            if ($product = get_product($id)) {
                $model['product'] = $product;
                return 'delete_view';
            }
        }
    }

    http_response_code(404);
    exit;
}

function cart(&$model)
{
    $model['cart'] = get_cart();
    return 'partial/cart_view';
}

function add_to_cart()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $product = get_product($id);

        $cart = &get_cart();
        $amount = isset($cart[$id]) ? $cart[$id]['amount'] + 1 : 1;

        $cart[$id] = ['name' => $product['name'], 'amount' => $amount];

        return 'redirect:' . $_SERVER['HTTP_REFERER'];
    }
}

function clear_cart()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $_SESSION['cart'] = [];
        return 'redirect:' . $_SERVER['HTTP_REFERER'];
    }
}

function gallery(&$model)
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        if($_POST['type'] === 'upload')
        {
            $upload_result = upload_image();
            $_SESSION['uploadInfo'] = $upload_result;
        }
        else
            markImages(true);

        return 'redirect:' . $_SERVER['HTTP_REFERER'];
    }
    else
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = 5;
        $skip = ($page-1) * $itemsPerPage;

        $result = get_image_data($skip,$itemsPerPage,$maxPage);

        $model['page'] = $page;
        $model['images'] = $result ?? [];
        $model['maxPage'] = $maxPage;

        $model['uploadInfo'] = $_SESSION['uploadInfo'];
        $_SESSION['uploadInfo'] = '';

        return 'gallery';
    }
}

function marked_gallery(&$model)
{
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        markImages(false);
        return 'redirect:' . $_SERVER['HTTP_REFERER'];
    }
    else
    {
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $itemsPerPage = 5;
        $skip = ($page-1) * $itemsPerPage;

        $result = get_marked_image_data($skip,$itemsPerPage,$maxPage);

        $model['page'] = $page;
        $model['images'] = $result ?? [];
        $model['maxPage'] = $maxPage;

        return "marked_gallery";
    }
}

function register_user(&$model)
{
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $register_result = processRegisterForm();
        $_SESSION['registerResult'] = $register_result;
        return 'redirect: '.$_SERVER['HTTP_REFERER'];
    }
    else 
    {
        $model['registerResult'] = $_SESSION['registerResult'];
        $_SESSION['registerResult'] = '';
        return 'register';
    }
}

function login_user(&$model)
{
    if($_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $userId = NULL;
        $loginResult = processLoginForm($userId);
        $_SESSION['user'] = $userId;
        $_SESSION['loginResult'] = $loginResult;
        return 'redirect: '.$_SERVER['HTTP_REFERER'];
    }
    else 
    {
        $model['loginResult'] = $_SESSION['loginResult'];
        $_SESSION['loginResult'] = '';
        return 'login';
    }
}

function logout()
{
    $_SESSION['user'] = NULL;
    return "login";
}

function home(&$model)
{
    return 'home';
}

function contact(&$model)
{
    return 'contact';
}