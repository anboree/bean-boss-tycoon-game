// Game object
const game = {
    money: 0,
    beans: 250,
    day: 1,
    hour: 7, // Start at 7 AM
    minute: 0,
    isOpen: true,
    isPaused: true,
    noBeansMessageShown: false
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
        icon: "assets/espresso-beans-upgrade.png",
        description: "Unlocks Espresso Machine",
        owned: false
    },
    {
        key: "espressoMachine",
        name: "Espresso Machine",
        cost: 2000,
        level: 2,
        icon: "assets/espresso-machine-upgrade.png",
        description: "New, Earns $5 - $10 per click",
        owned: false
    },
    {
        key: "hireFullTimeBarista",
        name: "Full-Time Barista",
        cost: 200,
        level: 2,
        icon: "assets/hire-full-time-barista-upgrade.png",
        description: "Earns $4 per 2 seconds",
        owned: false
    },
    {
        key: "biggerBusinessSign",
        name: "Bigger Business Sign",
        cost: 600,
        level: 2,
        icon: "assets/bigger-business-sign-upgrade.png",
        description: "+20% more money",
        owned: false
    },
    {
        key: "smallCoffeeShop",
        name: "Small Coffee Shop",
        cost: 3000,
        level: 2,
        icon: "assets/small-coffee-shop-upgrade.png",
        description: "Unlocks Level 3, Increased rent price",
        owned: false
    },
    // Level 3
    {
        key: "newMenu",
        name: "New Menu",
        cost: 5000,
        level: 3,
        icon: "assets/",
        description: "+$2 per click",
        owned: false
    },
    {
        key: "advancedCoffeeMachine",
        name: "Advanced Coffee Machine",
        cost: 8000,
        level: 3,
        icon: "assets/",
        description: "New, Earns $10 - $18 per click",
        owned: false
    },
    {
        key: "hireManager",
        name: "Manager",
        cost: 500,
        level: 3,
        icon: "assets/",
        description: "Earns $8 per 2 seconds",
        owned: false
    },
    {
        key: "betterBranding",
        name: "Better Branding",
        cost: 3500,
        level: 3,
        icon: "assets/",
        description: "+25% more money",
        owned: false
    },
    {
        key: "mediumCoffeeShop",
        name: "Medium Coffee Shop",
        cost: 15000,
        level: 3,
        icon: "assets/",
        description: "Unlocks Level 4, Increased rent price",
        owned: false
    },
    // Level 4
    {
        key: "onlineOrders",
        name: "Online Orders",
        cost: 12000,
        level: 4,
        icon: "assets/",
        description: "Earns random passive income",
        owned: false
    },
    {
        key: "hireProBarista",
        name: "Professional Barista",
        cost: 1000,
        level: 4,
        icon: "assets/",
        description: "Earns $12 per 2 seconds",
        owned: false
    },
    {
        key: "advertising",
        name: "Local Advertising",
        cost: 8000,
        level: 4,
        icon: "assets/",
        description: "+30% more money",
        owned: false
    },
    {
        key: "betterOnlineServer",
        name: "Better Online Server",
        cost: 20000,
        level: 4,
        icon: "assets/",
        description: "Increases income from online orders",
        owned: false
    },
    {
        key: "largeCoffeeShop",
        name: "Large Coffee Shop",
        cost: 40000,
        level: 4,
        icon: "assets/",
        description: "Unlocks Level 5, Increased rent price",
        owned: false
    },
    // Level 5
    {
        key: "futuristicCoffeeMachine",
        name: "Futuristic Coffee Machine",
        cost: 30000,
        level: 5,
        icon: "assets/",
        description: "Earns $15 - $25 per click",
        owned: false
    },
    {
        key: "socialMediaMarketing",
        name: "Social Media Marketing",
        cost: 20000,
        level: 5,
        icon: "assets/",
        description: "+40% more money",
        owned: false
    },
    {
        key: "expandMenu",
        name: "Expand Menu",
        cost: 15000,
        level: 5,
        icon: "assets/",
        description: "+$5 per click",
        owned: false
    },
    {
        key: "orderAutomation",
        name: "Order Automation",
        cost: 100000,
        level: 5,
        icon: "assets/",
        description: "Auto click every 4 seconds",
        owned: false
    },
    {
        key: "coffeeEmpire",
        name: "Coffee Empire",
        cost: 1000000,
        level: 5,
        icon: "assets/",
        description: "FINISHES THE GAME",
        owned: false
    }
];

// Bean Store
const beanStore = [
    { amount: 50, cost: 100 },
    { amount: 100, cost: 190 },
    { amount: 500, cost: 900 },
    { amount: 1000, cost: 1700 },
    { amount: 5000, cost: 5600 },
    { amount: 10000, cost: 10500 }
];

// All constants required for game
const moneySpan = document.getElementById("money");
const daySpan = document.getElementById("currentDay");
const timeSpan = document.getElementById("currentTime");
const brewBtn = document.getElementById("brewBtn");
const pauseBtn = document.getElementById("pauseBtn");
const activityBox = document.getElementById("activity-box");
const upgradesContainer = document.getElementById("upgradesContainer");
const beansSpan = document.getElementById("beans");
const storeBox = document.getElementById("store-box");

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

        // If owned then change appearance
        if(upg.owned){
            btn.classList.add("purchased");
            btn.disabled = true;

            btn.innerHTML = `
                ${upg.name} - PURCHASED
                <img src="${upg.icon}" width="50" height="50" style="display: block; margin: 0 auto">
            `;
        } else {
            btn.innerHTML = `
                Buy ${upg.name} - $${upg.cost} (${upg.description})
                <img src="${upg.icon}" width="50" height="50" style="display: block; margin: 0 auto">
            `;

            btn.onclick = () => buyUpgrade(upg.key);
        }

        upgradesContainer.appendChild(btn);
    });
}

// Function for rendering Bean Store
function renderBeanStore(){
    beanStore.forEach(item => {
        const btn = document.createElement("button");
        btn.classList.add("storeBtn");

        btn.textContent = `${item.amount} Beans - $${item.cost}`;

        btn.onclick = () => buyBeans(item);

        storeBox.appendChild(btn);
    });
}

// Function for buying beans
function buyBeans(item){
    if(game.isPaused || !game.isOpen) return;

    if(game.money >= item.cost){
        game.money -= item.cost;
        game.beans += item.amount;

        addActivityMessage(`You bought ${item.amount} beans!`);
        updateUI();
    } 
    else{
        addActivityMessage("Not enough money!");
    }
}

// Function that determines how many beans are removed with each click
function getBeanCost(){
    let cost = 1;

    if(getUpgrade("espressoMachine").owned){
        cost = 2;
    }

    if(getUpgrade("advancedCoffeeMachine").owned){
        cost = 4;
    }

    if(getUpgrade("futuristicCoffeeMachine").owned){
        cost = 8;
    }

    return cost;
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
function getClickValue(){
    let min = 1;
    let max = 1;

    // LEVEL 1
    if(getUpgrade("coffeeMachine").owned){
        min = 1;
        max = 3;
    }

    if(getUpgrade("premiumBeans").owned){
        if(getUpgrade("coffeeMachine").owned){
            min = 3;
            max = 6;
        } else {
            min = 3;
            max = 3;
        }
    }

    // LEVEL 2 (OVERRIDES LEVEL 1)
    if(getUpgrade("espressoMachine").owned){
        min = 5;
        max = 10;
    }

    // LEVEL 3 (OVERRIDES LEVEL 2)
    if(getUpgrade("advancedCoffeeMachine").owned){
        min = 10;
        max = 18;
    }

    // LEVEL 5 (OVERRIDES LEVEL 3)
    if(getUpgrade("futuristicCoffeeMachine").owned){
        min = 15;
        max = 25;
    }

    // Multipliers
    let value = Math.floor(Math.random() * (max - min + 1)) + min;

    if(getUpgrade("businessSign").owned){
        value *= 1.1;
    }

    if(getUpgrade("biggerBusinessSign").owned){
        value *= 1.2;
    }

    if(getUpgrade("betterBranding").owned){
        value *= 1.25;
    }

    if(getUpgrade("newMenu").owned){
        value += 2;
    }

    if(getUpgrade("advertising").owned){
        value *= 1.3;
    }

    if(getUpgrade("socialMediaMarketing").owned){
        value *= 1.4;
    }

    if(getUpgrade("expandMenu").owned){
        value += 5;
    }

    return Math.ceil(value);
}

// Passive income
setInterval(() => {
    if(game.isPaused || !game.isOpen) return;

    let income = 0;

    if(getUpgrade("hireBarista").owned){
        income += 1;
    }

    if(getUpgrade("hireFullTimeBarista").owned){
        income += 4;
    }

    if(getUpgrade("hireManager").owned){
        income += 8;
    }

    if(getUpgrade("hireProBarista").owned){
        income += 12;
    }

    // Online Orders (random income)
    if(getUpgrade("onlineOrders").owned){
        let onlineIncome = Math.floor(Math.random() * 5) + 3; // $3 – $7

        // Boost with server upgrade
        if(getUpgrade("betterOnlineServer").owned){
            onlineIncome *= 2;
        }

        income += onlineIncome;
    }

    if(income > 0){
        game.money += income;
        
        if(Math.random() < 0.3){ // 30% chance to log (reducing spammy messages)
            addActivityMessage(`Your business earned $${income}`);
        }

        updateUI();
    }

}, 2000);

// Auto click logic for 'Order Automation' upgrade
setInterval(() => {
    if(game.isPaused || !game.isOpen) return;
    if(!getUpgrade("orderAutomation").owned) return;

    const beanCost = getBeanCost();

    if(game.beans < beanCost) return;

    game.beans -= beanCost;

    const earned = getClickValue();
    game.money += earned;

    addActivityMessage(`Auto order earned $${earned}`);
    updateUI();

}, 4000);

// Function to update money
function updateUI(){
    moneySpan.textContent = game.money;
    beansSpan.textContent = game.beans;
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
    game.isPaused = true;
    showEndOfDayPopup();
}

function showEndOfDayPopup(){
    const rent = calculateRent();
    const workerExpenses = calculateWorkerExpenses();
    const total = rent + workerExpenses;

    let message = `
    END OF DAY ${game.day}
    
    Rent: ${rent}
    Worker Expenses: ${workerExpenses}
    Total: ${total}
    `;

    game.money -= total;

    if(game.money < 0){
        message += `\n\n⚠️You are in debt! Get it together or you will go bankrupt soon!⚠️`;
    }

    alert(message);
    startNextDay();
}

function startNextDay(){
    game.day += 1;
    game.hour = 7;
    game.minute = 0;
    game.isOpen = true;
    game.isPaused = false;

    updateUI();
}

setInterval(advanceTime, 300); // 0.3 seconds = 1 in-game minute

// Calculating end of day expenses
function calculateRent(){
    if(getUpgrade("largeCoffeeShop").owned) return 1200;
    if(getUpgrade("mediumCoffeeShop").owned) return 500;
    if(getUpgrade("smallCoffeeShop").owned) return 250;
    if(getUpgrade("biggerCoffeeStand").owned) return 100;

    return 50; // Base rent
}

function calculateWorkerExpenses(){
    let workerExpenses = 0;

    if(getUpgrade("hireBarista").owned) workerExpenses += 100;
    if(getUpgrade("hireFullTimeBarista").owned) workerExpenses += 200;
    if(getUpgrade("hireManager").owned) workerExpenses += 500;
    if(getUpgrade("hireProBarista").owned) workerExpenses += 1000;

    return workerExpenses;
}

// Added functionality for brew button
brewBtn.addEventListener("click", function () {
    if(game.isPaused || !game.isOpen) return;

    const beanCost = getBeanCost();

    // Doesn't spam message
    if(game.beans < beanCost){
        if(!game.noBeansMessageShown){
            addActivityMessage(`You need ${beanCost} beans to brew coffee!`);
            game.noBeansMessageShown = true;
        }
        return;
    }
    else{
        game.noBeansMessageShown = false;
    }

    // Remove beans
    game.beans -= beanCost;

    const earned = getClickValue();
    game.money += earned;

    addActivityMessage(`You brewed coffee and earned $${earned}!`);
    updateUI();
});

// Upgrade buying logic
function buyUpgrade(upgradeKey){
    const upgrade = getUpgrade(upgradeKey);

    if(game.isPaused || !game.isOpen) return;
    if(upgrade.owned) return;

    // Level 1 final upgrade
    if(upgradeKey === "biggerCoffeeStand" && !canUnlockLevel2()) {
        addActivityMessage("You must buy all Level 1 upgrades first!");
        return;
    }

    // Level 2 final upgrade
    if(upgradeKey === "smallCoffeeShop" && !canUnlockLevel3()) {
        addActivityMessage("You must buy all Level 2 upgrades first!");
        return;
    }

    // Level 3 final upgrade
    if(upgradeKey === "mediumCoffeeShop" && !canUnlockLevel4()) {
        addActivityMessage("You must buy all Level 3 upgrades first!");
        return;
    }

    // Level 4 final upgrade
    if(upgradeKey === "largeCoffeeShop" && !canUnlockLevel5()){
        addActivityMessage("You must buy all Level 4 upgrades first!");
        return;
    }

    // Level 5 final upgrade (final game upgrade)
    if(upgradeKey === "coffeeEmpire" && !canFinishGame()){
        addActivityMessage("You must buy all Level 5 upgrades first!");
        return;
    }

    // Checks if Espresso Beans are owned before purchasing Espresso Machine upgrade
    if(upgradeKey === "espressoMachine" && !getUpgrade("espressoBeans").owned){
        addActivityMessage("You need Espresso Beans first!");
        return;
    }

    if(game.money >= upgrade.cost){
        game.money -= upgrade.cost;
        upgrade.owned = true;
        renderUpgrades(upgrade.level); // Re-renders upgrades after buying

        addActivityMessage(`You bought ${upgrade.name}!`);

        if(upgradeKey === "biggerCoffeeStand"){
            unlockLevel2();
        }

        if(upgradeKey === "smallCoffeeShop"){
            unlockLevel3();
        }

        if(upgradeKey === "mediumCoffeeShop"){
            unlockLevel4();
        }

        if(upgradeKey === "largeCoffeeShop"){
            unlockLevel5();
        }

        updateUI();
    } 
    else{
        addActivityMessage("Not enough money!");
    }
}

// Checks if all upgrades required for next level are owned
function canUnlockLevel2(){
    return(
        getUpgrade("coffeeMachine").owned &&
        getUpgrade("businessSign").owned &&
        getUpgrade("hireBarista").owned &&
        getUpgrade("premiumBeans").owned
    );
}

function canUnlockLevel3(){
    return(
        getUpgrade("espressoBeans").owned &&
        getUpgrade("espressoMachine").owned &&
        getUpgrade("hireFullTimeBarista").owned &&
        getUpgrade("biggerBusinessSign").owned
    );
}

function canUnlockLevel4(){
    return(
        getUpgrade("newMenu").owned &&
        getUpgrade("advancedCoffeeMachine").owned &&
        getUpgrade("hireManager").owned &&
        getUpgrade("betterBranding").owned
    );
}

function canUnlockLevel5(){
    return(
        getUpgrade("onlineOrders").owned &&
        getUpgrade("hireProBarista").owned &&
        getUpgrade("advertising").owned &&
        getUpgrade("betterOnlineServer").owned
    );
}

function canFinishGame(){
    return(
        getUpgrade("futuristicCoffeeMachine").owned &&
        getUpgrade("socialMediaMarketing").owned &&
        getUpgrade("expandMenu").owned &&
        getUpgrade("orderAutomation").owned
    );
}

function unlockLevel2(){
    addActivityMessage("🎉You unlocked Level 2!");
    renderUpgrades(2);
}

function unlockLevel3(){
    addActivityMessage("🎉You unlocked Level 3!");
    renderUpgrades(3);
}

function unlockLevel4(){
    addActivityMessage("🎉You unlocked Level 4!");
    renderUpgrades(4);
}

function unlockLevel5(){
    addActivityMessage("🎉You unlocked Level 5!");
    renderUpgrades(5);
}

function finishGame(){
    addActivityMessage("🎉YOU HAVE FINISHED THE GAME!!!");
}

renderUpgrades(1);
renderBeanStore();
updateUI();