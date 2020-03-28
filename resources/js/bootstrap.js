// Import Popper.js
window.Popper = require("popper.js").default;

// Import jQuery
window.$ = window.jQuery = require("jquery");

// Import Bootstrap
require("bootstrap");

window._token = $('meta[name="csrf-token"]').attr('content');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': window._token
    }
});

$(document).on("hidden.bs.modal", ".modal", function () {
    $(".modal:visible").length && $(document.body).addClass("modal-open");
});
