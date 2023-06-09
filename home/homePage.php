<!DOCTYPE html>
<html>
<style>
  .none{
    display: none;
  }
  .display{
    display: block;
  }
  img{
    border-radius: 20px;
  }
  img:hover{
    cursor: pointer;
    opacity: 80%;
    scale: 0.95;
    transition: 1s all;
  }
</style>
<body>
<?php
  require_once '../header.html';
?>

<form id="search-form">
<!--  <input type="text" name="search" placeholder="Search products...">-->
  <input type="text" id="search-input" name="search" class="form-control w-25 mt-5 mb-2 ml-2" placeholder="Search products...">
  <button type="submit" class="btn btn-primary ml-2">Search</button>
  <div></div>
</form>
<div class="container mt-4">
  <!--      <div class="container">-->
  <div class="row">
    <div class="col-9"  id="search-results">
      <?php
      include("home.php");
      ?>
    </div>
    <form class="form-group col-3 border border-dark rounded py-3" method="post">
      <div id="noItem" class="alert alert-warning text-center">
        <h4>No Products ordered!</h4>
        <p>Click in the products you want to order</p>
      </div>
      <div id="ordersForm" class="none">
        <div>
          Orders
          <div id="addedOrders"></div>
        </div>
        <div><br>
          <label for="notes">Notes: </label><br>
          <textarea class="form-control" id="notes" name="notes"></textarea>
        </div><br>
        <label for="room">Room: </label>
        <select name="room" class="form-control" required>
          <option disabled selected value="">Room No.</option>
          <option value="1">1</option>
          <option value="2">2</option>
          <option value="3">3</option>
          <option value="4">4</option>
          <option value="5">5</option>
        </select><br>

        <?php if ($showAddToAdmin): ?>
        <label for="user-id">Add to user:</label><br>
        <select class="form-control" name="user-id">
          <option disabled selected value="">username</option>
          <?php
          foreach ($users as $user) {
            echo '<option value="' . $user['id'] . '">' . $user['name'] . '</option>';
          }
          echo '</select>';
          ?>
          <?php endif; ?>

          <hr>

          <input type="hidden" id="productIDs" name="productIDs">
          <input type="hidden" name="product_quantity[]">
          <p id="totalPrice">aa</p>
          <button class="btn btn-success float-right">Confirm</button>
      </div>
    </form>
  </div>
  <!--      </div>-->
</div>
<?php
require_once '../footer.html';
?>

<script>
  document.addEventListener('DOMContentLoaded', function() {
    let productImgs = document.querySelectorAll('.product-img');
    let productName = document.querySelectorAll('.product-name');
    let addedOrders = document.getElementById('addedOrders');
    let priceOfProduct = document.querySelectorAll('.price');
    let ordersForm = document.getElementById('ordersForm');
    let noItem = document.getElementById('noItem');
    let productNameCounts = {};
    let productIDs = [];
    let totalPrice = 0;

    // Initialize the total price to 0
    for (let i = 0; i < productImgs.length; i++) {
      productImgs[i].addEventListener('click', function() {
        noItem.classList.add('none');
        ordersForm.classList.add('display');

        let productID = this.getAttribute('data-product-id');
        productIDs.push(productID);
        productIDs = productIDs.map(function(x){ return parseInt(x); });
        document.getElementById('productIDs').value = JSON.stringify(productIDs);
        console.log(productIDs);

        let productNameText = productName[i].innerText;
        let price = parseFloat(priceOfProduct[i].innerText);

        if (productNameCounts[productNameText]) {
          productNameCounts[productNameText]++;
        } else {
          productNameCounts[productNameText] = 1;
        }

        totalPrice += price;

        // Add the price of the added product to the total price
        addedOrders.innerHTML = '';

        for (let productNameText in productNameCounts) {
          let p = document.createElement('p');
          p.textContent = productNameText;

          let count = productNameCounts[productNameText];

          let countSpan = document.createElement('span');
          countSpan.setAttribute('name', 'quantity');
          countSpan.setAttribute('value', count);
          countSpan.textContent = " " + count;

          let product_price = document.createElement('span');
          product_price.classList.add('price'); // Add the 'price' class

          let productPriceText = parseFloat(priceOfProduct[i-1].innerText); // Retrieve the correct price for each product
          console.log(productPriceText);
          product_price.textContent = (productPriceText * count).toFixed(2) + ' EGP';

          let plusButton = document.createElement('button');
          plusButton.textContent = '+';
          plusButton.classList.add('btn', 'btn-sm', 'btn-secondary', 'mx-1');
          plusButton.addEventListener('click', function() {
            productNameCounts[productNameText]++;
            countSpan.textContent = productNameCounts[productNameText];
            totalPrice += price;
            document.getElementById('totalPrice').textContent = 'Total: ' + totalPrice.toFixed(2) +' EGP';
            product_price.textContent = (productPriceText * productNameCounts[productNameText]).toFixed(2) + ' EGP'; // Update the product_price span for the specific product
          });

          let minusButton = document.createElement('button');
          minusButton.textContent = '-';
          minusButton.classList.add('btn', 'btn-sm', 'btn-secondary', 'mx-1');
          minusButton.addEventListener('click', function() {
            if (productNameCounts[productNameText] > 1) {
              productNameCounts[productNameText]--;
              countSpan.textContent = productNameCounts[productNameText];
              totalPrice -= price;
              document.getElementById('totalPrice').textContent = 'Total: ' + totalPrice.toFixed(2) +' EGP';
              product_price.textContent = 'Total: ' + (productPriceText * productNameCounts[productNameText]).toFixed(2) + ' EGP'; // Update the product_price span for the specific product
            } else {
              delete productNameCounts[productNameText];
              p.remove();
              totalPrice -= price;
              document.getElementById('totalPrice').textContent = 'Total: ' + totalPrice.toFixed(2) + ' EGP';
            }
          });

          let deleteButton = document.createElement('button');
          deleteButton.textContent = 'X';
          deleteButton.classList.add('btn', 'btn-sm', 'btn-danger', 'float-right');
          deleteButton.addEventListener('click', function() {
            delete productNameCounts[productNameText];
            p.remove();
            totalPrice -= price;
            document.getElementById('totalPrice').textContent = totalPrice.toFixed(2);
          });

          p.appendChild(countSpan);
          p.appendChild(plusButton);
          p.appendChild(minusButton);
          p.appendChild(deleteButton);
          addedOrders.appendChild(p);
        }

        document.getElementById('totalPrice').textContent = 'Total: ' + totalPrice + ' EGP';
      });
    }
  });

</script>

<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
</body>
</html>