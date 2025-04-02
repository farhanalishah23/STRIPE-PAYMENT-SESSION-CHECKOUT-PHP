<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Cards with Stripe Payment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        h1 {
            text-align: center;
            margin-top: 20px;
            color: #333;
        }
        .container {
            width: 90%;
            margin: 20px auto;
        }
        .form-container {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .form-container label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #333;
        }
        .form-container input,
        .form-container button {
            width: calc(50% - 10px);
            margin: 5px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-container input[readonly] {
            background-color: #f0f0f0;
            cursor: not-allowed;
        }
        .form-container button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
            border: none;
        }
        .form-container button:hover {
            background-color: #218838;
        }
        .product-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .product-card {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .product-card h3 {
            margin: 10px 0;
            color: #333;
        }
        .product-card p {
            margin: 0 0 10px;
            color: #555;
        }
        .summary {
            background: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
            text-align: center;
        }
        .summary h3 {
            margin: 10px 0;
            color: #333;
        }
        .pay-button {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            font-size: 18px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .pay-button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Product Cards with Stripe Payment</h1>
    <div class="container">
        <div class="form-container">
            <label>Product Name</label>
            <input type="text" id="productName" placeholder="Product Name" />
            <label>Product Price</label>
            <input type="number" value="10" id="productPrice" readonly />
            <button type="button" id="addProductBtn">Add Product</button>
        </div>

        <div class="product-container" id="productContainer"></div>

        <div class="summary">
            <form method="post" action="checkoutapi.php">
                <h3>Total Products: <span id="totalProducts">0</span></h3>
                <h3>Total Cost: $<span id="totalCost">0</span></h3>

                <!-- Hidden fields to send product data -->
                <div id="hiddenFields"></div>

                <input type="hidden" name="totalcost" id="hiddenTotalCost">
                <input type="hidden" name="totalProducts" id="hiddenTotalProducts">

                <button type="submit" class="pay-button">Pay with Stripe</button>
            </form>
        </div>
    </div>

    <script>
        const addProductBtn = document.getElementById('addProductBtn');
        const productNameInput = document.getElementById('productName');
        const productPriceInput = document.getElementById('productPrice');
        const productContainer = document.getElementById('productContainer');
        const totalProductsElement = document.getElementById('totalProducts');
        const totalCostElement = document.getElementById('totalCost');
        const hiddenFields = document.getElementById('hiddenFields');
        const hiddenTotalCost = document.getElementById('hiddenTotalCost');
        const hiddenTotalProducts = document.getElementById('hiddenTotalProducts');

        let totalProducts = 0;
        let totalCost = 0;

        addProductBtn.addEventListener('click', () => {
            const name = productNameInput.value.trim();
            const price = parseFloat(productPriceInput.value); 

            if (name) {
                const productCard = document.createElement('div');
                productCard.className = 'product-card';
                productCard.innerHTML = `
                    <h3>${name}</h3>
                    <p>Price: $${price.toFixed(2)}</p>
                `;
                productContainer.appendChild(productCard);

                // Add hidden fields with product name and price
                const hiddenProductName = document.createElement('input');
                hiddenProductName.type = 'hidden';
                hiddenProductName.name = 'product_names[]';
                hiddenProductName.value = name;
                hiddenFields.appendChild(hiddenProductName);

                const hiddenProductPrice = document.createElement('input');
                hiddenProductPrice.type = 'hidden';
                hiddenProductPrice.name = 'product_prices[]';
                hiddenProductPrice.value = price;
                hiddenFields.appendChild(hiddenProductPrice);

                totalProducts++;
                totalCost += price;

                // Update the total products and cost in the summary
                updateSummary();

                // Reset the product name input field
                productNameInput.value = '';
            } else {
                alert('Please enter the product name.');
            }
        });

        function updateSummary() {
            totalProductsElement.textContent = totalProducts;
            totalCostElement.textContent = totalCost.toFixed(2);
            hiddenTotalCost.value = totalCost.toFixed(2);
            hiddenTotalProducts.value = totalProducts;
        }
    </script>
</body>

</html>
