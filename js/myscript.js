$(function () {
  $("#mySidenav").load("sidemenu.php");
});

$(document).ready(function () {
  $("#show-omr").click(function () {
    $("#omr-float").toggle();
  });
});

function openNav() {
  document.getElementById("mySidenav").style.width = "250px";
  //   $("#mySidenav").show(500);

}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";

  //   $("#mySidenav").hide(500);

}

function goBack() {
  window.history.back();
}

$(document).ready(function () {
  $('[data-toggle="tooltip"]').tooltip();
});

/********************* local storage *******************/


function setActiveBox($radioButton) {
  var value = $radioButton.attr("value");
  saveData($radioButton.attr("name"), value);
}

function saveData(key, value) {
  console.log("save:" + key + "," + value)
  localStorage.setItem(key, value);
}

function getData(key) {
  console.log("get:" + key + "," + localStorage.getItem(key))
  return localStorage.getItem(key);
}

function clearAllData() {
  console.log("Clear localStorage");
  localStorage.clear();
}


function pad(n) {
  if (n < 10) {
    return "0" + n;
  } else {
    return n;

  }
}



/********************* local storage ENDS *******************/


/********************* HIDE HEADER when scrolling *******************/

/* When the user scrolls down, hide the navbar. When the user scrolls up, show the navbar */
var prevScrollpos = window.pageYOffset;
window.onscroll = function () {
  var currentScrollPos = window.pageYOffset;
  if (Math.abs(prevScrollpos - currentScrollPos) > 10) {
    if (prevScrollpos >= currentScrollPos) {
      document.getElementById("header-bar").style.top = "0";
      document.getElementById("footer-bar").style.backgroundColor = "#f65888";
    } else {
      document.getElementById("header-bar").style.top = "-81px";
      document.getElementById("footer-bar").style.backgroundColor = "#00000022";

    }
    prevScrollpos = currentScrollPos;
  }
}
/********************* HIDE HEADER when scrolling ENDS *******************/


/********************* SCROLL to top *******************/

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
}
/********************* SCROLL to top ENDS *******************/


function showtoast() {
  // Get the snackbar DIV
  var x = document.getElementById("snackbar");

  // Add the "show" class to DIV
  x.className = "show";

  // After 3 seconds, remove the show class from DIV
  setTimeout(function () { x.className = x.className.replace("show", ""); }, 3000);
}