function toggleMenu() {
    var navMenu = document.getElementById('navMenu');
    if (navMenu.style.right === '0px') {
        navMenu.style.right = '-100%';
    } else {
        navMenu.style.right = '0px';
    }
}