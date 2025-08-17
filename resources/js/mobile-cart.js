const cartButton = document.getElementById("cart-button");
const cartModal = document.getElementById("cart_modal");
const modalBox = cartModal.querySelector(".modal-box");
const cartSidebarContainer = document.getElementById("cart-sidebar-container");

const cartContent = document.getElementById("cart");

const handleMobileCart = () => {
    if (window.innerWidth < 1024) {
        // On mobile, move cart to modal
        if (!modalBox.contains(cartContent)) {
            modalBox.appendChild(cartContent);
        }
    } else {
        // On desktop, move cart back to sidebar
        if (!cartSidebarContainer.contains(cartContent)) {
            cartSidebarContainer.appendChild(cartContent);
        }
    }
};

cartButton.addEventListener("click", () => {
    cartModal.showModal();
});

// Run on initial load
handleMobileCart();

// Debounce resize event for performance
let resizeTimeout;
window.addEventListener("resize", () => {
    clearTimeout(resizeTimeout);
    resizeTimeout = setTimeout(handleMobileCart, 150);
});
