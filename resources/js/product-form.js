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
                    document.querySelector("#productErrorCategory").innerHTML =
                        data.errors.product_category_id;
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
                    document.querySelector("#productErrorQuantity").innerHTML =
                        data.errors.quantity;
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
