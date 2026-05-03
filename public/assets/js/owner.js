let chart;

window.onload = function () {
  setWeekly();
};


function setWeekly(){
  setChartActive("weekly");

  drawChart(
    ['Sat','Sun','Mon','Tue','Wed','Thu','Fri'],
    [1200,1500,1800,2200,2000,2500,2700]
  );
}

function setMonthly(){
  setChartActive("monthly");

  drawChart(
    ['Week 1','Week 2','Week 3','Week 4'],
    [8000,9500,11000,12000]
  );
}

function setChartActive(type){
  document.getElementById("weeklyBtn").classList.remove("active");
  document.getElementById("monthlyBtn").classList.remove("active");

  if(type === "weekly"){
    document.getElementById("weeklyBtn").classList.add("active");
  }else{
    document.getElementById("monthlyBtn").classList.add("active");
  }
}

function drawChart(labels, data){
  const ctx = document.getElementById('revenueChart');

  if(chart){
    chart.destroy();
  }

  chart = new Chart(ctx, {
    type: 'line',
    data: {
      labels: labels,
      datasets: [{
        data: data,
        borderWidth: 2,
        tension: 0.4
      }]
    },
    options: {
      plugins: {
        legend: { display: false }
      }
    }
  });
}



function setActive(el){
  document.querySelectorAll(".sidebar a")
    .forEach(link => link.classList.remove("active"));

  el.classList.add("active");
}

function toggleSidebar(){
  document.querySelector(".sidebar").classList.toggle("hide");
  document.querySelector(".main-content").classList.toggle("full");
}
function openModal(){
  document.getElementById("modal").classList.add("show");
}

function closeModal(){
  document.getElementById("modal").classList.remove("show");
}

window.openModal = openModal;
window.closeModal = closeModal;
window.addEventListener("click", function(e){
  const modal = document.getElementById("modal");
  if(e.target === modal){
    modal.classList.remove("show");
  }
});