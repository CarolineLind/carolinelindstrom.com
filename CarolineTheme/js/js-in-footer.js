
/*--------------------------------------------------*/
/*--------------- Script in footer -----------------*/
/*--------------------------------------------------*/

document.addEventListener("DOMContentLoaded", function() {
    toggleParent(document.querySelector('.js-toggleParent'));
    inputRange();
});

function toggleParent(element) {
    element.addEventListener('click', function() {
        if(element.classList.contains("clicked")) {
            element.classList.remove("clicked");
            element.parentNode.classList.remove("active");
        } else {
            element.classList.add("clicked");
            element.parentNode.classList.add("active");
        }
    }, false);
}; 


function inputRange() {
  
}; 

function updateTextInput(val) {
    document.getElementById('textInput').value=val; 
}

function updateRange(input) {
    console.log(input);
    console.log(input.value);
    var rangewrapp = input.closest('.rangewrapp');
    console.log(rangewrapp);
    rangewrapp.querySelector('input[type=text]').value = input.value;
    var color = input.id;
    console.log(color);
    var circles = document.getElementsByClassName(color);
    console.log(circles);

   
    
    for (var i=0, item; item = circles[i]; i++) {

        item.setAttribute('style', '--pos: ' + input.value + 'deg');
    }
}

