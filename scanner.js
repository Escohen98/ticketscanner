(function() {
  "use strict";

  window.addEventListener("load", initialize);

  function initialize() {

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
