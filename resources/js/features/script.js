// ========================================
// PSARECO Core Functions - Full Version
// without Machine Type
// ========================================

// ---------- INVENTORY (with cost price) ----------
function getInventory() {
    const data = localStorage.getItem("inventory");
    return data ? JSON.parse(data) : [];
}

function saveInventory(inventory) {
    localStorage.setItem("inventory", JSON.stringify(inventory));
}

function addInventoryItem(item) {
    const inventory = getInventory();
    const newId = inventory.length > 0 ? Math.max(...inventory.map(i => i.id)) + 1 : 1;
    item.id = newId;
    if (!item.reorderLevel) item.reorderLevel = 10;
    if (!item.expirationDate) item.expirationDate = null;
    if (!item.costPrice) item.costPrice = item.price * 0.7;
    item.createdAt = new Date().toISOString();
    inventory.push(item);
    saveInventory(inventory);
    return item;
}

function updateInventoryItem(id, updates) {
    const inventory = getInventory();
    const index = inventory.findIndex(i => i.id === id);
    if (index !== -1) {
        inventory[index] = { ...inventory[index], ...updates };
        saveInventory(inventory);
        return true;
    }
    return false;
}

function deleteInventoryItem(id) {
    let inventory = getInventory();
    inventory = inventory.filter(i => i.id !== id);
    saveInventory(inventory);
}

function checkLowStock() {
    const inventory = getInventory();
    return inventory.filter(item => item.qty <= (item.reorderLevel || 10));
}

function checkExpiringSoon(daysThreshold = 30) {
    const inventory = getInventory();
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    return inventory.filter(item => {
        if (!item.expirationDate) return false;
        const expDate = new Date(item.expirationDate);
        if (expDate < today) return false;
        const daysDiff = Math.ceil((expDate - today) / (1000 * 60 * 60 * 24));
        return daysDiff <= daysThreshold && daysDiff >= 0;
    });
}

function isProductExpired(productId) {
    const inventory = getInventory();
    const item = inventory.find(i => i.id === productId);
    if (!item || !item.expirationDate) return false;
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    const expDate = new Date(item.expirationDate);
    return expDate < today;
}

// ---------- MACHINERY (without type, with status & maintenance) ----------
function getMachines() {
    let machines = JSON.parse(localStorage.getItem("machines")) || [];

    if (machines.length === 0) {
        const defaultMachines = [
            { id: 1, name: "Hand Tractor", model: "HT-2000", totalUnits: 3, bookedUnits: 0, dailyRate: 1500, status: "Operational", lastMaintenanceDate: "2025-01-15", createdAt: new Date().toISOString(), maintenanceRecords: [] },
            { id: 2, name: "Rice Harvester", model: "RH-3000", totalUnits: 1, bookedUnits: 0, dailyRate: 3500, status: "Operational", lastMaintenanceDate: "2025-02-10", createdAt: new Date().toISOString(), maintenanceRecords: [] },
            { id: 3, name: "Thresher", model: "TH-150", totalUnits: 2, bookedUnits: 0, dailyRate: 1200, status: "Operational", lastMaintenanceDate: "2025-01-20", createdAt: new Date().toISOString(), maintenanceRecords: [] },
            { id: 4, name: "Water Pump", model: "WP-5HP", totalUnits: 4, bookedUnits: 0, dailyRate: 800, status: "Operational", lastMaintenanceDate: "2025-02-01", createdAt: new Date().toISOString(), maintenanceRecords: [] },
            { id: 5, name: "Grain Rice Dryer", model: "GRD-500", totalUnits: 1, bookedUnits: 0, dailyRate: 2500, status: "Operational", lastMaintenanceDate: "2025-01-05", createdAt: new Date().toISOString(), maintenanceRecords: [] },
            { id: 6, name: "Knapsack Sprayer", model: "KS-20", totalUnits: 5, bookedUnits: 0, dailyRate: 500, status: "Operational", lastMaintenanceDate: "2025-02-12", createdAt: new Date().toISOString(), maintenanceRecords: [] }
        ];
        localStorage.setItem("machines", JSON.stringify(defaultMachines));
        return defaultMachines;
    }

    let needsSave = false;
    machines = machines.map(m => {
        let updated = { ...m };
        if (updated.totalUnits === undefined || updated.totalUnits === null) {
            updated.totalUnits = 1;
            needsSave = true;
        }
        if (updated.bookedUnits === undefined || updated.bookedUnits === null) {
            updated.bookedUnits = 0;
            needsSave = true;
        }
        if (updated.bookedUnits > updated.totalUnits) {
            updated.bookedUnits = updated.totalUnits;
            needsSave = true;
        }
        if (!updated.createdAt) {
            updated.createdAt = new Date().toISOString();
            needsSave = true;
        }
        if (!updated.status || (updated.status !== 'Operational' && updated.status !== 'Under Maintenance' && updated.status !== 'Out of Service')) {
            updated.status = 'Operational';
            needsSave = true;
        }
        if (updated.lastMaintenanceDate === undefined) {
            updated.lastMaintenanceDate = null;
            needsSave = true;
        }
        if (!updated.maintenanceRecords) {
            updated.maintenanceRecords = [];
            needsSave = true;
        }
        // Remove type if exists
        if (updated.type) {
            delete updated.type;
            needsSave = true;
        }
        return updated;
    });

    if (needsSave) {
        localStorage.setItem("machines", JSON.stringify(machines));
    }
    return machines;
}

function saveMachines(machines) {
    localStorage.setItem("machines", JSON.stringify(machines));
}

function addMachine(machine) {
    const machines = getMachines();
    const newId = machines.length > 0 ? Math.max(...machines.map(m => m.id)) + 1 : 1;
    machine.id = newId;
    machine.totalUnits = machine.totalUnits || 1;
    machine.bookedUnits = 0;
    machine.createdAt = new Date().toISOString();
    machine.status = machine.status || "Operational";
    machine.lastMaintenanceDate = null;
    machine.maintenanceRecords = [];
    machines.push(machine);
    saveMachines(machines);
    return machine;
}

function updateMachine(id, updatedData) {
    const machines = getMachines();
    const index = machines.findIndex(m => m.id === id);
    if (index !== -1) {
        machines[index] = { ...machines[index], ...updatedData, id: id, createdAt: machines[index].createdAt, bookedUnits: machines[index].bookedUnits, maintenanceRecords: machines[index].maintenanceRecords };
        saveMachines(machines);
        return true;
    }
    return false;
}

function addMaintenanceRecord(machineId, description, cost) {
    const machines = getMachines();
    const index = machines.findIndex(m => m.id === machineId);
    if (index !== -1) {
        if (!machines[index].maintenanceRecords) machines[index].maintenanceRecords = [];
        machines[index].maintenanceRecords.push({
            date: new Date().toISOString(),
            description: description,
            cost: parseFloat(cost) || 0
        });
        machines[index].lastMaintenanceDate = new Date().toISOString().split('T')[0];
        if (machines[index].status !== 'Under Maintenance') {
            machines[index].status = 'Under Maintenance';
        }
        saveMachines(machines);
        return true;
    }
    return false;
}

function updateMachineStatusWithCost(machineId, status, description = "", cost = 0) {
    const machines = getMachines();
    const index = machines.findIndex(m => m.id === machineId);
    if (index !== -1) {
        machines[index].status = status;
        if (status === "Under Maintenance" && (description || cost > 0)) {
            if (!machines[index].maintenanceRecords) machines[index].maintenanceRecords = [];
            machines[index].maintenanceRecords.push({
                date: new Date().toISOString(),
                description: description || "Maintenance performed",
                cost: parseFloat(cost) || 0
            });
            machines[index].lastMaintenanceDate = new Date().toISOString().split('T')[0];
        }
        saveMachines(machines);
        return true;
    }
    return false;
}

function getMaintenanceCost(machineId) {
    const machines = getMachines();
    const machine = machines.find(m => m.id === machineId);
    if (!machine || !machine.maintenanceRecords) return 0;
    return machine.maintenanceRecords.reduce((sum, r) => sum + r.cost, 0);
}

function getTotalMaintenanceCost() {
    const machines = getMachines();
    let total = 0;
    machines.forEach(m => {
        if (m.maintenanceRecords) {
            total += m.maintenanceRecords.reduce((sum, r) => sum + r.cost, 0);
        }
    });
    return total;
}

function getMachinesUnderMaintenance() {
    const machines = getMachines();
    return machines.filter(m => m.status === "Under Maintenance");
}

function getMachinesOverdueMaintenance(daysThreshold = 90) {
    const machines = getMachines();
    const today = new Date();
    return machines.filter(m => {
        if (!m.lastMaintenanceDate) return true;
        const lastDate = new Date(m.lastMaintenanceDate);
        const daysDiff = Math.ceil((today - lastDate) / (1000 * 60 * 60 * 24));
        return daysDiff > daysThreshold && m.status !== "Under Maintenance";
    });
}

function getAvailableUnits(machineId) {
    const machine = getMachines().find(m => m.id === machineId);
    if (!machine) return 0;
    if (machine.status !== "Operational") return 0;
    return machine.totalUnits - machine.bookedUnits;
}

function updateMachineBooking(machineId, delta) {
    const machines = getMachines();
    const index = machines.findIndex(m => m.id === machineId);
    if (index !== -1) {
        machines[index].bookedUnits += delta;
        if (machines[index].bookedUnits < 0) machines[index].bookedUnits = 0;
        if (machines[index].bookedUnits > machines[index].totalUnits) machines[index].bookedUnits = machines[index].totalUnits;
        saveMachines(machines);
        return true;
    }
    return false;
}

function deleteMachine(id) {
    let machines = getMachines();
    machines = machines.filter(m => m.id !== id);
    saveMachines(machines);
}

// Add some legacy globals for compatibility with the imported page scripts
window.getInventory = getInventory;
window.saveInventory = saveInventory;
window.addInventoryItem = addInventoryItem;
window.updateInventoryItem = updateInventoryItem;
window.deleteInventoryItem = deleteInventoryItem;
window.checkLowStock = checkLowStock;
window.checkExpiringSoon = checkExpiringSoon;
window.isProductExpired = isProductExpired;
window.getMachines = getMachines;
window.saveMachines = saveMachines;
window.addMachine = addMachine;
window.updateMachine = updateMachine;
window.addMaintenanceRecord = addMaintenanceRecord;
window.updateMachineStatusWithCost = updateMachineStatusWithCost;
window.getMaintenanceCost = getMaintenanceCost;
window.getTotalMaintenanceCost = getTotalMaintenanceCost;
window.getMachinesUnderMaintenance = getMachinesUnderMaintenance;
window.getMachinesOverdueMaintenance = getMachinesOverdueMaintenance;
window.getAvailableUnits = getAvailableUnits;
window.updateMachineBooking = updateMachineBooking;
window.deleteMachine = deleteMachine;
