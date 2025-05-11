const form = document.getElementById('send-form');
const successBox = document.getElementById('success-message');

form.addEventListener('submit', function (e) {
  e.preventDefault();

  const email = document.getElementById('email').value;
  const currency = document.getElementById('currency').value;
  const amount = document.getElementById('amount').value;

  if (!email || !amount || amount <= 0) {
    alert('Please enter valid details.');
    return;
  }

  // Update success message content
  document.getElementById('email-value').textContent = email;
  document.getElementById('currency-value').textContent = currency;
  document.getElementById('amount-value').textContent = amount;

  // Hide form and show success
  document.querySelector('.payment-confirmation').style.display = 'none';
  successBox.style.display = 'block';
});
