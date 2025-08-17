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
    const customerIdHiddenInput = document.getElementById("customer_id_hidden");
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
                            const customerDiv = document.createElement("div");
                            customerDiv.textContent = `${customer.name} (${customer.phone_number})`;
                            customerDiv.className =
                                "p-2 cursor-pointer hover:bg-gray-100";
                            customerDiv.dataset.customerId = customer.id;
                            customerDiv.dataset.customerName = customer.name;
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
