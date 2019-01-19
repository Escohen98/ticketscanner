(function() {
  "use strict";

  window.addEventListener("load", initialize);
  const CODE_LENGTH = 4;

  //Adds event listeners to specified buttons.
  function initialize() {
    let nums = qsa(".num");
    for (let i = 0; i<nums.length; i++) {
      addColorChange(nums[i]);
      nums[i].addEventListener("click", addChar);
    }
    addColorChange($("x"));
    $("x").addEventListener("click", delChar);

    addColorChange($("enter"));
    $("enter").addEventListener("click", fetchData);
  }

  function fetchData() {
    updateBtnColor();
    let code = $("code").innerText;
    let test = Math.round(Math.random());
    if(test==0) {
      displayResult(true);
    } else {
      displayResult(false);
    }
  }

    //Displaus success message after code a validated as active and exists in
    //database (if result == true) otherwise displays error message
    function displayResult(result) {
      let element = "error";
      let file = "bad-beep.wav";
      if(result) {
        element = "success"
        file = "success.wav"
      }
      let timer = null;
      timer = setTimeout(function() {
        let audio = new Audio(`./audio/${file}`);
        audio.play();
        $(element).classList.remove("hidden");
      }, 500);
        $(element).classList.add("hidden");
        console.log(element + ": " + $(element).classList);
        $("code").innerText = "";
    }

    //Adds given number (0-9) to the code string while the length <= CODE_LENGTH
    function addChar() {
      updateBtnColor();
      let code = $("code").innerText;
      if(code.length < CODE_LENGTH)
        $("code").innerText += this.innerText;
    }

    //Removes the last character in the code string from the code.
    function delChar() {
      updateBtnColor();
      let code = $("code").innerText;
      if(code.length != 0) {
        $("code").innerText = code.substring(0, code.length-2);
      }
    }

  //Adds mousedown and mouseup events to given button to change the color
  //when pressed.
  function addColorChange(btn) {
    btn.addEventListener("mousedown", changeColor);
    btn.addEventListener("mouseup", changeColor);
  }

  //Changes the color of the element that called on the function to
  //gray if it's blue, otherwise turns it gray.
  function changeColor() {
    if(this.style.backgroundColor == "blue" ) {
      this.style.backgroundColor = "#DBDBDB";
    } else {
      this.style.backgroundColor = "blue"
    }

  }

  //Makes sure all buttons are set to default.
  //Redundant but necessary.
  function updateBtnColor() {
    let btns = qsa("button");
    for(let i = 0; i<btns.lenght; i++) {
      if(btns[i].style.backgroundColor == "blue" ) {
        btns[i].style.backgroundColor = "#DBDBDB";
      }
    }
  }

  //Retrieved functions from CSE 154 Template
  //Simplifies importing elements
  function $(id) {
    return document.getElementById(id);
  }

  //Simplifies importing multiple elements
  function qsa(query) {
    return document.querySelectorAll(query);
  }

  //Simplifies importing class or semantic elements
  function qs(query) {
    return document.querySelector(query);
  }

})();
