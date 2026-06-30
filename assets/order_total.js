function formatPrice(value) {
  return value.toFixed(2);
}

function initOrderTotal() {
  const form = document.querySelector('.js-order-form');

  if (!form || form.dataset.initialized === 'true') {
    return;
  }

  const nbPersInput = form.querySelector('[name$="[nbPers]"]');
  const subtotalRow = form.querySelector('.js-order-subtotal-row');
  const subtotalTarget = form.querySelector('.js-order-subtotal');
  const discountRow = form.querySelector('.js-order-discount-row');
  const discountTarget = form.querySelector('.js-order-discount');
  const totalTarget = form.querySelector('.js-order-total');

  if (!nbPersInput || !subtotalRow || !subtotalTarget || !discountRow || !discountTarget || !totalTarget) {
    return;
  }

  form.dataset.initialized = 'true';

  const menuPrice = Number.parseFloat(form.dataset.menuPrice);
  const minPersons = Number.parseInt(form.dataset.minPersons, 10);
  const discountThreshold = Number.parseInt(form.dataset.discountThreshold, 10);
  const discountRate = Number.parseFloat(form.dataset.discountRate);
  const unitPrice = menuPrice / minPersons;

  const updateTotal = () => {
    const nbPers = Number.parseInt(nbPersInput.value, 10) || minPersons;
    const subtotal = unitPrice * nbPers;
    const discount = nbPers >= discountThreshold ? subtotal * discountRate : 0;
    const total = subtotal - discount;

    subtotalTarget.textContent = formatPrice(subtotal);
    discountTarget.textContent = formatPrice(discount);
    totalTarget.textContent = formatPrice(total);
    subtotalRow.classList.toggle('d-none', discount === 0);
    discountRow.classList.toggle('d-none', discount === 0);
  };

  nbPersInput.addEventListener('input', updateTotal);
  nbPersInput.addEventListener('change', updateTotal);
  updateTotal();
}

document.addEventListener('DOMContentLoaded', initOrderTotal);
document.addEventListener('turbo:load', initOrderTotal);
