
function setup() {
    var e = document.getElementById("watchme");

    e.addEventListener("animationstart", listener, false);
    e.addEventListener("animationend", listener, false);
    e.addEventListener("animationiteration", listener, false);

    var e = document.getElementById("watchme");
    e.className = "rotateinfinite";
}

function listener(e) {
    //var l = document.createElement("li");
    switch(e.type) {
      case "animationstart":
        alert ("start");
        break;
      case "animationend":
        alert ("stop");
          break;
    }
    document.getElementById("output").appendChild(l);
}


