import Chart from 'chart.js/auto';

window.Chart = Chart;

document.addEventListener("DOMContentLoaded", () => {
    // Sidebar toggle
    const sidebarToggle = document.querySelector("#sidebarToggle");
    sidebarToggle.addEventListener("click", () => {
        document
            .querySelector("#sidebarToggleIcon")
            .classList.toggle("bi-list");
        document.querySelector("#sidebarToggleIcon").classList.toggle("bi-x");
        document.querySelector("#sidebar").classList.toggle("-ms-[280px]");
        setTimeout(() => {
            document.querySelector("#sidebar").classList.toggle("hidden");
        }, 100);
    });

    // Category Modal
    const updateCategoryClick = document.querySelectorAll(
        ".updateCategoryClick"
    );

    updateCategoryClick?.forEach((click) => {
        click.addEventListener("click", (e) => {
            console.log(click.attributes["data-action"].value);
            document
                .querySelector("#modalForm")
                .setAttribute("action", click.attributes["data-action"].value);
            document
                .querySelector("#modalInputName")
                .setAttribute("value", click.attributes["data-name"].value);
            document
                .querySelector("#modalPreview")
                .setAttribute("src", click.attributes["data-icon"].value);
        });
    });

    document.querySelector("#addNewCategory")?.addEventListener("click", () => {
        document
            .querySelector("#modalForm")
            .setAttribute("action", "/productcategories");
        document
            .querySelector('#modalForm > input[name="_method"]')
            .setAttribute("value", "POST");
        document.querySelector("#modalInputName").setAttribute("value", "");
        document.querySelector("#modalPreview").setAttribute("src", "");
    });

    const modalForm = document.querySelector("#modalForm");
    modalForm?.addEventListener("submit", (e) => {
        if (modalForm) {
            e.preventDefault();
            const data = new FormData(modalForm);
            fetch(modalForm.getAttribute("action"), {
                method: "POST",
                body: data,
                headers: {
                    accept: "application/json",
                },
            })
                .then((response) => response.json())
                .then((data) => {
                    if (data.errors) {
                        if (data.errors?.name) {
                            document
                                .querySelector("fieldset > input[name='name']")
                                .classList.add("border-red-500");
                            document.querySelector(
                                "#modalErrorName"
                            ).innerHTML = data.errors.name;
                        }
                        if (data.errors?.icon) {
                            document
                                .querySelector('fieldset > input[name="icon"]')
                                .classList.add("border-red-500");
                            document.querySelector(
                                "#modalErrorIcon"
                            ).innerHTML = data.errors.icon;
                        }
                    }
                    if (data.message && !data.errors) {
                        window.location.reload();
                    }
                })
                .catch((error) => console.error(error));
        }
    });

    // Product Category Modal
    const productForm = document.querySelector("#productForm");
    productForm?.addEventListener("submit", (e) => {
        e.preventDefault();
        const data = new FormData(productForm);
        fetch(productForm.getAttribute("action"), {
            method: "POST",
            body: data,
            headers: {
                accept: "application/json",
            },
        })
            .then((response) => response.json())
            .then((data) => {
                if (data.errors) {
                    if (data.errors?.product_category_id) {
                        document
                            .querySelector(
                                'fieldset > select[name="product_category_id"]'
                            )
                            .classList.add("border-red-500");
                        document.querySelector(
                            "#productErrorCategory"
                        ).innerHTML = data.errors.product_category_id;
                    }
                    if (data.errors?.name) {
                        document
                            .querySelector("fieldset > input[name='name']")
                            .classList.add("border-red-500");
                        document.querySelector("#productErrorName").innerHTML =
                            data.errors.name;
                    }
                    if (data.errors?.description) {
                        document
                            .querySelector(
                                'fieldset > textarea[name="description"]'
                            )
                            .classList.add("border-red-500");
                        document.querySelector(
                            "#productErrorDescription"
                        ).innerHTML = data.errors.description;
                    }
                    if (data.errors?.sku) {
                        document
                            .querySelector("fieldset > input[name='sku']")
                            .classList.add("border-red-500");
                        document.querySelector("#productErrorSku").innerHTML =
                            data.errors.sku;
                    }
                    if (data.errors?.quantity) {
                        document
                            .querySelector("fieldset > input[name='quantity']")
                            .classList.add("border-red-500");
                        document.querySelector(
                            "#productErrorQuantity"
                        ).innerHTML = data.errors.quantity;
                    }
                    if (data.errors?.price) {
                        document
                            .querySelector("fieldset > input[name='price']")
                            .classList.add("border-red-500");
                        document.querySelector("#productErrorPrice").innerHTML =
                            data.errors.price;
                    }
                    if (data.errors?.image) {
                        document
                            .querySelector('fieldset > input[name="image"]')
                            .classList.add("border-red-500");
                        document.querySelector("#productErrorImage").innerHTML =
                            data.errors.image;
                    }
                }
                if (data.message && !data.errors) {
                    window.location.replace("/products");
                }
            });
    });

    const serviceHistoryForm = document.querySelector("#serviceHistoryForm");

    if (serviceHistoryForm) {
        const detailsContainer = document.querySelector("#details-container");
        const addDetailBtn = document.querySelector("#add-detail-btn");
        const detailTemplate = document.querySelector("#detail-template");
        let detailIndex = 0;

        const addDetail = () => {
            const templateNode = detailTemplate.content.cloneNode(true);
            const detailItem = templateNode.querySelector(".detail-item");

            // Update names and IDs
            detailItem.querySelector(
                "input[name='details[0][kind]']"
            ).name = `details[${detailIndex}][kind]`;
            detailItem.querySelector(
                "input[name='details[0][description]']"
            ).name = `details[${detailIndex}][description]`;
            detailItem.querySelector(
                "input[name='details[0][price]']"
            ).name = `details[${detailIndex}][price]`;

            detailItem.querySelector(
                "#details-0-kind"
            ).id = `details-${detailIndex}-kind`;
            detailItem.querySelector(
                "#details-0-description"
            ).id = `details-${detailIndex}-description`;
            detailItem.querySelector(
                "#details-0-price"
            ).id = `details-${detailIndex}-price`;

            detailsContainer.appendChild(templateNode);
            detailIndex++;
        };

        // Add first detail on page load
        if (window.location.pathname === "/servicehistories/create")
            addDetail();

        addDetailBtn.addEventListener("click", addDetail);

        detailsContainer.addEventListener("click", (e) => {
            if (e.target.closest(".remove-detail-btn")) {
                e.target.closest(".detail-item").remove();
            }
        });

        serviceHistoryForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const data = new FormData(serviceHistoryForm);
            const action = serviceHistoryForm.getAttribute("action");
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            // Clear previous errors
            document
                .querySelectorAll(".text-red-500")
                .forEach((el) => (el.innerHTML = ""));

            fetch(action, {
                method: "POST",
                body: data,
                headers: {
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then((err) => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    if (data.message) {
                        window.location.href = "/servicehistories";
                    }
                })
                .catch((error) => {
                    if (error.errors) {
                        for (const key in error.errors) {
                            const errorDiv = document.getElementById(
                                key.replace(".", "-")
                            );
                            if (errorDiv) {
                                errorDiv.innerHTML = error.errors[key][0];
                            }
                        }
                    }
                });
        });
    }

    // Customer Selection Modal Logic
    const customerModal = document.getElementById("customer_selection_modal");
    if (customerModal) {
        const searchInput = document.getElementById("customer-search-input");
        const searchResultsContainer = document.getElementById(
            "customer-search-results"
        );
        const newCustomerForm = document.getElementById("new-customer-form");
        const selectedCustomerName = document.getElementById(
            "selected-customer-name"
        );
        const customerIdHiddenInput =
            document.getElementById("customer_id_hidden");
        let searchTimeout;

        const selectCustomer = (id, name) => {
            customerIdHiddenInput.value = id;
            selectedCustomerName.textContent = name;
            selectedCustomerName.classList.remove("text-gray-500");
            customerModal.close(); // Use DaisyUI method to close
        };

        searchInput.addEventListener("keyup", () => {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                const query = searchInput.value;
                if (query.length < 2) {
                    searchResultsContainer.innerHTML = "";
                    return;
                }

                fetch(`/customers?search=${query}`, {
                    headers: {
                        Accept: "application/json",
                    },
                })
                    .then((response) => response.json())
                    .then((paginatedResponse) => {
                        searchResultsContainer.innerHTML = "";
                        const customers = paginatedResponse.data; // Access the nested 'data' array
                        if (customers.length > 0) {
                            customers.forEach((customer) => {
                                const customerDiv =
                                    document.createElement("div");
                                customerDiv.textContent = `${customer.name} (${customer.phone_number})`;
                                customerDiv.className =
                                    "p-2 cursor-pointer hover:bg-gray-100";
                                customerDiv.dataset.customerId = customer.id;
                                customerDiv.dataset.customerName =
                                    customer.name;
                                searchResultsContainer.appendChild(customerDiv);
                            });
                        } else {
                            searchResultsContainer.innerHTML =
                                '<p class="p-2 text-gray-500">No customers found.</p>';
                        }
                    });
            }, 300);
        });

        searchResultsContainer.addEventListener("click", (e) => {
            if (e.target.dataset.customerId) {
                selectCustomer(
                    e.target.dataset.customerId,
                    e.target.dataset.customerName
                );
            }
        });

        newCustomerForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(newCustomerForm);
            const csrfToken = document
                .querySelector('meta[name="csrf-token"]')
                .getAttribute("content");

            fetch(newCustomerForm.action, {
                method: "POST",
                body: formData,
                headers: {
                    Accept: "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                },
            })
                .then((response) => {
                    if (!response.ok) {
                        return response.json().then((err) => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log(data);
                    selectCustomer(data.customer.id, data.customer.name);
                    newCustomerForm.reset();
                })
                .catch((error) => {
                    // Clear previous errors
                    document
                        .querySelectorAll("#new-customer-form .text-red-500")
                        .forEach((el) => (el.textContent = ""));

                    if (error.errors) {
                        for (const key in error.errors) {
                            document.getElementById(
                                `new-customer-error-${key}`
                            ).textContent = error.errors[key][0];
                        }
                    }
                });
        });
    }
});
