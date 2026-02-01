const getData = async () => {
  const response = await fetch("../mockData/api.json");
  const data = await response.json();
  return data;
};

getData().then(data => {
  console.log(data);
});

function toggleMenu() {
    const hamburger = document.querySelector('.hamburger');
    const menuItems = document.querySelector('.menu-items');
    
    if (hamburger && menuItems) {
        hamburger.classList.toggle('active');
        menuItems.classList.toggle('active');
    }
}

document.addEventListener('click', function(e) {
    const hamburger = document.querySelector('.hamburger');
    const menuItems = document.querySelector('.menu-items');
    
    if (hamburger && menuItems && !hamburger.contains(e.target) && !menuItems.contains(e.target)) {
        hamburger.classList.remove('active');
        menuItems.classList.remove('active');
    }
});

window.addEventListener('resize', function() {
    if (window.innerWidth > 768) {
        const hamburger = document.querySelector('.hamburger');
        const menuItems = document.querySelector('.menu-items');
        if (hamburger && menuItems) {
            hamburger.classList.remove('active');
            menuItems.classList.remove('active');
        }
    }
});