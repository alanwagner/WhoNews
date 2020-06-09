/**
 * WhoNews : functions.js
 *
 * @author     Alan G. Wagner <mail@alanwagner.org>
 * @copyright  2020 Alan G. Wagner
 * @license    GNU GPL 3.0
 */

function toggleSettings() {
  var element = document.getElementById("wn-settings-panel");
  element.classList.toggle("wn-settings-hidden");
}

function toggleCustomInput(id, selected) {
  var element = document.getElementById(id);
  if (selected === "custom") {
	  element.classList.remove("wn-custom-hidden");
  } else {
	  element.classList.add("wn-custom-hidden");
  }
}
