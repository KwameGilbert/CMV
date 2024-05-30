document.getElementById('paymentForm').addEventListener('submit', payWithPaystack, false);

function payWithPaystack(event) {
    event.preventDefault();
    let first_name = document.getElementById('first_name').value;
    let last_name = document.getElementById('last_name').value;
    let votes_amount = document.getElementById('amount').innerText;
    let votes_amount_float = parseFloat(votes_amount);
    let email = document.getElementById('email').value;
    let amount = votes_amount_float * 100; // Paystack expects amount in kobo (or smallest currency unit)

    let handler = PaystackPop.setup({
        //Replace with your public key
        //key: 'pk_live_04349580eea795597762b0e6242736891e6a0faa',
        key: 'pk_test_3a243aa0a24572b40ef92531641e5809cd500d3b',
        email: email,
        first_name: first_name,
        last_name: last_name,
        amount: amount,
        currency: 'GHS',
        split_code: 'SPL_ckaKku9FKI', //FADSA Split code
        ref: 'Gili' + Math.floor((Math.random() * 1000000000) + 1), // Generate a unique reference

        onClose: function () {
            alert('Payment cancelled.');
        },

        callback: function (response) {
            if (response.status === 'success') {
                submitForm(response.reference);
            } else {
                showTransactionErrorNotification();
            }
        }
    });
    handler.openIframe();
}

function submitForm(reference) {
    let form = document.getElementById('paymentForm');
    let formData = new FormData(form);
    formData.append('reference', reference);

    fetch('process_vote.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            showGoodNotification();
            setTimeout(() => {
                window.location.href = 'index.php';
            }, 1000);
        } else if(data.status ===  'database_error') {
            console.log(data.status);
            showDataBaseErrorNotification();
        } else if(data.status === 'transaction_error') {
            console.log(data.status);
            showTransactionErrorNotification();
        } else if(data.status === 'error') {
            console.log(data.status);
            showBadNotification();
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('An error occurred. Please try again later.')
    });
}

function calculateTotal(cost_per_vote) {
  const votes = document.getElementById('votes').value;
  const totalAmount = votes * cost_per_vote; // calculateTotal per vote
  document.getElementById('amount').innerText = totalAmount.toFixed(2);
}

function showGoodNotification() {
    var notificationBox = document.getElementById("good_notification");
    notificationBox.style.display = 'block';
    setTimeout(function() {
        notificationBox.style.display = 'none';
    }, 3000); // Hide after 3 seconds
}

function showBadNotification() {
    var notificationBox = document.getElementById("bad_notification");
    notificationBox.style.display = 'block';
    setTimeout(function() {
        notificationBox.style.display = 'none';
    }, 3000); // Hide after 3 seconds
}

function showDataBaseErrorNotification(){
    var notificationBox = document.getElementById("database_error_notification");
    notificationBox.style.display = 'block';
    setTimeout(function() {
        notificationBox.style.display = 'none';
    }, 3000); // Hide after 3 seconds
}

function showTransactionErrorNotification(){
    var notificationBox = document.getElementById("transaction_error_notification");
    notificationBox.style.display = 'block';
    setTimeout(function() {
        notificationBox.style.display = 'none';
    }, 3000); // Hide after 3 seconds
}