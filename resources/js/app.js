import "./bootstrap";

document.addEventListener("DOMContentLoaded", () => {
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
});
