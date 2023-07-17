document.addEventListener('DOMContentLoaded', () => {
    // Fetch user data and update the HTML elements
    fetchUserData();
    
    // Fetch transaction history and update the table
    fetchTransactionHistory();
  });
  
  function fetchUserData() {
    fetch('http://localhost/Digital-Assignment/Float/backend/fetch_details.php', {
      method: 'POST',
      credentials: 'same-origin', // Include cookies for session handling
    })
    .then(response => response.json())
    .then(data => {
      if (data.user) {
        // Update user balance
        const balanceElement = document.getElementById('balance');
        balanceElement.innerHTML = `Balance: <span>₹${data.user.balance}</span>`;
  
        // Update user name
        const nameElement = document.getElementById('name');
        nameElement.textContent = data.user.name;
  
        // Update profile photo (assuming the image URL is in the response)
        const photoElement = document.getElementById('profile_photo');
        let imgURL = data.user.profile_photo;
        imgURL = 'http://localhost/Digital-Assignment/Float/backend/' + imgURL;
        photoElement.src = imgURL;
      }
    })
    .catch(error => {
      console.error('Error fetching user data:', error);
    });
  }
  
  function fetchTransactionHistory() {
    fetch('http://localhost/Digital-Assignment/Float/backend/get_transaction_history.php', {
      method: 'POST',
      credentials: 'same-origin', // Include cookies for session handling
    })
    .then(response => response.json())
    .then(data => {
      const historyTableBody = document.getElementById('transactionHistory');
  
      if (data.transactions && data.transactions.length > 0) {
        // Clear existing rows from the table
        historyTableBody.innerHTML = '';
  
        // Populate the table with transaction data
        data.transactions.forEach(transaction => {
          const row = document.createElement('tr');
          const nameCell = document.createElement('td');
          const dateCell = document.createElement('td');
          const amountCell = document.createElement('td');
        
          if (transaction.isCredit === 1) {
            
          }
          nameCell.textContent = transaction.name;
          dateCell.textContent = transaction.date;
          amountCell.textContent = transaction.amount;
  
          row.appendChild(nameCell);
          row.appendChild(dateCell);
          row.appendChild(amountCell);
  
          historyTableBody.appendChild(row);
        });
      }
    })
    .catch(error => {
      console.error('Error fetching transaction history:', error);
    });
  }

  function addTransaction() {
    // Get form values
    const name = document.getElementById('tname').value;
    const amount = parseFloat(document.getElementById('amount').value);
    const isCredit = document.getElementById('credit').checked ? 1 : 0;
  
    // Create the request body
    const requestBody = {
      name,
      amount,
      isCredit
    };
  
    // Make the HTTP request using the Fetch API
    fetch('http://localhost/Digital-Assignment/Float/backend/add_transaction.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      credentials: 'include', // Include cookies for session handling
      body: JSON.stringify(requestBody)
    })
      .then(response => {
        if (!response.ok) {
          throw new Error('Network response was not ok');
        }
        return response.json();
      })
      .then(data => {
        if (data.status === 'success') {
          fetchUserData();
          fetchTransactionHistory();
        } else {
          console.error('Error adding transaction:', data.message);
        }
      })
      .catch(error => {
        console.error('Error adding transaction:', error);
      });
  }
  