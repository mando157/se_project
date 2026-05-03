// ===== TIMER =====
let time = 900; // 15 min

setInterval(() => {
    let min = Math.floor(time / 60);
    let sec = time % 60;

    document.getElementById("timer").innerText =
        `${String(min).padStart(2, '0')}:${String(sec).padStart(2, '0')}`;

    if (time > 0) time--;
}, 1000);
