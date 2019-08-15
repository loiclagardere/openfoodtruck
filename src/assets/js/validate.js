'use strict'



// Variables
let form = document.querySelector("form");
let usernameGroup = document.querySelector("div[name='usernameGroup']");
let emailGroup = document.querySelector("div[name='emailGroup']");
let passwordGroup = document.querySelector("div[name='passwordGroup']");
let passwordConfirmGroup = document.querySelector("div[name='passwordConfirmGroup']");
let button = document.querySelector("button");
let usernameField = document.getElementById("username");
let emailField = document.getElementById("email");
let passwordField = document.getElementById("password");
let confirmField = document.getElementById("password-confirm");
let regexUsername = new RegExp('^[a-zA-Z0-9_]+$'); // [a-zA-Z0-9_]
let regexEmail = new RegExp('^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$');

// Messages
let messageUsernameLength = "Certains caractéres ne sont pas autorisés."
let messageEmailFormat = "Le format de l'email est incorrecte."

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

let messagePasswordMatch = "Les mots de passe sont différents."

// Style element
function styleButtonOff() {
    button.setAttribute('disabled', 'disabled');
    button.classList.add('btn-invalid');
}

function styleButtonOn() {
    button.removeAttribute("disabled", "");
    button.classList.remove("btn-invalid");
}


/***
 * 
 * Strucutre of message
 * 
 */
function elementMessage(parentNode, text, classField) {
    let messageHtml = document.createElement('div');
    messageHtml.id = classField;
    messageHtml.classList.add('error-field');
    messageHtml.textContent = text;
    parentNode.appendChild(messageHtml);
}


/**
 * On load
 * Add class and attribute on forom button
 * 
 */
function eventOnLoad() {
    window.addEventListener('load', function () {
        form ? button.setAttribute('disabled', 'disabled') : "";
        button ? button.classList.add('btn-invalid') : "";
    })
}


/***
 * 
 * Display message for user about the username length
 */
function checkUsernameLength() {
    usernameField.addEventListener('keyup', function (e) {
        document.getElementById("username-length") ? document.getElementById("username-length").remove() : "";
        if (regexUsername.test(this.value)) {
            styleButtonOn();
            usernameField.classList.remove("invalid");
        } else {
            styleButtonOff();
            usernameField.classList.add("invalid");
            elementMessage(usernameGroup, messageUsernameLength, "username-length");
        }
    });
}

/***
 * 
 * Display message for user about the email format
 */
function checkEmailFormat() {
    emailField.addEventListener('keyup', function (e) {
        document.getElementById("email-length") ? document.getElementById("email-length").remove() : "";
        if (regexEmail.test(this.value)) {
            styleButtonOn();
            emailField.classList.remove("invalid");
        } else {
            styleButtonOff();
            emailField.classList.add("invalid");
            elementMessage(emailGroup, messageEmailFormat, "email-length");
        }
    });
}


/***
 * 
 * Display message for user about the password length
 */
function checkPasswordLength() {
    passwordField.addEventListener('keyup', function (e) {
        document.getElementById("password-length") ? document.getElementById("password-length").remove() : "";
        if (this.value.length > 7) {
            styleButtonOn();
            passwordField.classList.remove("invalid");
            confirmField ? confirmField.classList.remove("invalid") : "";

        } else {
            styleButtonOff();
            passwordField.classList.add("invalid");
            confirmField ? confirmField.classList.add("invalid") : "";
            elementMessage(passwordGroup, messagePasswordLength(this), "password-length");
        }
    });
}


/***
 * 
 * Display message for user about confirmation password
 */
function checkPasswordMatch() {
    passwordField.addEventListener('keyup', function (e) {
        document.getElementById("password-match") ? document.getElementById("password-match").remove() : "";
        if (this.value.length > 7) {
            if (this.value == confirmField.value) {
                styleButtonOn();
                passwordField.classList.remove("invalid");
                confirmField.classList.remove("invalid");
            } else {
                styleButtonOff();
                passwordField.classList.add("invalid");
                confirmField.classList.add("invalid");
                elementMessage(passwordGroup, messagePasswordMatch, "password-match");
            }
        }
    });

    confirmField.addEventListener('keyup', function (e) {
        document.getElementById("password-match") ? document.getElementById("password-match").remove() : "";

        if (this.value.length > 7) {
            if (this.value == passwordField.value) {
                styleButtonOn();
                passwordField.classList.remove("invalid");
                confirmField.classList.remove("invalid");
                document.getElementById("password-match") ? document.getElementById("password-match").remove() : "";
            } else {
                styleButtonOff();
                passwordField.classList.add("invalid");
                confirmField.classList.add("invalid");
                elementMessage(passwordGroup, messagePasswordMatch, "password-match");
            }
        }
    });
}


eventOnLoad();
usernameGroup ? checkUsernameLength() : "";
passwordField ? checkPasswordLength() : "";
confirmField ? checkPasswordMatch() : "";