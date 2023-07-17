// JavaScript code in signup.js

// Function to validate email format
function validateEmail(email) {
  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  return emailRegex.test(email);
}

// Function to handle form submission
function handleSubmit(event) {
  event.preventDefault(); // Prevent form submission

  // Fetch form input values
  const name = document.getElementById('Name').value;
  const email = document.getElementById('Email').value;
  const password = document.getElementById('Pass').value;
  const confirmPass = document.getElementById('Confirm').value;
  const balance = document.getElementById('Balance').value;
  const profilePhoto = document.getElementById('Photo').files[0];

  // Perform data validation
  if (name === '' || email === '' || password === '' || confirmPass === '' || balance === '' || !profilePhoto) {
    alert('Please fill in all the fields.');
    return;
  }

  if (!validateEmail(email)) {
    alert('Please enter a valid email address.');
    return;
  }

  if (password !== confirmPass) {
    alert('Passwords do not match.');
    return;
  }

  // Create form data object
  const formData = new FormData();
  formData.append('name', name);
  formData.append('email', email);
  formData.append('password', password);
  formData.append('balance', balance);
  formData.append('profile_photo', profilePhoto);

  // Create and send XHR request
  const xhr = new XMLHttpRequest();
  xhr.open('POST', 'http://localhost/Digital-Assignment/Float/backend/signup.php', true);
  xhr.onreadystatechange = function () {
    if (xhr.readyState === XMLHttpRequest.DONE) {
      console.log(xhr.responseText); // Print the response for debugging

      if (xhr.status === 200) {
        const data = JSON.parse(xhr.responseText);
        if (data.status === 'success') {
          // Redirect to main.html or perform any other action on successful signup
          window.location.href = '../main/main.html';
        } else {
          alert('An error occurred during signup. Please try again.');
        }
      } else {
        alert('An error occurred during signup. Please try again.');
      }
    }
  };
  xhr.send(formData);
}

// Add form submit event listener
document.getElementById('signupForm').addEventListener('submit', handleSubmit);
