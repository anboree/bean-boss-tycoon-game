// Starting money value
let money = 0;

// Necessary variables & constants for the game
const moneySpan = document.getElementById("money");
const brewBtn = document.getElementById("brewBtn");

// Function to update UI
function updateMoneyDisplay() {
    moneySpan.textContent = money;
}

// Click event
brewBtn.addEventListener("click", function () {
    money += 1;
    updateMoneyDisplay();
});

// Initial display
updateMoneyDisplay();