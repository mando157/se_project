const data = {
    base: 43.5,
    surcharge: 4.5,
    service: 2,
    hours: 4,
    level: "P3 - Zone Alpha"
};

// ===== Calculate Total =====
function calculateTotal() {
    const total = data.base + data.surcharge + data.service;

    document.getElementById("base").innerText = "$" + data.base.toFixed(2);
    document.getElementById("surcharge").innerText = "$" + data.surcharge.toFixed(2);
    document.getElementById("service").innerText = "$" + data.service.toFixed(2);
    document.getElementById("total").innerText = "$" + total.toFixed(2);
    document.getElementById("totalPrice").innerText = "$" + total.toFixed(2);
}

calculateTotal();

// ===== Toggle Form (Card / Cash) =====
const cardForm = document.getElementById('cardForm')

function toggleForm(show) {
    cardForm.style.display = show ? "block" : "none";
}

// ===== Payment Simulation =====
const line = document.getElementById('line');
const step2 = document.getElementById('step-2');

function handlePayment() {
    line.style.background = "linear-gradient(135deg, var(--primary), var(--primary2))";
    step2.style.background = "linear-gradient(135deg, var(--primary), var(--primary2))";
    alert("Success ✅");
}

// ===== Card Number =====

document.getElementById('number').addEventListener("input" , function(e){
    let value = e.target.value.replace(/\D/g , "");
    value = value.match(/.{1,4}/g)?.join(" ") || value;
    e.target.value = value;
});

document.getElementById('expiry').addEventListener("input" , function(e){
    let expiry = e.target.value.replace(/\D/g , "");
    expiry = expiry.match(/.{1,2}/g)?.join(" / ") || expiry;
    e.target.value = expiry;
});

