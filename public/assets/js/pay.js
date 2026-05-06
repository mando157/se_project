const data = {
    base: booking.price_per_hour,
    hours: booking.hours,
    surcharge: 0,
    service: 0
};

function calculateTotal() {

    const base = data.base * data.hours;
    const total = base + data.surcharge + data.service;

    document.getElementById("base").innerText = "$" + base.toFixed(2);
    document.getElementById("surcharge").innerText = "$0.00";
    document.getElementById("service").innerText = "$0.00";
    document.getElementById("total").innerText = "$" + total.toFixed(2);
}

calculateTotal();

// ================= TOGGLE FORM =================
const cardForm = document.getElementById('cardForm');

function toggleForm(show) {
    if (!cardForm) return;
    cardForm.style.display = show ? "block" : "none";
}

// default state
toggleForm(true);

// ================= PAYMENT =================
const line = document.getElementById('line');
const step2 = document.getElementById('step-2');

function handlePayment() {
    if (line && step2) {
        line.style.background = "linear-gradient(135deg, var(--primary), var(--primary2))";
        step2.style.background = "linear-gradient(135deg, var(--primary), var(--primary2))";
    }

    alert("Payment Successful ✅");
}

// ================= CARD NUMBER FORMAT =================
const cardInput = document.getElementById('number');

if (cardInput) {
    cardInput.addEventListener("input", function (e) {
        let value = e.target.value.replace(/\D/g, "");
        value = value.substring(0, 16);
        value = value.match(/.{1,4}/g)?.join(" ") || "";
        e.target.value = value;
    });
}

// ================= EXPIRY FORMAT =================
const expiryInput = document.getElementById('expiry');

if (expiryInput) {
    expiryInput.addEventListener("input", function (e) {
        let value = e.target.value.replace(/\D/g, "");
        value = value.substring(0, 4);

        if (value.length > 2) {
            value = value.substring(0, 2) + " / " + value.substring(2);
        }

        e.target.value = value;
    });
}