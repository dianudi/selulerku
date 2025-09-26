import Chart from "chart.js/auto";

window.Chart = Chart;

document.addEventListener("DOMContentLoaded", async () => {
    // Common scripts for all pages
    await import("./sidebar-toggle");
    await import("./category-modal");
    await import("./customer-selection-modal");
    await import("./mobile-cart.js");

    // Page-specific scripts with dynamic import
    const isProductIndexPage = document.querySelector(".stats.shadow");
    const isOrderEditPage = document.getElementById("order-edit-page");
    const serviceHistoryForm = document.querySelector("#serviceHistoryForm");
    const productForm = document.querySelector("#productForm");
    const expenseForm = document.querySelector("#expenseForm");

    if (productForm) await import("./product-form.js");
    if (expenseForm) await import("./expense-form.js");
    if (isProductIndexPage) await import("./cart.js");
    if (isOrderEditPage) await import("./order-edit.js");
    if (serviceHistoryForm) await import("./service-history-form.js");
});
