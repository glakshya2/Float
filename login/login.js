document.addEventListener("DOMContentLoaded", function () {
  const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  document.getElementById("loginForm").addEventListener("submit", function (event) {
    event.preventDefault(); // Prevent form submission

    let email = document.getElementById("emailInput").value;
    let password = document.getElementById("passwordInput").value;

    if (!emailPattern.test(email)) {
      document .getElementById("emailInput").classList.add('error');
      alert("Invalid email format");
      return;
    }

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "http://localhost/Digital-Assignment/Float/backend/login.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
      if (xhr.readyState === XMLHttpRequest.DONE) {
        console.log(xhr.responseText); // Print the response for debugging

        if (xhr.status === 200) {
          var response = JSON.parse(xhr.responseText);
          if (response.status === "success") {
            // If login is successful, navigate to main.html
            window.location.href = "../main/main.html";
          } else {
            // Show error message
            alert(response.status);
          }
        } else {
          // Show error message
          alert("An error occurred.");
        }
      }
    };

    var params =
      "email=" +
      encodeURIComponent(email) +
      "&password=" +
      encodeURIComponent(password);
    xhr.send(params);
  });

  document.getElementById("emailInput").onfocus = function () {
    document.getElementById("emailInput").classList.remove("error");
  };
});
