
const passwordInput = document.getElementById("bc-lightwallet-password");
const passwordValidation = document.getElementById("bc-lightwallet-password-validity");
passwordInput.addEventListener("input", function(event) {
  const password = event.target.value;
  
  // Define the regular expression to match the password format
  const passwordRegex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};'."'".':"\\|,.<>\/?])(?=.*[a-zA-Z]).{8,}$/;

  if (!passwordRegex.test(password)) {
    passwordValidation.innerHTML = "Password must contain at least 1 special character, 1 uppercase letter, 1 lowercase letter, 1 number, and be at least 8 characters long.";
  } else {
    passwordValidation.innerHTML = "";
  }
});
