const cartItemsContainer = document.getElementById("cart-items");
const cartSubtotalElement = document.getElementById("cart-subtotal");
const cartTotalElement = document.getElementById("cart-total");
const checkoutButton = document.getElementById("checkout");
const emptyCartMessage = document.getElementById("empty-cart-message");
const resetButton = document.getElementById("reset-cart-button");

function getCart() {
    const cart = localStorage.getItem("shopping_cart");
    return cart ? JSON.parse(cart) : [];
}

function saveCart(cart) {
    localStorage.setItem("shopping_cart", JSON.stringify(cart));
    renderCart();
}

function addToCart(product) {
    const cart = getCart();
    const existingProduct = cart.find((item) => item.id === product.id);
    if (existingProduct) {
        existingProduct.quantity++;
    } else {
        cart.push({ ...product, quantity: 1 });
    }
    saveCart(cart);
}

function updateQuantity(productId, change) {
    let cart = getCart();
    const product = cart.find((item) => item.id === productId);
    if (product) {
        product.quantity += change;
        if (product.quantity <= 0) {
            cart = cart.filter((item) => item.id !== productId);
        }
    }
    saveCart(cart);
}

function removeFromCart(productId) {
    let cart = getCart();
    cart = cart.filter((item) => item.id !== productId);
    saveCart(cart);
}

function resetCart() {
    localStorage.removeItem("shopping_cart");
    renderCart();
}

function renderCart() {
    const cart = getCart();
    if (cartItemsContainer) cartItemsContainer.innerHTML = "";

    if (cart.length === 0) {
        if (emptyCartMessage) emptyCartMessage.classList.remove("hidden");
        if (checkoutButton) checkoutButton.disabled = true;
        updateTotals(0);
        return;
    }

    if (emptyCartMessage) emptyCartMessage.classList.add("hidden");
    if (checkoutButton) checkoutButton.disabled = false;

    let subtotal = 0;

    let index = 0;
    cart.forEach((item) => {
        const li = document.createElement("li");
        li.className = "list-row items-center";
        li.innerHTML = `
                <input type="hidden" name="details[${index}][product_id]" value="${item.id}">
                <div><img class="size-15 rounded-box" src="${item.image}" /></div>
                <div>
                    <div>${item.name}</div>
                    <div class="text-xs uppercase font-semibold opacity-60">Rp. ${item.price}</div>
                    <div class="mt-2">
                        <button type="button" data-id="${item.id}" class="btn btn-xs btn-square btn-neutral decrement-quantity"><i class="bi bi-dash text-xl"></i></button>
                        <input name="details[${index}][quantity]" class="w-10 text-center" type="text" inputmode="numeric" value="${item.quantity}" readonly>
                        <button type="button" data-id="${item.id}" class="btn btn-xs btn-square btn-neutral increment-quantity"><i class="bi bi-plus text-xl"></i></button>
                    </div>
                </div>
                <button type="button" data-id="${item.id}" class="btn btn-square btn-ghost remove-from-cart">
                    <i class="bi bi-trash text-lg"></i>
                </button>
            `;
        if (cartItemsContainer) cartItemsContainer.appendChild(li);
        subtotal += item.price * item.quantity;
        index++;
    });

    updateTotals(subtotal);
}

function updateTotals(subtotal) {
    const total = subtotal; // Add logic for tax or shipping if needed
    if (cartSubtotalElement)
        cartSubtotalElement.textContent = `Rp. ${subtotal.toLocaleString(
            "id-ID"
        )}`;
    if (cartTotalElement)
        cartTotalElement.textContent = `Rp. ${total.toLocaleString("id-ID")}`;
}

// Event Listeners
document.querySelectorAll(".add-to-cart").forEach((button) => {
    button.addEventListener("click", (e) => {
        const product = {
            id: e.currentTarget.dataset.id,
            name: e.currentTarget.dataset.productName,
            price: parseFloat(e.currentTarget.dataset.productPrice),
            image: e.currentTarget.dataset.productImage,
        };
        addToCart(product);
    });
});

if (cartItemsContainer)
    cartItemsContainer.addEventListener("click", (e) => {
        const target = e.target.closest("button");
        if (!target) return;

        const productId = target.dataset.id;

        if (target.classList.contains("increment-quantity")) {
            updateQuantity(productId, 1);
        } else if (target.classList.contains("decrement-quantity")) {
            updateQuantity(productId, -1);
        } else if (target.classList.contains("remove-from-cart")) {
            removeFromCart(productId);
        }
    });

if (resetButton) {
    resetButton.addEventListener("click", () => {
        resetCart();
    });
}

// Initial render
renderCart();
