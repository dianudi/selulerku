import Chart from "chart.js/auto";

window.Chart = Chart;

document.addEventListener("DOMContentLoaded", async () => {
    await import("./sidebar-toggle");
    await import("./category-modal");
    await import("./product-form");
    await import("./service-history-form");
    await import("./customer-selection-modal");
    await import("./mobile-cart.js");
    await import("./cart.js");
});
