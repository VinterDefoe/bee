//import _ from 'lodash';
import './scss/style.scss';
import 'bootstrap';

//////// Add Review Form //////
$(function () {
    $('.custom-file-input').on('change',function () {
        $(this).next('.custom-file-label').html($(this).val());
        console.log(1);
    })

});


(function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
