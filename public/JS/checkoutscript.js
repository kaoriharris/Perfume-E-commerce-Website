function updateProgressBar(currentStep) {
    const progressBars = ['identification-bar', 'delivery-bar', 'payment-bar', 'waitingforpayment-bar', 'confirmation-bar'];

    for (let i = 0; i < progressBars.length; i++) {
        const progressBar = document.getElementById(progressBars[i]);
        progressBar.style.backgroundColor = i <= currentStep ? 'gray' : 'white';
    }
}


// Payment option js 
var selectedButton = 0;
function selectPaymentOption(option) {

    //deselect
    if (selectedButton != 0){
        document.getElementById(selectedButton).classList.remove('.selected-option');
    }

    selectedButton = option;

    //highlight real one
    document.getElementById(selectedButton).classList.add('.selected-option');
}

// Update deliveryID
function updateChosen(ID) {
    var chosen = document.querySelector('.actualchosenone');
    chosen.value = parseInt(ID);
    console.log("afahsbcn",chosen.value);
}