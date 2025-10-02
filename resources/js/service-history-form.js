const detailsContainer = document.querySelector("#details-container");
const addDetailBtn = document.querySelector("#add-detail-btn");
const detailTemplate = document.querySelector("#detail-template");
let detailIndex = 0;

function addDetail() {
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
        "input[name='details[0][cost_price]']"
    ).name = `details[${detailIndex}][cost_price]`;
    detailItem.querySelector(
        "input[name='details[0][image]']"
    ).name = `details[${detailIndex}][image]`;

    detailItem.querySelector(
        "#details-0-kind"
    ).id = `details-${detailIndex}-kind`;
    detailItem.querySelector(
        "#details-0-description"
    ).id = `details-${detailIndex}-description`;
    detailItem.querySelector(
        "#details-0-price"
    ).id = `details-${detailIndex}-price`;
    detailItem.querySelector(
        "#details-0-cost_price"
    ).id = `details-${detailIndex}-cost_price`;
    detailItem.querySelector(
        "#details-0-image"
    ).id = `details-${detailIndex}-image`;

    detailsContainer.appendChild(templateNode);
    detailIndex++;
}

// Add first detail on page load
if (window.location.pathname === "/servicehistories/create") addDetail();

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
