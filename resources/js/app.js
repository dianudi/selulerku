import Chart from "chart.js/auto";

window.Chart = Chart;

document.addEventListener("DOMContentLoaded", async () => {
    // Common scripts for all pages
    await import("./sidebar-toggle");
    await import("./category-modal");
    await import("./product-form");
    await import("./service-history-form");
    await import("./customer-selection-modal");
    await import("./mobile-cart.js");

    // Page-specific scripts with dynamic import
    const isProductIndexPage = document.querySelector(".stats.shadow");
    const isOrderEditPage = document.getElementById("order-edit-page");

    if (isProductIndexPage) {
        await import("./cart.js");
    }

    if (isOrderEditPage) {
        await import("./order-edit.js");
    }
});