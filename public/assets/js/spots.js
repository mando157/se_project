document.addEventListener("DOMContentLoaded", function () {

  function toggleSidebar() {
    document.querySelector(".sidebar")?.classList.toggle("hide");
    document.querySelector(".main-content")?.classList.toggle("full");
  }

  window.toggleSidebar = toggleSidebar;


  function setActive(el) {
    document.querySelectorAll(".sidebar a")
      .forEach(link => link.classList.remove("active"));

    el.classList.add("active");
  }

  window.setActive = setActive;


  const switchBtn = document.getElementById("toggle");

  if (switchBtn) {
    switchBtn.addEventListener("click", () => {
      switchBtn.classList.toggle("active");
    });
  }

  const autoRenewToggle = document.getElementById("toggleAutoRenew");

  if (autoRenewToggle) {
    let isActive = true;

    autoRenewToggle.classList.add("active");

    autoRenewToggle.addEventListener("click", () => {
      isActive = !isActive;

      autoRenewToggle.classList.toggle("active", isActive);

      alert(
        isActive
          ? " Auto-Renew Enabled"
          : " Auto-Renew Disabled"
      );
    });
  }




  const wizardBtn = document.getElementById("launchWizardBtn");

  if (wizardBtn) {
    wizardBtn.addEventListener("click", () => {
      alert(
        " Welcome to Setup Wizard!\n\n" +
        "Step 1: Enter location details\n" +
        "Step 2: Set price & availability\n" +
        "Step 3: Publish instantly\n\n" +
        "Done in under 2 minutes!"
      );
    });
  }

});
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