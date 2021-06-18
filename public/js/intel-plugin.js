
var input = document.querySelector("#mobile");
window.intlTelInput(input, {
    utilsScript: "{{CustomUrl::asset('js/js/utils.js')}} ",
});

var input = document.querySelector("#mobile");
intlTelInput(input, {
    utilsScript: "{{CustomUrl::asset('js/js/utils.js')}}"
});

var input = document.querySelector("#mobile"),
    errorMsg = document.querySelector("#error-msg"),
    validMsg = document.querySelector("#valid-msg");

// Error messages based on the code returned from getValidationError
var errorMap = ["Invalid number", "Invalid country code", "Too short", "Too long", "Invalid number"];

// Initialise plugin
var intl = window.intlTelInput(input, {
    utilsScript: "{{CustomUrl::asset('js/js/utils.js')}}"
});

var reset = function () {
    // console.log(errorMsg);
    input.classList.remove("error");
    errorMsg.innerHTML = "";
    errorMsg.classList.add("d-none");
    validMsg.classList.add("d-none");
};

// Validate on blur event
input.addEventListener('blur', function () {
    reset();
    if (input.value.trim()) {
        if (intl.isValidNumber()) {
            validMsg.classList.remove("d-none");
        } else {
            input.classList.add("error");
            var errorCode = intl.getValidationError();
            errorMsg.innerHTML = errorMap[errorCode];
            errorMsg.classList.remove("d-none");
            errorMsg.classList.remove("d-none");
        }
    }
});
var input = document.querySelector("#mobile");
intlTelInput(input, {
    initialCountry: "auto",
    geoIpLookup: function (success, failure) {
        $.get("https://ipinfo.io?token=5d97f285ff1d4d", function () {
        }, "jsonp").always(function (resp) {
            var countryCode = (resp && resp.country) ? resp.country : "";
            success(countryCode);
        });
    },
    utilsScript: "{{CustomUrl::asset('js/js/utils.js')}}"
});

// Reset on keyup/change event
input.addEventListener('change', reset);
input.addEventListener('keyup', reset);
