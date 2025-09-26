const expenseForm = document.querySelector("#expenseForm");

expenseForm?.addEventListener("submit", (e) => {
    e.preventDefault();
    const data = new FormData(expenseForm);
    const action = expenseForm.getAttribute("action");

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
