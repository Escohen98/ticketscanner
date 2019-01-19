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

  //Adds given number (0-9) to the code string while the length <= CODE_LENGTH
  function addChar() {
    updateBtnColor();
    let code = $("code").innerText;
    console.log("Code length: " + code.length);
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

  function fetchData() {
    updateBtnColor();
    $("code").innerText = "";
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
    for(int i = 0; i<btns.lenght; i++) {
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
