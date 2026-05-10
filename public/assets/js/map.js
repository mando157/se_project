const searchInput =
    document.getElementById("searchInput");

const cards =
    document.querySelectorAll(".parking-card");

searchInput.addEventListener("input", () => {

    const value =
        searchInput.value.toLowerCase();

    cards.forEach(card => {

        const parkingName =
            card.querySelector(".parking-name")
            .innerText
            .toLowerCase();

        if (parkingName.includes(value)) {

            card.style.display = "block";

        } else {

            card.style.display = "none";

        }

    });

});
