let deliveryMethodCheckboxes = document.querySelectorAll('.checkboxes-delivery input');
let deliveryDetails1 = document.querySelector('.delivery-1');
let deliveryDetails3 = document.querySelector('.delivery-3');

deliveryMethodCheckboxes[0].addEventListener('click', function() {
   if(this.checked) {
       this.parentElement.classList.remove('hidden');
       deliveryMethodCheckboxes[1].checked = false;
       deliveryMethodCheckboxes[1].parentElement.classList.add('hidden');
       deliveryMethodCheckboxes[2].checked = false;
       deliveryMethodCheckboxes[2].parentElement.classList.add('hidden');
       deliveryDetails1.classList.remove('hidden');
       deliveryDetails3.classList.add('hidden');
   } else {
       deliveryMethodCheckboxes[1].parentElement.classList.remove('hidden');
       deliveryMethodCheckboxes[2].parentElement.classList.remove('hidden');
       deliveryDetails3.classList.add('hidden');
       deliveryDetails1.classList.add('hidden');
   }
});

deliveryMethodCheckboxes[1].addEventListener('click', function() {
    if(this.checked) {
        this.parentElement.classList.remove('hidden');
        deliveryMethodCheckboxes[2].checked = false;
        deliveryMethodCheckboxes[2].parentElement.classList.add('hidden');
        deliveryMethodCheckboxes[0].checked = false;
        deliveryMethodCheckboxes[0].parentElement.classList.add('hidden');
        deliveryDetails1.classList.add('hidden');
        deliveryDetails3.classList.add('hidden');
    } else {
        deliveryMethodCheckboxes[2].parentElement.classList.remove('hidden');
        deliveryMethodCheckboxes[0].parentElement.classList.remove('hidden');
        deliveryDetails3.classList.add('hidden');
        deliveryDetails1.classList.add('hidden');
    }
});

deliveryMethodCheckboxes[2].addEventListener('click', function() {
   if(this.checked) {
       this.parentElement.classList.remove('hidden');
       deliveryMethodCheckboxes[1].checked = false;
       deliveryMethodCheckboxes[1].parentElement.classList.add('hidden');
       deliveryMethodCheckboxes[0].checked = false;
       deliveryMethodCheckboxes[0].parentElement.classList.add('hidden');
       deliveryDetails1.classList.add('hidden');
       deliveryDetails3.classList.remove('hidden');
   } else {
       deliveryMethodCheckboxes[1].parentElement.classList.remove('hidden');
       deliveryMethodCheckboxes[0].parentElement.classList.remove('hidden');
       deliveryDetails3.classList.add('hidden');
       deliveryDetails1.classList.add('hidden');
   }
});
