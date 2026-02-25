// document.body.setScaledFont = function() {
//     var f = 0.35, s = this.offsetWidth, fs = s * f;
//     this.style.fontSize = fs + '%';
//     return this
// }
// document.body.setScaledFont();

var displayCounters = 0;
var timerTime = "";
var barWidth = document.getElementById("desc-class-1").offsetWidth;
var inter;

function displayData() {       
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "ajaxChart.php", true);

    xhr.onreadystatechange = function () {
        if (this.readyState != 4) return;
    
        if (this.status == 200) {
            var response = JSON.parse(this.responseText);
        }
        console.log(response);

        //document.getElementById("class-1").style.height = "90%";

        var votes = [
            response.class_1,
            response.class_2,
            response.class_3,
            response.class_4,
            response.class_5,
            response.class_6,
            response.class_7,
            response.class_8,
            response.class_9,
            response.class_10,
            response.class_11,
            response.class_12
        ]
        var highestVote = Math.max.apply(0, votes);

        document.querySelectorAll(".bar").forEach(bar => {
            var classNum = bar.getAttribute("data-class");

            var newHeight = Math.round((votes[classNum-1] / highestVote) * 100, 1);
            var currentHeight = (bar.style.height).replace("%", "");
            if(newHeight != (bar.style.height).replace("%", "")) {
                // I wrote this at 1:27 AM. I tried to refrain from using jQuery, but I wanted an easy-to-implement height change animation.
                //$(bar).animate({height: newHeight + "%"},10,"linear");
                bar.style.height = (votes[classNum-1] / highestVote) * 100 + "%";
            }
        });

        document.querySelectorAll(".desc-bar").forEach(barCount => {
            var classNum = barCount.getAttribute("data-class");
            barCount.querySelector(".count").innerHTML = votes[classNum-1];
        });
        var defFontSize = 5; //vh
        document.querySelectorAll(".count").forEach(text => {
            text.style.fontSize = defFontSize + "vh";
        });

        
        document.querySelectorAll(".count.desc-text").forEach(text => {
            var textWidth = text.scrollWidth;
            //console.log("bar width: "+barWidth+" num width: "+textWidth);
            defFontSize = (text.style.fontSize).replace("vh", "");
            console.log("resized num:" + barWidth + " elem: " + textWidth + " fsize: "+text.style.fontSize);
            var resizeCount = defFontSize;
            while(barWidth < textWidth+10) {
                resizeCount -= 0.2;
                text.style.fontSize = resizeCount + "vh";
                console.log("resized num:" + barWidth + " elem: " + textWidth + " fsize: "+text.style.fontSize);
                textWidth = text.scrollWidth;
            }
        });

        if(displayCounters != response.display_counter) {
            displayCounters = response.display_counter;

            if(displayCounters == 1) {
                document.querySelector(".chart-area").style.height = "79vh";
                document.querySelectorAll(".chart-desc").forEach(elem => {
                    elem.style.height = "15vh";
                });
                document.querySelectorAll(".desc-text.count").forEach(elem => {
                    elem.style.display = "block";
                });
                document.getElementById("timer").style.display = "none";
            } else {
                if(response.timer == "") {
                    document.querySelectorAll(".chart-desc").forEach(elem => {
                        elem.style.height = "8vh";
                    });
                    document.querySelector(".chart-area").style.height = "86vh";
                    document.getElementById("timer").style.display = "none";
                } else {
                    document.getElementById("timer").style.display = "flex";
                }
                document.querySelectorAll(".desc-text.count").forEach(elem => {
                    elem.style.display = "none";
                });
            }
        }
        if(displayCounters == 0) {
            if(timerTime != response.timer) {
                clearInterval(inter);
                timerTime = response.timer;
                document.getElementById("min").innerHTML = "0";
                document.getElementById("sec").innerHTML = "00";
                if(timerTime != "") {
                    var countDownDate = new Date(timerTime).getTime();
                    document.getElementById("timer").classList.remove("low");
                    document.getElementById("timer").classList.remove("low-lower");
                    document.getElementById("counter").style.display = "flex";
                    document.getElementById("end").style.display = "none";

                    inter = setInterval(function() {
                        var now = new Date().getTime();
                        var distance = countDownDate - now;
    
                        if(distance < 0) {
                            clearInterval(inter);
                            distance = 0;
                        }
                        var minutes = Math.floor(distance/60000);
                        var seconds = Math.floor(distance/1000 - minutes*60);
                        if(seconds < 10) {
                            seconds = "0" + seconds;
                        }
    
                        document.getElementById("min").innerHTML = minutes;
                        document.getElementById("sec").innerHTML = seconds;

                        if(seconds % 2 !== 0) {
                            document.getElementById("colon").style.opacity = "0.5";
                        } else {
                            document.getElementById("colon").style.opacity = "1";
                        }

                        if(minutes < 5) {
                            if(minutes == 0 && seconds == "00") {
                                document.getElementById("counter").style.display = "none";
                                document.getElementById("end").style.display = "flex";
                            }
                            
                            if(minutes < 1) {
                                document.getElementById("timer").classList.add("low-lower");
                            } else {
                                document.getElementById("timer").classList.add("low");
                            }
                        }

                    }, 1000);
                    document.getElementById("timer").style.display = "flex";
                    document.querySelector(".chart-area").style.height = "79vh";
                    document.querySelectorAll(".chart-desc").forEach(elem => {
                        elem.style.height = "15vh";
                    });
    
    
                } else {
                    document.getElementById("timer").style.display = "none";
                    document.querySelectorAll(".chart-desc").forEach(elem => {
                        elem.style.height = "8vh";
                    });
                    document.querySelector(".chart-area").style.height = "86vh";
                }                
            }
        }



    };

    xhr.send();
}

function resizeClasses() {

    document.querySelectorAll(".desc-text").forEach(text => {
        defFontSize = 5; //vh
        text.style.fontSize = defFontSize + "vh";
    });

    //var barWidth = document.getElementById("desc-class-1").offsetWidth;
    var textWidth = 0;
    var longestText;

    document.querySelectorAll(".class-name").forEach(text => {
        if(text.offsetWidth > textWidth) {
            textWidth = text.offsetWidth;
            longestText = text; // get largest text element
        }
    });

    // adjust size of largest text to fit container, then keep final size
    var textWidth = longestText.offsetWidth;
    defFontSize = (longestText.style.fontSize).replace("vh", "");
    var resizeCount = defFontSize;
    while(barWidth - 20 < textWidth) {
        resizeCount -= 0.2;
        longestText.style.fontSize = resizeCount + "vh";
        console.log("resized class");
        textWidth = longestText.offsetWidth;
    }

    // set final size to all elements
    document.querySelectorAll(".class-name").forEach(text => {
        text.style.fontSize = resizeCount + "vh";
    });
}

(function () {
    
    // window.addEventListener('resize', function(event) {
    //     document.querySelectorAll(".desc-text").forEach(text => {

    //     });
    // }, true);
    // window.dispatchEvent(new Event('resize'));
    var i = 1;
    document.querySelectorAll(".bar").forEach(bar => {
        if(i <= 6) {
            bar.classList.add("blue");
        } else {
            bar.classList.add("orange");
        }
        i++;
    });   

    window.addEventListener('resize', function(event) {
        barWidth = document.getElementById("desc-class-1").offsetWidth;
        resizeClasses();
    }, true);
    window.dispatchEvent(new Event('resize'));


    displayData();
    window.setInterval(function(){
        displayData();
    }, 2000);
}());

