var dateString = document.getElementById('end').innerHTML; // Oct 23

var dateObject = new Date(dateString);

console.log(dateObject);
var deadline = new Date(""+ dateObject +"").getTime();
var x = setInterval(function() {
    var now = new Date().getTime();
    var t = deadline - now;
    var days = Math.floor(t / (1000 * 60 * 60 * 24));
    var hours = Math.floor((t%(1000 * 60 * 60 * 24))/(1000 * 60 * 60));
    var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
    var seconds = Math.floor((t % (1000 * 60)) / 1000);
    document.getElementById("Timer").innerHTML = days + " Days "
        + hours + " hours and " + minutes + " minutes remaing to vote";
    if (t < 0) {
        clearInterval(x);
        document.getElementById("Timer").innerHTML = "EXPIRED";
    }
}, 1000);