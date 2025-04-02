<?php
require __DIR__ . '/vendor/autoload.php';

$stripe_secret_key = "sk_test_51Qj325FQ6pwlpK5Qa6OT8E2C20UDyhps7E3uIkJwtmCCiqMOljXhJBzSBMCxOLkK9r98zpFBb30nmwxmIxN17Acn00ptOCTpXH";


if (isset($_POST['product_names']) && isset($_POST['product_prices'])) {
    $product_names = $_POST['product_names'];
    $product_prices = $_POST['product_prices'];

    \Stripe\Stripe::setApiKey($stripe_secret_key); 

    $line_items = [];

    foreach ($product_names as $index => $name) {
        $price = $product_prices[$index] * 100;  

        $line_items[] = [
            "quantity" => 1,
            "price_data" => [
                "currency" => "usd",
                "unit_amount" => $price,
                "product_data" => [
                    "name" => $name
                ]
            ]
        ];
    }

    try {
        $checkout_session = \Stripe\Checkout\Session::create([
            "mode" => "payment",  
            "locale"=>"sv",
            "success_url" => "https://localhost/stripe/success.php",
            "cancel_url" => "https://localhost/stripe/payment.php",
            "line_items" => $line_items
        ]);

        http_response_code(303);
        header("Location:" . $checkout_session->url);
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Error: Missing product data.";
}
?>
