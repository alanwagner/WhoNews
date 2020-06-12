/**
 * WhoNews : functions.js
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

function toggleSettings() {
  var elementPanel = document.getElementById("wn-settings-panel");
  elementPanel.classList.toggle("wn-settings-hidden");
  var buttonPanel = document.getElementById("wn-settings-btn");
  buttonPanel.classList.toggle("wn-settings-btn-active");
}

function toggleCustomInput(id, selected) {
  var element = document.getElementById(id);
  if (selected === "custom") {
	  element.classList.remove("wn-custom-hidden");
  } else {
	  element.classList.add("wn-custom-hidden");
  }
}
