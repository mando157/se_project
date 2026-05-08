const countdowns = document.querySelectorAll(".countdown");

countdowns.forEach(counter => {

    const bookingDate = counter.dataset.date;
    const startTime = counter.dataset.start;
    const endTime = counter.dataset.end;
    const bookingId = counter.dataset.booking;

    const start = new Date(`${bookingDate}T${startTime}`).getTime();
    const end = new Date(`${bookingDate}T${endTime}`).getTime();

    let finished = false;

    const timer = setInterval(() => {

        const now = Date.now();

        // ================= BEFORE START =================
        if (now < start) {
            counter.innerHTML = "Waiting For Booking Time";
            return;
        }

        // ================= AFTER END =================
        if (now >= end) {
            counter.innerHTML = "Booking Ended";
            clearInterval(timer);

            if (!finished) {
                finished = true;

                fetch(`http://localhost/ParkFlow/Driver/completeBooking?booking_id=${bookingId}`);
            }

            return;
        }

        // ================= ACTIVE BOOKING =================
        const distance = end - now;

        const hours = Math.floor(distance / (1000 * 60 * 60));
        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        counter.innerHTML = `${hours}h ${minutes}m ${seconds}s`;

    }, 1000);
});