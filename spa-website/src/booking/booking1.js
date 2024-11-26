document.addEventListener("DOMContentLoaded", function () {
    const today = new Date().toISOString().split("T")[0];
    document.getElementById("date").setAttribute("min", today);

    // Get the checkbox and input elements
    const accommodationCheckbox = document.getElementById("accommodation-checkbox");
    const accommodationInput = document.getElementById("accommodation-input");

    // Event listener for checkbox to toggle the accommodation input visibility
    accommodationCheckbox.addEventListener("change", function () {
        if (accommodationCheckbox.checked) {
            accommodationInput.style.display = "block"; // Show the input
        } else {
            accommodationInput.style.display = "none"; // Hide the input
        }
    });
});

function validateForm(event) {
    console.log("Form validation started");

    const fullName = document.getElementById("fullName").value.trim();
    const phone = document.getElementById("phone").value.trim();
    const email = document.getElementById("email").value.trim();
    const postalCode = document.getElementById("postalCode").value.trim();
    const date = document.getElementById("date").value;

    // Regular expression for validating phone number (e.g., 819-555-5555)
    const phonePattern = /^\d{3}-\d{3}-\d{4}$/;

    // Regular expression for validating postal code (e.g., A1A 1A1)
    const postalCodePattern = /^[A-Za-z]\d[A-Za-z] \d[A-Za-z]\d$/;

    // Validate phone number
    if (!phonePattern.test(phone)) {
        alert("Please enter a valid phone number in the format: 819-555-5555.");
        console.log("Invalid phone number");
        event.preventDefault();  // Prevent form submission
        return false;
    }

    // Validate postal code (should be in format A1A 1A1)
    if (!postalCodePattern.test(postalCode)) {
        alert("Please enter a valid postal code in the format: A1A 1A1.");
        console.log("Invalid postal code");
        event.preventDefault();
        return false;
    }

    // Validate full name (should not be empty)
    if (fullName === "") {
        alert("Full name is required.");
        console.log("Full name is missing");
        event.preventDefault();
        return false;
    }

    // Validate email (should be correct format)
    const emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!emailPattern.test(email)) {
        alert("Please enter a valid email address.");
        console.log("Invalid email address");
        event.preventDefault();
        return false;
    }

    // Validate date (should not be empty)
    if (date === "") {
        alert("Date is required.");
        console.log("Date is missing");
        event.preventDefault();
        return false;
    }

    console.log("Form validation completed successfully");

    // If everything is valid, allow the form to submit
    return true;
}

// Attach form validation to the form's submit event
document.querySelector("form").addEventListener("submit", function(event) {
    if (!validateForm(event)) {
        event.preventDefault();  // Stop form submission if validation fails
    }
});
