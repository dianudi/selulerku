import imageCompression from "browser-image-compression";

const productForm = document.querySelector("#productForm");
let compressedImageFile = null;

const imageInput = document.querySelector('input[name="image"]');
imageInput?.addEventListener("change", async (e) => {
    const file = e.target.files[0];
    if (!file) return;
    if (!file.type.startsWith("image/")) return;

    try {
        compressedImageFile = await imageCompression(file, {
            maxSizeMB: 1,
            maxWidthOrHeight: 1920,
            useWebWorker: true,
        });
        const preview = document.querySelector("#imagePreview");
        if (preview) {
            preview.src = await imageCompression.getDataUrlFromFile(
                compressedImageFile
            );
        }
    } catch (error) {
        console.error(error);
        compressedImageFile = null;
    }
});

productForm?.addEventListener("submit", (e) => {
    e.preventDefault();
    const data = new FormData(productForm);
    const action = productForm.getAttribute("action");

    if (compressedImageFile) {
        data.set("image", compressedImageFile, compressedImageFile.name);
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
                window.location.href = "/products";
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
