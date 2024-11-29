document.addEventListener("DOMContentLoaded", function() {
  console.log('Phone Formatter JS is loaded.');
  let phoneInputs = document.querySelectorAll('.cu-phone-formatted');
  
  // loop through all phone input element
  for (let index = 0; index < phoneInputs.length; index++) {
    const phoneInput = phoneInputs[index];
    phoneInput.addEventListener('input', function (e) {
      var x = e.target.value.replace(/\D/g, '').match(/(\d{0,3})(\d{0,3})(\d{0,4})/);
      e.target.value = !x[2] ? x[1] : '(' + x[1] + ') ' + x[2] + (x[3] ? '-' + x[3] : '');
    });
  }  
});

function getParents(element, parentSelector) {
  const parents = [];
  let currentElement = element.parentElement;

  while (currentElement) {
    if (!parentSelector || currentElement.matches(parentSelector)) {
      parents.push(currentElement);
    }
    currentElement = currentElement.parentElement;
  }

  return parents;
}
