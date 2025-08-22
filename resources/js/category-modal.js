const updateCategoryClick = document.querySelectorAll(".updateCategoryClick");

updateCategoryClick?.forEach((click) => {
    click.addEventListener("click", (e) => {
        document.querySelector(".modal-box > h3").innerHTML = "Update Category";
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
    document.querySelector(".modal-box > h3").innerHTML = "Add New Category";
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
                        document.querySelector("#modalErrorName").innerHTML =
                            data.errors.name;
                    }
                    if (data.errors?.icon) {
                        document
                            .querySelector('fieldset > input[name="icon"]')
                            .classList.add("border-red-500");
                        document.querySelector("#modalErrorIcon").innerHTML =
                            data.errors.icon;
                    }
                }
                if (data.message && !data.errors) {
                    window.location.reload();
                }
            })
            .catch((error) => console.error(error));
    }
});
