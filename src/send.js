document.body.setScaledFont = function() {
    var f = 0.35, s = this.offsetWidth, fs = s * f;
    this.style.fontSize = fs + '%';
    return this
}

window.addEventListener('resize', function(event) {
    document.body.setScaledFont();
}, true);
document.body.setScaledFont();

function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}

var choice1 = null;
var choice2 = null;

function submitData() {
    
    document.getElementById("noclicks").classList.add("prevent-clicks");

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "ajaxController.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");
    xhr.timeout = 5000;

    xhr.onreadystatechange = function () {
        if (this.readyState != 4){

            // document.getElementById("nocon").classList.add("message-show");
            // setTimeout(() => {
            //     document.getElementById("nocon").classList.remove("message-show");
            // }, 1000); 
            return;  
        }
    
        if (this.status == 200) {
            var response = JSON.parse(this.responseText);
        } else {
            var response = null;
        }

        document.getElementById("noclicks").classList.remove("prevent-clicks");

        if(response != null && response.text == "success") {
            choice1 = null;
            choice2 = null;
    
            document.querySelector(".button-vote").classList.remove("allowed");
            document.getElementById("succ").classList.add("message-show");
            setTimeout(() => {
                document.querySelectorAll(".button").forEach(hidden => {
                    hidden.classList.remove("button-selected");
                    hidden.classList.remove("button-hidden");
                });
                document.getElementById("succ").classList.remove("message-show");
            }, 400);
        } else if(response.text == "voting-locked") {
            document.getElementById("locked").classList.add("message-show");
            setTimeout(() => {
                document.getElementById("locked").classList.remove("message-show");
            }, 1000);            
        } else {
            document.getElementById("fail").classList.add("message-show");
            setTimeout(() => {
                document.getElementById("fail").classList.remove("message-show");
            }, 1000);            
        }            
    };

    xhr.onerror = function() {
        noConError();
    }
    xhr.ontimeout = function() {
        noConError();
    }

    function noConError() {
        document.getElementById("nocon").classList.add("message-show");
        setTimeout(() => {
            document.getElementById("nocon").classList.remove("message-show");
        }, 1000); 
    }

    var data = JSON.stringify({
        "choice1": choice1,
        "choice2": choice2
    });

    xhr.send(data);
}

document.querySelector(".button-vote").addEventListener("click", function(event) { 
    if(choice1 !== null || choice2 !== null) {
        submitData();
    }   
});

document.addEventListener("keyup", function(event) {
    if (event.code === 'Enter') {
        if(choice1 !== null || choice2 !== null) {
            submitData();
        }   
    }
});

document.querySelectorAll(".button").forEach(e => e.addEventListener("click", function(event) {  
    var selectedClass = this.getAttribute("data-class");
    
    if(selectedClass <= 6 && selectedClass > 0) {
        var selectedCat = 1;
        choice1 = selectedClass;
    } else if(selectedClass > 6 && selectedClass <= 12) {
        var selectedCat = 2;
        choice2 = selectedClass;
    }

    document.querySelector(".button-vote").classList.add("allowed");

    document.querySelectorAll(".cat-" + selectedCat).forEach(hidden => {
        if(hidden.getAttribute("data-class") != selectedClass) {
            hidden.classList.remove("button-selected");
            hidden.classList.add("button-hidden");
        } else {
            hidden.classList.remove("button-hidden");
            hidden.classList.add("button-selected");
        }
    });
    // //Uncomment the code below to auto submit data once both choices have been selected
    // if(choice1 !== null && choice2 !== null) {
    //     //alert("První: " + choice1 + ", druhý: " + choice2);
    //     submitData();
    // }

}));