<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Carrinho de Compras - Sampaio Cosméticos</title>
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Lato&display=swap");
      * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
      }

      body {
        background: #f6f6f6;
        font-family: "Lato", sans-serif;
      }

      body > header {
        background: #d4098a;
        color: white;
        font-size: 24px;
        padding: 20px 0;
        display: flex;
        justify-content: center;
      }

      body > main {
        background: #fff;
        padding: 0 30px 30px;
        max-width: 1200px;
        margin: 0 auto;
      }

      body > main .page-title {
        font-size: 40px;
        padding: 50px 0;
        text-align: center;
      }

      body > main .content {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
      }

      body > main .content section {
        flex: 1;
        min-width: 0;
      }

      body > main .content aside {
        min-width: 250px;
        max-width: 300px;
      }

      button {
        cursor: pointer;
        font-family: "Lato", sans-serif;
        transition: background 0.2s;
      }

      table {
        width: 100%;
        border-collapse: collapse;
      }

      table thead tr {
        border-bottom: 3px solid #eee;
      }

      table thead tr th {
        text-align: left;
        padding-bottom: 10px;
        text-transform: uppercase;
        color: #666;
      }

      table tbody tr {
        border-bottom: 3px solid #eee;
        transition: background 0.2s;
      }

      table tbody tr:last-child {
        border: 0;
      }

      table tbody tr td {
        padding: 20px 0;
      }

      .product {
        display: flex;
        align-items: center;
      }

      .product img {
        border-radius: 6px;
        max-width: 100px;
      }

      .product .info {
        margin-left: 20px;
      }

      .product .info .name {
        font-size: 20px;
        margin-bottom: 10px;
      }

      .product .info .category {
        color: #666;
      }

      .qty {
        background: #eee;
        display: inline-flex;
        padding: 0;
        justify-content: space-around;
        align-items: center;
        min-width: 60px;
        border-radius: 20px;
        overflow: hidden;
        height: 30px;
      }

      .qty span {
        margin: 0 10px;
      }

      .qty button {
        display: flex;
        align-items: center;
        background: transparent;
        border: 0;
        padding: 0 10px;
        font-size: 20px;
        height: 100%;
      }

      .qty button:hover {
        background: #ddd;
      }

      .remove {
        background: #eee;
        border: 0;
        width: 30px;
        height: 30px;
        border-radius: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
      }

      .remove:hover {
        background: #ddd;
      }

      aside {
        margin-left: 30px;
      }

      aside .box {
        margin-bottom: 15px;
        border: 1px solid #ccc;
        background: #eee;
        color: #222;
      }

      aside .box header {
        padding: 15px 20px;
        font-size: 18px;
        border-bottom: 1px solid #ccc;
      }

      aside .box .info {
        padding: 20px;
        font-size: 16px;
      }

      aside .box .info > div {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        color: #555;
      }

      aside .box .info > div:last-child {
        margin: 0;
      }

      aside .box .info button {
        color: #28a745;
        background: transparent;
        border: 0;
        display: flex;
        align-items: center;
        font-size: 16px;
      }

      aside .box footer {
        padding: 15px 20px;
        background: #ddd;
        font-size: 18px;
        display: flex;
        justify-content: space-between;
      }

      aside > button {
        border: 0;
        padding: 15px 0;
        color: white;
        background: #28a745;
        display: block;
        width: 100%;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 16px;
      }

      aside > button:hover {
        background: #3bc55b;
      }

      .coupon-input {
        margin-top: 10px;
        padding: 8px;
        width: 100%;
        border: 1px solid #ccc;
        border-radius: 4px;
      }

      .coupon-applied {
        color: #28a745;
        font-size: 14px;
        margin-top: 5px;
      }

      .empty-cart {
        text-align: center;
        padding: 20px;
        color: #666;
      }

      @media (max-width: 768px) {
        body > main .content {
          flex-direction: column;
        }
        aside {
          margin-left: 0;
          max-width: 100%;
        }
      }
    </style>
  </head>
  <body>
    <header>
     
    </header>
    <main>
      <div class="page-title">Seu Carrinho</div>
      <div class="content">
        <section>
          <table id="cart-table">
            <thead>
              <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Total</th>
                <th>-</th>
              </tr>
            </thead>
            <tbody id="cart-items">
              <!-- Cart items populated by JavaScript -->
            </tbody>
          </table>
          <div id="empty-cart-message" class="empty-cart" style="display: none;">
            Seu carrinho está vazio.
          </div>
        </section>
        <aside>
          <div class="box">
            <header>Resumo da compra</header>
            <div class="info">
              <div><span>Sub-total</span><span id="subtotal">R$ 0</span></div>
              <div><span>Frete</span><span>Gratuito</span></div>
              <div><span>Desconto</span><span id="discount">R$ 0</span></div>
              <div>
                <button id="coupon-btn">
                  Adicionar cupom de desconto
                  <i class="bx bx-right-arrow-alt"></i>
                </button>
                <input
                  type="text"
                  id="coupon-input"
                  class="coupon-input"
                  placeholder="Digite o cupom"
                  style="display: none;"
                />
                <div id="coupon-message" class="coupon-applied" style="display: none;"></div>
              </div>
            </div>
            <footer>
              <span>Total</span>
              <span id="total">R$ 0</span>
            </footer>
          </div>
          <button id="checkout-btn">Finalizar Compra</button>
        </aside>
      </div>
    </main>
    <script>
      function formatPrice(value) {
        return `R$ ${value.toFixed(2).replace(".", ",")}`;
      }

      async function fetchCart() {
        try {
          const response = await fetch("cart.php?action=get");
          const data = await response.json();
          renderCart(data.cart, data.discount || 0);
        } catch (error) {
          console.error("Erro ao carregar carrinho:", error);
        }
      }

      function renderCart(cart, discount) {
        const cartItems = document.getElementById("cart-items");
        const emptyCartMessage = document.getElementById("empty-cart-message");
        cartItems.innerHTML = "";

        if (cart.length === 0) {
          emptyCartMessage.style.display = "block";
          updateSummary(0, discount);
          return;
        }

        emptyCartMessage.style.display = "none";

        cart.forEach((item) => {
          const row = document.createElement("tr");
          row.innerHTML = `
            <td>
              <div class="product">
                <img src="${item.image}" alt="${item.name}" />
                <div class="info">
                  <div class="name">${item.name}</div>
                  <div class="category">${item.category}</div>
                </div>
              </div>
            </td>
            <td>${formatPrice(item.price)}</td>
            <td>
              <div class="qty">
                <button onclick="updateQuantity(${item.id}, -1)">
                  <i class="bx bx-minus"></i>
                </button>
                <span>${item.quantity}</span>
                <button onclick="updateQuantity(${item.id}, 1)">
                  <i class="bx bx-plus"></i>
                </button>
              </div>
            </td>
            <td>${formatPrice(item.price * item.quantity)}</td>
            <td>
              <button class="remove" onclick="removeItem(${item.id})">
                <i class="bx bx-x"></i>
              </button>
            </td>
          `;
          cartItems.appendChild(row);
        });

        updateSummary(
          cart.reduce((sum, item) => sum + item.price * item.quantity, 0),
          discount
        );
      }

      async function updateQuantity(id, change) {
        try {
          const response = await fetch(
            `cart.php?action=update&id=${id}&change=${change}`,
            { method: "POST" }
          );
          const data = await response.json();
          renderCart(data.cart, data.discount || 0);
        } catch (error) {
          console.error("Erro ao atualizar quantidade:", error);
        }
      }

      async function removeItem(id) {
        try {
          const response = await fetch(`cart.php?action=remove&id=${id}`, {
            method: "POST",
          });
          const data = await response.json();
          renderCart(data.cart, data.discount || 0);
        } catch (error) {
          console.error("Erro ao remover item:", error);
        }
      }

      function updateSummary(subtotal, discount) {
        document.getElementById("subtotal").textContent = formatPrice(subtotal);
        document.getElementById("discount").textContent = formatPrice(discount);
        document.getElementById("total").textContent = formatPrice(subtotal - discount);
      }

      // Coupon functionality
      document.getElementById("coupon-btn").addEventListener("click", () => {
        const couponInput = document.getElementById("coupon-input");
        couponInput.style.display =
          couponInput.style.display === "none" ? "block" : "none";
      });

      document.getElementById("coupon-input").addEventListener("change", async (e) => {
        const coupon = e.target.value.toUpperCase();
        try {
          const response = await fetch(`cart.php?action=apply_coupon&code=${coupon}`, {
            method: "POST",
          });
          const data = await response.json();
          if (data.success) {
            document.getElementById("coupon-message").textContent = `Cupom ${coupon} aplicado!`;
            document.getElementById("coupon-message").style.display = "block";
            renderCart(data.cart, data.discount);
          } else {
            document.getElementById("coupon-message").textContent = "Cupom inválido!";
            document.getElementById("coupon-message").style.display = "block";
          }
          e.target.value = "";
        } catch (error) {
          console.error("Erro ao aplicar cupom:", error);
        }
      });

      // Checkout button
      document.getElementById("checkout-btn").addEventListener("click", async () => {
        try {
          const response = await fetch("cart.php?action=checkout", { method: "POST" });
          const data = await response.json();
          if (data.success) {
            alert("Compra finalizada com sucesso!");
            fetchCart();
          } else {
            alert("Seu carrinho está vazio!");
          }
        } catch (error) {
          console.error("Erro ao finalizar compra:", error);
        }
      });

      // Initial render
      fetchCart();
    </script>
  </body>
</html>