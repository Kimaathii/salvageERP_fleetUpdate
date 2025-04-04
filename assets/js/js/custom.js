const togglePassword = document.getElementById("togglePassword");
const passwordInput = document.getElementById("Password");

if (togglePassword && passwordInput) {
    togglePassword.addEventListener("click", function () {
        // Toggle password visibility
        const type = passwordInput.type === "password" ? "text" : "password";
        passwordInput.type = type;

        // Toggle eye icon class
        this.classList.toggle("fa-eye");
        this.classList.toggle("fa-eye-slash");
        
        console.log("Password toggle clicked!"); // Debugging log
    });

    console.log("Event listener attached to togglePassword.");
} else {
    console.log("togglePassword or passwordInput not found.");
}
