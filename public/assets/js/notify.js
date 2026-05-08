// function updateTimer() {
//     let now = new Date();
//     let today =
//         now.toISOString().split('T')[0];
//     let end =
//         new Date(today + "T" + endTime);
//     let diff =
//         Math.floor((end - now) / 1000);

//     if (diff <= 0) {
//         document.getElementById("timer")
//         .innerText = "Expired";
//         return;
//     }

//     let hours =
//         Math.floor(diff / 3600);
//     let minutes =
//         Math.floor((diff % 3600) / 60);
//     let seconds =
//         diff % 60;

//     document.getElementById("timer")
//     .innerText =
//         `${String(hours).padStart(2, '0')}:` +
//         `${String(minutes).padStart(2, '0')}:` +
//         `${String(seconds).padStart(2, '0')}`;
// }

// setInterval(updateTimer, 1000);
// updateTimer();