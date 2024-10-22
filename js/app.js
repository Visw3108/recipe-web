const getElement = (selector) => {
  const element = document.querySelector(selector)

  if (element) return element
  throw Error(
    `Please double check your class names, there is no ${selector} class`
  )
}

const links = getElement('.nav-links')
const navBtnDOM = getElement('.nav-btn')

navBtnDOM.addEventListener('click', () => {
  links.classList.toggle('show-links')
})

const date = getElement('#date')
const currentYear = new Date().getFullYear()
date.textContent = currentYear


// Show the popup when the page loads
window.onload = function() {
  document.getElementById('popup').style.display = 'flex';
};

// Close the popup when 'Maybe Later' is clicked
document.getElementById('maybeLaterBtn').onclick = function() {
  document.getElementById('popup').style.display = 'none';
};

// You can also add a similar action for the 'Subscribe Now' button if needed
document.getElementById('subscribeBtn').onclick = function() {
  alert("Subscribed!"); // Add your subscription logic here
  document.getElementById('popup').style.display = 'none';
};


// Show the popup when the page loads
window.onload = function() {
  document.getElementById('popup').style.display = 'flex';
};

// Close the popup when 'Maybe Later' is clicked
document.getElementById('maybeLaterBtn').onclick = function() {
  document.getElementById('popup').style.display = 'none';
};

// Show "Thank You" popup when 'Subscribe Now' is clicked
document.getElementById('subscribeBtn').onclick = function() {
  const popupContent = document.getElementById('popup-content');

  // Update popup content to display "Thank You" message
  popupContent.innerHTML = `
      <h2>Thank You!</h2>
      <p>Thank you for subscribing. We will keep you updated with the latest news and recipes!</p>
      <div class="popup-buttons">
          <button id="closePopupBtn" class="btn btn-gray">Close</button>
      </div>
  `;

  // Add event listener to the Close button
  document.getElementById('closePopupBtn').onclick = function() {
      document.getElementById('popup').style.display = 'none';
  };
};
