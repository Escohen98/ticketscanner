(function() {
  "use strict";

  window.addEventListener("load", initialize);
  const CODE_LENGTH = 4; //max code length.

  function initialize() {
    let nums = qsa(".num");
    for (let i = 0; i<nums.length; i++) {
      //addColorChange(nums[i]);
      nums[i].addEventListener("click", addChar);
    }
    //addColorChange($("x"));
    $("x").addEventListener("click", delChar);

  //  addColorChange($("enter"));
    $("enter").addEventListener("click", fetchData);
  }

  //Pulls count ammount of codes from database and sets them to active.
  //Logs error.
  function fetchData() {
    let count = $("code").innerText;
    let params = new FormData();
    params.append("pull", count);
    fetch("../backend/scanner.php", {method: "POST", mode: "cors", body: params})
    .then(checkStatus)
    .then(JSON.parse)
    .then(displayResult)
    .catch(console.log);
  }

  function displayResult(response) {
    console.log(response);
  }

  //Adds given number (0-9) to the code string while the length <= CODE_LENGTH
  function addChar() {
    //updateBtnColor();
    document.body.style.backgroundColor = "white";
    let code = $("code").innerText;
    if(code.length < CODE_LENGTH)
      $("code").innerText += this.innerText;
  }

  //Removes the last character in the code string from the code.
  function delChar() {
    let code = $("code").innerText;
    console.log(`|${code}|`);
    if(code.length != 0) {
      $("code").innerText = code.substring(0, code.length-1);
      code = $("code").innerText;
    }
    console.log(`|${code}|`);
  }

  /*
  * Taken from bestreads assignment
  * Helper function to return the response's result text if successful,
  * otherwise returns the rejected Promise result with an error status and
  * corresponding text.
  * @param {object} response - response to check for success/error
  * @returns {object} - valid result text if response was successful, otherwise
  *                     rejected Promise result
  */
  function checkStatus(response) {
   const OK = 200;
   const ERROR = 300;
   let responseText = response.text();
   if (response.status >= OK && response.status < ERROR
       || response.status === 0) {
     return responseText;
   } else {
     return responseText.then(Promise.reject.bind(Promise));
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
