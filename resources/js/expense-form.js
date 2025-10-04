import imageCompression from "browser-image-compression";

const expenseForm = document.querySelector("#expenseForm");
let compressedReceiptFile = null;

const receiptInput = document.querySelector('input[name="receipt_image_path"]');
receiptInput?.addEventListener("change", async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    if (!file.type.startsWith("image/")) return;

    try {
        compressedReceiptFile = await imageCompression(file, {
            maxSizeMB: 1,
            maxWidthOrHeight: 1920,
            useWebWorker: true,
        });
        const preview = document.querySelector("#modalPreviewReceipt");
        if (preview) {
            preview.src = await imageCompression.getDataUrlFromFile(
                compressedReceiptFile
            );
        }
    } catch (error) {
        console.error(error);
        compressedReceiptFile = null;
    }
});

expenseForm?.addEventListener("submit", (e) => {
    e.preventDefault();
    const data = new FormData(expenseForm);
    const action = expenseForm.getAttribute("action");

    if (compressedReceiptFile) {
        data.set(
            "receipt_image_path",
            compressedReceiptFile,
            compressedReceiptFile.name
        );
    }

    // Clear previous errors
    document.querySelectorAll(".error-message").forEach((el) => {
        el.innerHTML = "";
    });
    document.querySelectorAll(".border-red-500").forEach((el) => {
        el.classList.remove("border-red-500");
    });

    fetch(action, {
        method: "POST",
        body: data,
        headers: {
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
                window.location.href = "/expenses";
            }
        })
        .catch((error) => {
            if (error.errors) {
                for (const key in error.errors) {
                    const errorDiv = document.getElementById(key);
                    const inputEl = document.querySelector(`[name="${key}"]`);
                    if (errorDiv) {
                        errorDiv.innerHTML = error.errors[key][0];
                    }
                    if (inputEl) {
                        inputEl.classList.add("border-red-500");
                    }
                }
            }
        });
});
