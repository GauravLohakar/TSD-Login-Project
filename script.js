// ================= FORM SWITCHING =================

function toggleForms() {

    const loginBox =
        document.getElementById("login-box");

    const registerBox =
        document.getElementById("register-box");


    if (loginBox.classList.contains("active")) {

        // Hide Login
        loginBox.classList.remove("active");
        loginBox.classList.add("hidden");


        // Show Registration
        registerBox.classList.remove("hidden");
        registerBox.classList.add("active");

    }

    else {

        // Hide Registration
        registerBox.classList.remove("active");
        registerBox.classList.add("hidden");


        // Show Login
        loginBox.classList.remove("hidden");
        loginBox.classList.add("active");

    }

}