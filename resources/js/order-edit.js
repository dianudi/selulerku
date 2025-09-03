const cartItemsContainer = document.getElementById("cart-items");
const cartSubtotalElement = document.getElementById("cart-subtotal");
const cartTotalElement = document.getElementById("cart-total");
const checkoutButton = document.getElementById("checkout");
const emptyCartMessage = document.getElementById("empty-cart-message");

let currentCart = [];

function saveCart(cart) {
    currentCart = cart;
    renderCart();
}

function updateQuantity(productId, change) {
    let cart = [...currentCart];
    const product = cart.find((item) => item.id == productId);
    if (product) {
        product.quantity += change;
        if (product.quantity <= 0) {
            cart = cart.filter((item) => item.id != productId);
        }
    }
    saveCart(cart);
}

function removeFromCart(productId) {
    let cart = [...currentCart];
    cart = cart.filter((item) => item.id != productId);
    saveCart(cart);
}

function resetCart() {
    const editPageContainer = document.getElementById("order-edit-page");
    if (editPageContainer && editPageContainer.dataset.orderDetails) {
        const initialCartData = JSON.parse(
            editPageContainer.dataset.orderDetails
        );
        saveCart(initialCartData);
    }
}

function renderCart() {
    const cart = currentCart;
    if (!cartItemsContainer) return;

    cartItemsContainer.innerHTML = "";

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
                <div><img class="size-15 rounded-box" src="${item.image}" alt="${item.name}"/></div>
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
        cartItemsContainer.appendChild(li);
        subtotal += item.price * item.quantity;
        index++;
    });

    updateTotals(subtotal);
}

function updateTotals(subtotal) {
    const total = subtotal;
    if (cartSubtotalElement)
        cartSubtotalElement.textContent = `Rp. ${subtotal.toLocaleString(
            "id-ID"
        )}`;
    if (cartTotalElement)
        cartTotalElement.textContent = `Rp. ${total.toLocaleString("id-ID")}`;
}

// --- MASTER EVENT LISTENER ATTACHED TO THE DOCUMENT ---
document.addEventListener("click", (e) => {
    // Case 1: A button inside the cart items list
    const cartButton = e.target.closest("#cart-items button");
    if (cartButton) {
        const productId = cartButton.dataset.id;
        if (!productId) return;

        if (cartButton.classList.contains("increment-quantity")) {
            updateQuantity(productId, 1);
        } else if (cartButton.classList.contains("decrement-quantity")) {
            updateQuantity(productId, -1);
        } else if (cartButton.classList.contains("remove-from-cart")) {
            removeFromCart(productId);
        }
        return; // Action handled
    }

    // Case 2: The reset button
    const resetButton = e.target.closest("#reset-cart-button");
    if (resetButton) {
        resetCart();
        return; // Action handled
    }
});

// Initializer is now just the reset function
resetCart();
