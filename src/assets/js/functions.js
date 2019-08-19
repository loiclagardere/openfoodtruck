'use strict'

// Variables
let form = document.querySelector("form");
let passwordField = document.getElementById("password");
let confirmField = document.getElementById("password-confirm");
let button = document.querySelector("button");
let formGroup = document.querySelector(".form-group");
let messagePassword = "";

addHtmlOnButton();
validPassword()

/**
 * Add class and attribute on forom button
 * 
 */
function addHtmlOnButton() {
    window.addEventListener('load', function () {
        form ? button.setAttribute('disabled', 'disabled') : "";
        button ? button.classList.add('btn-invalid') : "";
    })
}

/**
 * 
 * Check password validity
 * 
 */
function validPassword() {
    passwordField.addEventListener('keyup', function (e) {
        if (this.value.length > 7 && this.value == confirmField.value) {
            button.removeAttribute("disabled", "");
            button.classList.remove("btn-invalid");
            this.classList.remove("invalid");
            confirmField.classList.remove("invalid");
        } else {
            button.setAttribute('disabled', 'disabled');
            button.classList.add('btn-invalid');
            this.classList.add("invalid");
            confirmField.classList.add("invalid");
            this.value.length < 8 ? createMessagePassword(document.getElementById("password"), formGroup) : message.remove();

            console.log(createMessagePassword(createMessagePassword(document.getElementById("password"), formGroup)));
        }
    });

    confirmField.addEventListener('keyup', function (e) {
        if (this.value.length > 7 && this.value == passwordField.value) {
            button.removeAttribute("disabled", "");
            button.classList.remove("btn-invalid");
            this.classList.remove("invalid");
            passwordField.classList.remove("invalid");
        } else {
            button.setAttribute("disabled", "disabled");
            button.classList.add('btn-invalid');
            this.classList.add("invalid");
            passwordField.classList.add("invalid");
        }
    });
}




// function createMessageField(element, parentNode) {
//     document.getElementById(messageField.id) ? document.getElementById(messageField.id).remove() : "";
//     messageField = document.createElement('div');
//     messageField.id = "password-field";
//     messageField.classList.add('error-field');
//     messageField.innerText = "Il manque " + (8 - element.value.length) + " caractére(s).";
//     parentNode.appendChild(messageField);

// }




let messagePasswordStringLength = function (element, parentNode) {
    messagePassword = document.createElement('div');
    messagePassword.id = "password-field";
    messagePassword.classList.add('error-field');
    messagePassword.innerText = "Il manque " + (8 - element.value.length) + " caractére(s).";
    parentNode.appendChild(messagePassword);
    return messagePassword;
}

let createMessagePassword= function (element, parentNode) {
    messagePasswordStringLength(element, parentNode);
    document.getElementById(messagePassword.id) ? document.getElementById(messagePassword.id).remove() : "";
} 

