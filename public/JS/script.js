
// SIDEBAR
function showSidebar() {
    const sidebar = document.querySelector('.sidebar')
    sidebar.style.display = 'flex'
}

function hideSidebar() {
    const sidebar = document.querySelector('.sidebar')
    sidebar.style.display = 'none'
}

// MENU-TAB
document.addEventListener('DOMContentLoaded', function () {
    const leftMenuItems = document.querySelectorAll('.left-menu li');
    const menuTab = document.querySelector('.menu-tab');

    let menuTabVisible = false;

    // Function to show menu-tab
    function showMenuTab() {
        menuTab.style.opacity = '1';
        menuTab.style.pointerEvents = 'auto';
    }

    // Function to hide menu-tab
    function hideMenuTab() {
        menuTab.style.opacity = '0';
        menuTab.style.pointerEvents = 'none';
    }

    // Function to toggle menu-tab visibility
    function toggleMenuTab() {
        if (menuTabVisible) {
            hideMenuTab();
        } else {
            showMenuTab();
        }
        menuTabVisible = !menuTabVisible;
    }

    // Event listeners for li elements inside left-menu
    leftMenuItems.forEach(function (item) {
        item.addEventListener('mouseenter', showMenuTab);
        item.addEventListener('mouseleave', function () {
            if (!menuTabVisible) {
                hideMenuTab();
            }
        });

        item.addEventListener('click', function () {
            toggleMenuTab();
        });
    });

    // Event listener for menu-tab
    menuTab.addEventListener('mouseenter', function () {
        if (!menuTabVisible) {
            showMenuTab();
        }
    });

    menuTab.addEventListener('mouseleave', function () {
        if (!menuTabVisible) {
            hideMenuTab();
        }
    });
});

// CART
function showCart() {
    closeProfile();
    const body = document.querySelector('body')
    body.classList.add('active');
}

function closeCart() {
    const body = document.querySelector('body')
    body.classList.remove('active');
}


// //UPDATE-CART-ORIGINAL
// function updateQuantity(productId, change, form) {
//     // Find the quantity element
//     var qtyElement = document.querySelector('.item[data-product-id="' + productId + '"] .qty');
//     var cartCountElement = document.querySelector('.cart-count');



//     // Update the quantity
//     var newQty = parseInt(qtyElement.innerText) + change;
//     if (newQty < 0) {
//         // If quantity becomes negative, set it to 0 and remove the item
//         newQty = 0;
//         document.querySelector('.item[data-product-id="' + productId + '"]').remove();

//         // Decrease cart-count
//         var newCartCount = parseInt(cartCountElement.innerText) - 1;
//         cartCountElement.innerText = newCartCount;

//         // Remove the cart-count span if it becomes zero
//         if (newCartCount === 0) {
//             cartCountElement.parentNode.removeChild(cartCountElement);
//         }
//     }

//     console.log("this is productId in javscript line 150", productId);
//     console.log("this is new qty parse int line 151", newQty);

//     // Update the quantity element
//     qtyElement.innerText = newQty;

//     // Set the value of the hidden input
//     const hiddenQty = document.querySelector(".hiddenqty[name='productqty']");
//     const selectedProductID = document.querySelector(".selectedProductID[name='selectedProductID']");
//     hiddenQty.value = parseInt(newQty);
//     console.log("this is new qty parse int", newQty);
//     selectedProductID.value = parseInt(productId);
//     console.log("this is qtyElemetn", newQty);
//     console.log("this is hiddeninput", hiddenQty);
//     // Submit the form automatically
//     form.submit();
// }

// // UPDATE-CART-WROKING
// function updateQuantity(productId, change, form) {
//     // Find the quantity element
//     var qtyElement = document.querySelector('.item[data-product-id="' + productId + '"] .qty');

//     // Update the quantity
//     var newQty = parseInt(qtyElement.innerText) + change;
    
//     if (newQty < 0) {
//         // If quantity becomes negative, set it to 0 and remove the item
//         newQty = 0;
//         document.querySelector('.item[data-product-id="' + productId + '"]').remove();
//     }

//     // Update the quantity element
//     qtyElement.innerText = newQty;

//     // Set the value of the hidden input
//     const hiddenQty = document.querySelector(".hiddenqty[name='productqty']");
//     const selectedProductID = document.querySelector(".selectedProductID[name='selectedProductID']");
//     hiddenQty.value = newQty;
//     selectedProductID.value = productId;

//     // Use AJAX to send asynchronous request to update the database
//     fetch(form.action, {
//         method: 'POST',
//         headers: {
//             'Content-Type': 'application/x-www-form-urlencoded',
//             'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value // Include CSRF token
//         },
//         body: new URLSearchParams(new FormData(form))
//     })
//     .then(response => response.json())
//     .then(data => {
//         // Handle response, if needed
//         console.log(data);
//     })
//     .catch(error => {
//         console.error('Error:', error);
//     });
// }

//UPDATE-CART
function updateQuantity(productId, change, form) {
    // Find the quantity element
    var qtyElement = document.querySelector('.item[data-product-id="' + productId + '"] .qty');

    // Update the quantity
    var newQty = parseInt(qtyElement.innerText) + change;

    if (newQty <= 0) {
        // Remove the HTML element from the DOM
        document.querySelector('.item[data-product-id="' + productId + '"]').remove();
    }

    // Update the quantity element
    qtyElement.innerText = newQty;

    // Set the value of the hidden input
    const hiddenQty = document.querySelector(".hiddenqty[name='productqty']");
    const selectedProductID = document.querySelector(".selectedProductID[name='selectedProductID']");
    hiddenQty.value = newQty;
    selectedProductID.value = productId;

    // Use AJAX to send asynchronous request to update the database
    fetch(form.action, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-TOKEN': form.querySelector('input[name="_token"]').value // Include CSRF token
        },
        body: new URLSearchParams(new FormData(form))
    })
    .then(response => response.json())
    .then(data => {
        // Handle response, if needed
        console.log(data);
    })
    .catch(error => {
        console.error('Error:', error);
    });
}


// Prevent the form from submitting on button click
document.querySelectorAll('.update-qty').forEach(button => {
    button.addEventListener('click', function(event) {
        event.preventDefault();
        updateQuantity(this.dataset.productId, parseInt(this.dataset.change), this.closest('form'));
    });
});

// PROFILE
function showProfile() {

    closeCart();
    const body = document.querySelector('body')
    // body.classList.add('pos-right');
    body.classList.add('active2');

}

function closeProfile() {
    const body = document.querySelector('body')
    // body.classList.add('pos-right');
    body.classList.remove('active2');
}

// OPEN ABOUTUS
function openAboutUs() {
    var aboutUsUrl = "http://127.0.0.1:8000/aboutus";
    // Redirect to the generated URL
    window.location.href = aboutUsUrl;
}

// PRODUCTS PAGE

const allFilter = document.querySelectorAll('.item');
const allFilterBtns = document.querySelectorAll('.btn');

window.addEventListener('DOMContentLoaded', () => {
    allFilterBtns[0].classList.add('active-btn');
});

allFilterBtns.forEach((btn) => {
    btn.addEventListener('click', () => {
        showFilteredContent(btn);
    });
});

function showFilteredContent(btn) {
    allFilter.forEach((item) => {
        if (item.classList.contains(btn.id)) {
            resetActiveBtn();
            btn.classList.add('activebtn');
            item.style.display = "block";
        } else {
            item.style.display = "none";
        }
    });
}

function resetActiveBtn() {
    allFilterBtns.forEach((btn) => {
        btn.classList.remove('activebtn');
    });
}

// function nextQuestion(){
//     var questionPan = document.querySelector('.questions');
//     questionPan.scrollBy(1000,0);
// }


function nextQuestion() {
    // var questionPan = document.querySelectorAll('.quiz-container');
    // questionPan.scrollBy(100,0);
    // let x = questionPan.offsetLeft;

    var scroll = document.querySelector('.questions');
    // scroll.style.left = -100 +'vw';
    scroll.scrollBy = (100, 0);
    // scroll.scrollBy(100,0);

    // scroll.style.add = '.active .questions';

}


// //---------------------------------------------
// // SCROLLING MONITOR
// window.addEventListener("scroll", setScrollVar)
// window.addEventListener("resize", setScrollVar)

// // set percentage of scroll
// function setScrollVar(){
//     const htmlElement = document.documentElement
//     const percentOfScreenHeightScrolled = htmlElement.scrollTop / htmlElement.clientHeight
//     console.log(Math.min (percentOfScreenHeightScrolled * 100, 100))

//     htmlElement.style.setProperty("--scroll", Math.min (percentOfScreenHeightScrolled * 100, 100))
// }

// // to reset scroll when refreshed
// setScrollVar()