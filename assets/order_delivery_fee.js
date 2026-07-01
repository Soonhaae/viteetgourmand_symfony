function formatDeliveryPrice(value) {
  return value.toFixed(2);
}

function normalizeCity(value) {
  return value
    .trim()
    .toLowerCase()
    .normalize('NFD')
    .replace(/[\u0300-\u036f]/g, '');
}

function initOrderDeliveryFee() {
  document.querySelectorAll('.js-delivery-form').forEach((form) => {
    if (form.dataset.deliveryInitialized === 'true') {
      return;
    }

    const cityInput = form.querySelector('[name$="[deliveryCity]"]');
    const distanceInput = form.querySelector('[name$="[deliveryDistanceKm]"]');
    const deliveryPriceTarget = form.querySelector('.js-delivery-price');
    const orderFinalTotalTarget = form.querySelector('.js-order-final-total');

    if (!cityInput || !distanceInput || !deliveryPriceTarget || !orderFinalTotalTarget) {
      return;
    }

    form.dataset.deliveryInitialized = 'true';

    const baseDeliveryPrice = Number.parseFloat(form.dataset.deliveryBasePrice);
    const kmRate = Number.parseFloat(form.dataset.deliveryKmRate);

    const getMenuTotal = () => {
      const menuTotalTarget = form.querySelector('.js-order-total') || form.querySelector('.js-delivery-menu-total');

      return Number.parseFloat((menuTotalTarget?.textContent || form.dataset.menuTotal).replace(',', '.'));
    };

    const updateDeliveryFee = () => {
      const city = normalizeCity(cityInput.value);
      const distance = Number.parseInt(distanceInput.value, 10) || 0;
      const deliveryPrice = city === 'bordeaux' ? baseDeliveryPrice : baseDeliveryPrice + distance * kmRate;
      const orderTotal = getMenuTotal() + deliveryPrice;

      deliveryPriceTarget.textContent = formatDeliveryPrice(deliveryPrice);
      orderFinalTotalTarget.textContent = formatDeliveryPrice(orderTotal);
    };

    cityInput.addEventListener('input', updateDeliveryFee);
    distanceInput.addEventListener('input', updateDeliveryFee);
    form.querySelector('[name$="[nbPers]"]')?.addEventListener('input', updateDeliveryFee);
    form.querySelector('[name$="[nbPers]"]')?.addEventListener('change', updateDeliveryFee);

    updateDeliveryFee();
  });
}

document.addEventListener('DOMContentLoaded', initOrderDeliveryFee);
document.addEventListener('turbo:load', initOrderDeliveryFee);
