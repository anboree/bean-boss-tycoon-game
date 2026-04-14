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
const upgrades = [
    // Level 1
    {
        key: "coffeeMachine",
        name: "Coffee Machine",
        cost: 80,
        level: 1,
        icon: "assets/used-coffee-machine-upgrade.png",
        description: "Used, Earns $1 - $3 per click",
        owned: false
    },
    {
        key: "businessSign",
        name: "Business Sign",
        cost: 120,
        level: 1,
        icon: "assets/coffee-business-sign-upgrade.png",
        description: "+10% more money",
        owned: false
    },
    {
        key: "hireBarista",
        name: "Part-Time Barista",
        cost: 100,
        level: 1,
        icon: "assets/hire-part-time-barista-upgrade.png",
        description: "Earns $1 per 2 seconds",
        owned: false
    },
    {
        key: "premiumBeans",
        name: "Premium Beans",
        cost: 500,
        level: 1,
        icon: "assets/premium-coffee-beans-upgrade.png",
        description: "Earns more money",
        owned: false
    },
    {
        key: "biggerCoffeeStand",
        name: "Bigger Coffee Stand",
        cost: 1000,
        level: 1,
        icon: "assets/bigger-coffee-stand-upgrade.png",
        description: "Unlocks Level 2, Increased rent price",
        owned: false
    },
    // Level 2
    {
        key: "espressoBeans",
        name: "Espresso Beans",
        cost: 1200,
        level: 2,
        icon: "assets/",
        description: "Unlocks Espresso Machine",
        owned: false
    },
    {
        key: "espressoMachine",
        name: "Espresso Machine",
        cost: 2000,
        level: 2,
        icon: "assets/",
        description: "New, Earns $5 - $10 per click",
        owned: false
    },
    {
        key: "hireFullTimeBarista",
        name: "Full-Time Barista",
        cost: 200,
        level: 2,
        icon: "assets/",
        description: "Earns $4 per 2 seconds",
        owned: false
    },
    {
        key: "biggerBusinessSign",
        name: "Bigger Business Sign",
        cost: 600,
        level: 2,
        icon: "assets/",
        description: "+20% more money",
        owned: false
    },
    {
        key: "smallCoffeeShop",
        name: "Small Coffee Shop",
        cost: 3000,
        level: 2,
        icon: "assets/",
        description: "Unlocks Level 3, Increased rent price",
        owned: false
    }
    // Level 3
];

// All constants required for game
const moneySpan = document.getElementById("money");
const daySpan = document.getElementById("currentDay");
const timeSpan = document.getElementById("currentTime");
const brewBtn = document.getElementById("brewBtn");
const pauseBtn = document.getElementById("pauseBtn");
const activityBox = document.getElementById("activity-box");
const upgradesContainer = document.getElementById("upgradesContainer");

// Helper function for upgrades
function getUpgrade(key){
    return upgrades.find(upg => upg.key === key);
}

// Function for dynamically rendering upgrades
function renderUpgrades(level){
    upgradesContainer.innerHTML = `<span id="upgradesLevelText">Level ${level} Upgrades</span>`;

    upgrades.filter(upg => upg.level === level).forEach(upg => {
            const btn = document.createElement("button");
            btn.classList.add("upgradesBtn");

            btn.innerHTML = `
                Buy ${upg.name} - $${upg.cost} (${upg.description})
                <img src="${upg.icon}" width="50" height="50" style="display: block; margin: 0 auto">
            `;

            btn.onclick = () => buyUpgrade(upg.key);

            upgradesContainer.appendChild(btn);
        });
}

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

    if(getUpgrade("coffeeMachine").owned){
        min = 1;
        max = 3;
    }

    if(getUpgrade("premiumBeans").owned){
        if (getUpgrade("coffeeMachine").owned){
            min = 3;
            max = 6;
        } else {
            min = 3;
            max = 3;
        }
    }

    let value = Math.floor(Math.random() * (max - min + 1)) + min;

    // Business sign multiplier
    if(getUpgrade("businessSign").owned){
        value = Math.ceil(value * 1.1);
    }

    return value;
}

// Passive income
setInterval(() => {
    if(game.isPaused || !game.isOpen) return;

    if(getUpgrade("hireBarista").owned){
        game.money += 1;
        addActivityMessage("Your barista earned $1");
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
function updatePauseUI(){
    if(game.isPaused){
        pauseBtn.src = "assets/resume-icon.png";
        addActivityMessage("Game paused");
    } 
    else{
        pauseBtn.src = "assets/pause-icon.png";
        addActivityMessage("Game resumed");
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
    const upgrade = getUpgrade(upgradeKey);

    if(game.isPaused || !game.isOpen) return;
    if(upgrade.owned) return;

    if(upgradeKey === "biggerCoffeeStand" && !canUnlockNextLevel()) {
        addActivityMessage("You must buy all upgrades first!");
        return;
    }

    if(game.money >= upgrade.cost){
        game.money -= upgrade.cost;
        upgrade.owned = true;

        addActivityMessage(`You bought ${upgrade.name}!`);

        if (upgradeKey === "biggerCoffeeStand") {
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
        getUpgrade("coffeeMachine").owned &&
        getUpgrade("businessSign").owned &&
        getUpgrade("hireBarista").owned &&
        getUpgrade("premiumBeans").owned
    );
}

function unlockLevel2() {
    addActivityMessage("🎉You unlocked Level 2!");
    renderUpgrades(2);
}

renderUpgrades(1);
game.isPaused = true;
updateUI();