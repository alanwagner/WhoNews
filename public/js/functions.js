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

function generateTabHtml(data) {
    let html = '';
    if (data.image) {
        html += '<div class="wn-tab-image" title="' + data.label + '">';
        html += '<span class="wn-img-bg" style="background-image: url(img/' + data.image + ');"></span>';
    } else {
        html += '<div class="wn-tab-text">';
    }
    html += '<span class="wn-tab-label">' + data.label + '</span></div>';
    html += '<ul><li><a class="wn-link-rss" href="' + data.url + '" title="RSS Link"';
    if (data.target) {
        html += ' target="' + data.target + '"';
    }
    html += '><span>RSS Link</span></a></li></ul>';

    return html;
}

function generateColumnHtml(data) {
    let html = '';

    for (const item of data) {
        html += '<li class="wn-item">';
        if (item.imageUrl) {
            html += '<div class="wn-item-image"><img src="' + item.imageUrl + '" /></div>';
        }
        html += '<div class="wn-item-text"><div class="wn-item-title">';
        html += '<a href=\"' + item.itemUrl + '" class="wn-item-link"';
        if (item.target) {
            html += ' target="' + item.target + '"';
        }
        html += '><span>' + item.title + '</span></a></div>';
        if (item.description) {
            html += '<div class="wn-item-description">' + item.description + '</div>';
        }
        html += '<div class="wn-item-date">' + item.feedTitle;
        if (item.date) {
            html += '&nbsp;•&nbsp;&nbsp;' + item.date;
        }
        html += '</div></div></li>';
    }

    return html;
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
    const { tabData, columnData } = data;
    document.querySelector('#wn-tab-' + idx).innerHTML = generateTabHtml(tabData);
    document.querySelector('#wn-col-' + idx).innerHTML = generateColumnHtml(columnData);
    updatePageTitle();
  })
  .catch(error => {
    console.error('An error occurred:', error);
    // Handle the error appropriately
  });
}

function updatePageTitle() {
    let title = 'WhoNews.org';

    if (defaultPageTitle.length) {
        title = defaultPageTitle;
    } else {
        const labels = document.querySelectorAll('.wn-tab-label');
        [...labels].map((elt) => {
           title += ' | ' + elt.innerHTML;
        });
    }

    document.querySelector('title').innerHTML = title;
}
