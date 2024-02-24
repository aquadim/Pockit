var sidebar = document.getElementById('agstControls');
var content = document.getElementById('agstMain');
var button = document.getElementById('btnToggleSidebar');

function toggleSidebar() {
    if (globalSidebarOpened) {
        closeSidebar();
    } else {
        openSidebar();
    }
}

function openSidebar() {
    globalSidebarOpened = true;
    sidebar.classList.add("sidebarOpen");
    content.classList.add("contentShifted");
    button.textContent = "❌ Закрыть панель инструментов";
}

function closeSidebar() {
    globalSidebarOpened = false;
    sidebar.classList.remove("sidebarOpen");
    content.classList.remove("contentShifted");
    button.textContent = "📖 Открыть панель инструментов";
}

function sidebarStart(opened) {
    if (opened) {
        openSidebar();
    } else {
        closeSidebar();
    }
}