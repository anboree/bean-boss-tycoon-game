// Game object
const game = {
    money: 0,

    day: 1,
    hour: 7, // Start at 7 AM
    minute: 0,
    isOpen: true,
    isPaused: false,
};

// Upgrades
const upgrades = {
    // Level 1
    coffeeMachine: {
        name: "Coffee Machine",
        cost: 50,
        owned: false
    },
    businessSign: {
        name: "Business Sign",
        cost: 120,
        owned: false
    },
    hireBarista: {
        name: "Part-Time Barista",
        cost: 100,
        owned: false
    },
    premiumBeans: {
        name: "Premium Beans",
        cost: 400,
        owned: false
    },
    biggerStand: {
        name: "Bigger Coffee Stand",
        cost: 800,
        owned: false
    }
};

// All constants required for game
const moneySpan = document.getElementById("money");
const daySpan = document.getElementById("currentDay");
const timeSpan = document.getElementById("currentTime");
const brewBtn = document.getElementById("brewBtn");
const pauseBtn = document.getElementById("pauseBtn");
const activityBox = document.getElementById("activity-box");
const levelFinalUpgrade = document.getElementById("levelFinalUpgrade");

// Function for activity box messages
function addActivityMessage(message) {
    const p = document.createElement("p");
    p.textContent = message;
    p.classList.add("activity-text");

    activityBox.append(p);
    activityBox.scrollTop = activityBox.scrollHeight; // Auto-scroll down
}

// Click value function
function getClickValue() {
    let min = 1;
    let max = 1;

    if (upgrades.coffeeMachine.owned) {
        min = 1;
        max = 3;
    }

    if (upgrades.premiumBeans.owned) {
        if (upgrades.coffeeMachine.owned) {
            min = 3;
            max = 6;
        } else {
            min = 3;
            max = 3;
        }
    }

    let value = Math.floor(Math.random() * (max - min + 1)) + min;

    // Business sign multiplier
    if (upgrades.businessSign.owned) {
        value = Math.floor(value * 1.1);
    }

    return value;
}

// Passive income
setInterval(() => {
    if(game.isPaused || !game.isOpen) return;

    if(upgrades.hireBarista.owned) {
        game.money += 1;
        updateUI();
    }
}, 2000);

// Function to update money
function updateUI(){
    moneySpan.textContent = game.money;
    daySpan.textContent = game.day;
    timeSpan.textContent = formatTime(game.hour, game.minute);
}

// Pause game logic
function updatePauseUI() {
    if(game.isPaused){
        pauseBtn.src = "assets/resume-icon.png";
        addActivityMessage("The game is currently paused!");
    } 
    else{
        pauseBtn.src = "assets/pause-icon.png";
    }
}

pauseBtn.addEventListener("click", function () {
    game.isPaused = !game.isPaused;

    updatePauseUI();
});

// Time formatting
function formatTime(hour, minute) {
    let ampm = hour >= 12 ? "PM" : "AM";
    let displayHour = hour % 12;
    if (displayHour === 0) displayHour = 12;

    let displayMinute = minute.toString().padStart(2, "0");

    return `${displayHour}:${displayMinute} ${ampm}`;
}

// Clock system
function advanceTime() {
    if(game.isPaused) return;

    game.minute += 1;

    if (game.minute >= 60) {
        game.minute = 0;
        game.hour += 1;
    }

    // Close shop at 6 PM (18:00)
    if (game.hour >= 18) {
        endDay();
    }

    updateUI();
}

// End of day logic
function endDay() {
    game.isOpen = false;
    game.day += 1;
    game.hour = 7;
    game.minute = 0;
    game.isOpen = true;
}

setInterval(advanceTime, 300); // 0.3 seconds = 1 in-game minute

// Added functionality for brew button
brewBtn.addEventListener("click", function () {
    if(game.isPaused || !game.isOpen) return;

    const earned = getClickValue();
    game.money += earned;

    addActivityMessage(`You brewed coffee and earned $${earned}!`);
    updateUI();
});

// Upgrade buying logic
function buyUpgrade(upgradeKey) {
    const upgrade = upgrades[upgradeKey];

    if(game.isPaused || !game.isOpen) return;
    if(upgrade.owned) return;

    // Prevents buying Bigger Stand early
    if(upgradeKey === "biggerStand" && !canUnlockNextLevel()) {
        addActivityMessage("You must buy all upgrades first!");
        return;
    }

    if(game.money >= upgrade.cost){
        game.money -= upgrade.cost;
        upgrade.owned = true;

        addActivityMessage(`You bought ${upgrade.name}!`);

        // Level unlock logic
        if (upgradeKey === "biggerStand") {
            unlockLevel2();
        }

        updateUI();
    } 
    else{
        addActivityMessage("Not enough money!");
    }
}

// Checks if all upgrades required for level 2 are owned
function canUnlockNextLevel() {
    return(
        upgrades.coffeeMachine.owned &&
        upgrades.businessSign.owned &&
        upgrades.hireBarista.owned &&
        upgrades.premiumBeans.owned
    );
}

if(!canUnlockNextLevel()){
    levelFinalUpgrade.disabled = true;
}

function unlockLevel2() {
    addActivityMessage("🎉 You unlocked Level 2!");
    document.getElementById("upgrades-level").textContent = 2;

    // Later: load new upgrades, reset shop, etc.
}

updateUI();