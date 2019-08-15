'use strict'



// Variables
let form = document.querySelector("form");
let passwordField = document.getElementById("password");
let confirmField = document.getElementById("password-confirm");
let button = document.querySelector("button");
let formGroupPassword = document.querySelector("div[name='passwordGroup']");
let messageField = "";


// Messages
/**
 * 
 *Text message password length
 * 
 */
function messagePasswordLength(inputField) {
    let result = 8 - inputField.value.length;
    let message = "Il manque " + result + " caractére(s).";
    return message;
}

let messagePasswordConfirm = "Les mots de passe sont différents"

// Style element
function styleButtonOff() {
    button.setAttribute('disabled', 'disabled');
    button.classList.add('btn-invalid');
}

function styleButtonOn() {
    button.removeAttribute("disabled", "");
    button.classList.remove("btn-invalid");
}

function stylePasswordFieldOff() {
    passwordField.classList.add("invalid");
    confirmField.classList.add("invalid");
}

function stylePasswordFieldOn() {
    passwordField.classList.remove("invalid");
    confirmField.classList.remove("invalid");
}


/***
 * 
 * Strucutre of message
 * 
 */
function elementMessage(parentNode, text, classField) {
    messageField = document.createElement('div');
    messageField.id = classField;
    messageField.classList.add('error-field');
    messageField.textContent = text;
    parentNode.appendChild(messageField);
}


/**
 * Add class and attribute on forom button
 * 
 */
function StyleButtonOnLoad() {
    window.addEventListener('load', function () {
        form ? button.setAttribute('disabled', 'disabled') : "";
        button ? button.classList.add('btn-invalid') : "";
    })
}



// function validPasswordLength() {

//     passwordField.addEventListener('keyup', function (e) {
//         document.getElementById("password-length") !== null ? document.getElementById("password-length").remove() : "";
//         document.getElementById("password-match") !== null ? document.getElementById("password-match").remove() : "";

//         if (this.value.length > 7) {
//             if (this.value == confirmField.value) {
//                 styleButtonOn()
//                 stylePasswordFieldOn()
//                 document.getElementById("password-match") !== null ? document.getElementById("password-match").remove() : "";
//             } else {
//                 styleButtonOff();
//                 stylePasswordFieldOff();
//                 elementMessage(formGroupPassword, messageConfirmPassword, "password-match");
//             }
//         } else {
//             styleButtonOff();
//             stylePasswordFieldOff();
//             elementMessage(formGroupPassword, messagePasswordLength(this), "password-length");
//         }
//     });

//     confirmField.addEventListener('keyup', function (e) {
//         document.getElementById("password-length") ? document.getElementById("password-length").remove() : "";
//         document.getElementById("password-match") ? document.getElementById("password-match").remove() : "";

//         if (this.value.length > 7) {
//             if (this.value == passwordField.value) {
//                 styleButtonOn();
//                 stylePasswordFieldOn();
//                 document.getElementById("password-match") ? document.getElementById("password-match").remove() : "";
//             } 
//             else {
//                 styleButtonOff();
//                 stylePasswordFieldOff();
//                 elementMessage(formGroupPassword, messagePasswordConfirm, "password-match");
//             }
//         } else {
//             styleButtonOff();
//             stylePasswordFieldOff();
//         }
//     });
// }


function checkPasswordLength() {
    passwordField.addEventListener('keyup', function (e) {
        document.getElementById("password-length") !== null ? document.getElementById("password-length").remove() : "";
        // document.getElementById("password-match") !== null ? document.getElementById("password-match").remove() : "";

        if (this.value.length > 7) {
            styleButtonOn()
            stylePasswordFieldOn()
        } else {
            styleButtonOff();
            stylePasswordFieldOff();
            elementMessage(formGroupPassword, messagePasswordLength(this), "password-length");
        }
    });
}

function checkPasswordMatch() {
    if (confirmField) {
    passwordField.addEventListener('keyup', function (e) {
        document.getElementById("password-match") !== null ? document.getElementById("password-match").remove() : "";
        if (this.value.length > 7) {
            if (this.value == confirmField.value) {
                styleButtonOn();
                stylePasswordFieldOn();
                // document.getElementById("password-match") !== null ? document.getElementById("password-match").remove() : "";
            } else {
                styleButtonOff();
                stylePasswordFieldOff();
                elementMessage(formGroupPassword, messagePasswordConfirm, "password-match");
            }
        } 
        // else {
        //     styleButtonOff();
        //     stylePasswordFieldOff();
        //     elementMessage(formGroupPassword, messagePasswordConfirm, "password-match");
        // }
    });

    confirmField.addEventListener('keyup', function (e) {
        document.getElementById("password-match") ? document.getElementById("password-match").remove() : "";

        if (this.value.length > 7) {
            if (this.value == passwordField.value) {
                styleButtonOn();
                stylePasswordFieldOn();
                document.getElementById("password-match") ? document.getElementById("password-match").remove() : "";
            } else {
                styleButtonOff();
                stylePasswordFieldOff();
                elementMessage(formGroupPassword, messagePasswordConfirm, "password-match");
            }
        } 
        // else {
        //     styleButtonOff();
        //     stylePasswordFieldOff();
        // }
    });

}
}


StyleButtonOnLoad();
checkPasswordLength();
checkPasswordMatch();