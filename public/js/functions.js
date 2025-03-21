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

function loadFeed(url, idx) {
  fetch(url)
  .then(response => {
    if (!response.ok) {
      throw new Error('Request failed');
    }
    return response.json();
  })
  .then(data => {
    const { tabHtml, columnHtml } = data;
    document.querySelector('#wn-tab-' + idx).innerHTML = tabHtml;
    document.querySelector('#wn-col-' + idx).innerHTML = columnHtml;
    updatePageTitle();
  })
  .catch(error => {
    console.error('An error occurred:', error);
    // Handle the error appropriately
  });
}

function updatePageTitle() {
    let title = 'WhoNews Beta ';
    const labels = document.querySelectorAll('.wn-tab-label');
    [...labels].map((elt) => {
       title += ' | ' + elt.innerHTML;
    });
    document.querySelector('title').innerHTML = title;
}
