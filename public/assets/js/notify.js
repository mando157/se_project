function updateTimer() {

    if (!startTime || !endTime || !bookingDate) {
        return;
    }

    let now = new Date();

    let start =
        new Date(
            bookingDate + "T" + startTime
        );

    let end =
        new Date(
            bookingDate + "T" + endTime
        );

    // لو end أقل من start
    // معناها معدي نص الليل
    if (end <= start) {
        end.setDate(end.getDate() + 1);
    }

    // قبل البداية
    if (now < start) {

        document.getElementById("timer")
            .innerText =
            "Waiting For Booking Time";

        return;
    }

    // بعد النهاية
    if (now >= end) {

        document.getElementById("timer")
            .innerText =
            "Expired";

        return;
    }

    // أثناء الحجز
    let diff =
        Math.floor((end - now) / 1000);

    let hours =
        Math.floor(diff / 3600);

    let minutes =
        Math.floor((diff % 3600) / 60);

    let seconds =
        diff % 60;

    document.getElementById("timer")
        .innerText =
        `${String(hours).padStart(2, '0')}:` +
        `${String(minutes).padStart(2, '0')}:` +
        `${String(seconds).padStart(2, '0')}`;
}

setInterval(updateTimer, 1000);

updateTimer();