'use strict'

// Variables
let form = document.querySelector("form");
let usernameGroup = document.querySelector("div[name='usernameGroup']");
let emailGroup = document.querySelector("div[name='emailGroup']");
let usernameEmailGroup = document.querySelector("div[name='usernameEmailGroup']");
let siretGroup = document.querySelector("div[name='siretGroup']");
let passwordGroup = document.querySelector("div[name='passwordGroup']");
let passwordConfirmGroup = document.querySelector("div[name='passwordConfirmGroup']");
let button = document.querySelector("button");

let fieldsRequired = document.querySelectorAll('input:required');

let usernameField = document.getElementById("username");
let emailField = document.getElementById("email");
let usernameEmailField = document.getElementById("username-email");
let siretField = document.getElementById("siret");
let passwordField = document.getElementById("password");
let passwordConfirmField = document.getElementById("password-confirm");

let regexUsername = new RegExp('^[a-zA-Z0-9_]+$');
let regexEmail = new RegExp('^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$');
let regexSiret = new RegExp('^[0-9]+$');

// Messages
let messageUsernameFormat = "Certains caractéres ne sont pas autorisés."
let messageEmailFormat = "Le format de l'email est incorrecte."
let messageUsenameEmailFormat = "Ce champ est obligatoire."
let messageSiretFormat = "Le numéro de Siret n'est pas valide."

/**
 * 
 *Text message password length
 * 
 */
const messagePasswordLength = function (inputField) {
    let result = 8 - inputField.value.length;
    let message = "Il manque au moins " + result + " caractére(s).";
    return message;
}

let messagePasswordMatch = "Les mots de passe sont différents."

// Style element
const styleButtonOff = function () {
    button.setAttribute('disabled', 'disabled');
    button.classList.add('btn-invalid');
}

const styleButtonOn = function () {
    button.removeAttribute('disabled', 'disabled');
    button.classList.remove("btn-invalid");
}

const styleFieldValid = function (element) {
    element.classList.remove("invalid");
    element.classList.add('valid');
}


const styleFieldInvalid = function (element) {
    element.classList.remove("valid");
    element.classList.add("invalid");
}




/**
 * Remove inavlid class
 * 
 * @param {string} inputField 
 */
const removeClassInvalid = function (inputField) {
    if (inputField.value == "") {
        inputField.classList.remove("invalid");
    }
}


/**
 * 
 * Create html message down input
 * 
 * @param {string} parentNode 
 * @param {string} text 
 * @param {string} classField 
 */
const elementMessage = function (parentNode, text, classField) {
    let messageHtml = document.createElement('div');
    messageHtml.id = classField;
    messageHtml.classList.add('error-field');
    messageHtml.textContent = text;
    parentNode.appendChild(messageHtml);
}


/**
 * Remove error message
 * @param { string } classField 
 */
const checkMessageField = function (classField) {
    document.getElementById(classField) ? document.getElementById(classField).remove() : "";
}


/**
 * 
 * On load
 * Add class and attribute on forom button
 */
const eventOnLoad = function () {
    window.addEventListener('load', function () {
        button ? styleButtonOff() : "";
    })
}


/***
 * 
 * check the value of element format with regex
 * 
 * @return boolean
 */
const checkRegex = function (element, regex) {
    if (regex.test(element.value)) {
        return true
    } else {
        return false
    }
}


/***
 * 
 * Display message for user about the username format
 */
const checkUsernameField = function () {
    usernameField.addEventListener('keyup', function () {
        checkMessageField("username-format");
        if (checkRegex(this, regexUsername)) {
            styleFieldValid(this);
        } else {
            styleFieldInvalid(this);
            elementMessage(usernameGroup, messageUsernameFormat, "username-format");
        }
    });
}


/***
 * 
 * Display message for user about the email format
 */
const checkEmailField = function () {
    emailField.addEventListener('blur', function () {
        checkMessageField("email-format");
        if (checkRegex(this, regexEmail)) {
            styleFieldValid(this);
        } else {
            styleFieldInvalid(this);
            elementMessage(emailGroup, messageEmailFormat, "email-format");
        }
    });
}

/***
 * 
 * Display message for user about the usernameEmail field format
 */
const checkUsernameEmailField = function () {
    usernameEmailField.addEventListener('keyup', function () {
        checkMessageField("username-email-format");
        if (this.value !== "") {
            styleFieldValid(this);
        } else {
            styleFieldInvalid(this);
            elementMessage(usernameEmailGroup, messageUsernameEmailFormat, "username-email-format");
        }
    });
}


/**
 * check the validity of the Siret number
 * Use fect() or XMLHttpRequest
 * @param {string} inputField 
 */
const checkSiret = function () {
    siretField.addEventListener('blur', function () {
        checkMessageField("siret-length");
        if (regexSiret.test(this.value)) {
            const urlApi = 'https://entreprise.data.gouv.fr/api/sirene/v1/siret/';
            // const urlApi = 'http://127.0.01:1234';  // test erreur serveur
            if (window.fetch) { // fetch
                // if (1 == 2) { // xhr
                const getSiretFetch = async function (inputField) {
                    let requestApi = new Request(urlApi + inputField.value);
                    let requestApiInit = {
                        method: 'GET',
                        headers: {
                            'Accept-Encoding': 'gzip, deflate',
                            'Referer': 'https://entreprise.data.gouv.fr/api_doc_sirene'
                        },
                        cache: 'no-cache'
                    }
                    try {
                        let responseRequest = await fetch(requestApi, requestApiInit);
                        let responseApiData = await responseRequest.json();
                        if (responseRequest.ok) {

                            if (responseApiData.etablissement) {
                                console.log(responseRequest.statusText);
                                styleFieldValid(inputField);

                            } else {
                                console.log("ce siret n'exsite pas");
                                console.log(responseRequest.statusText);
                                styleFieldInvalid(inputField);
                                elementMessage(siretGroup, messageSiretFormat + " reponse ok mais pas d'etablissement", "siret-length");
                            }

                        } else {
                            console.log('erreur serveur :', responseRequest.status);
                            elementMessage(siretGroup, messageSiretFormat, "siret-length");
                        }

                    } catch (e) {
                        console.log('catch Fetch exception :', e);
                        console.log('erreur :', responseRequest.status);
                        elementMessage(siretGroup, messageSiretFormat, "siret-length");
                    }
                }
                getSiretFetch(siretField);

            } else {
                const getSiretXhr = function (inputField) {
                    try {
                        let xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function (event) {
                            if (this.readyState === XMLHttpRequest.DONE) {
                                let xhrParse = JSON.parse(this.responseText); // Response is a promise. The promise is parse. A json is return
                                if (this.status === 200 && xhrParse.etablissement) {
                                    console.log("siret valide");
                                    styleFieldValid(inputField);
                                } else {
                                    console.log("siret pas valide");
                                    styleFieldInvalid(inputField);
                                    elementMessage(siretGroup, messageSiretFormat + " reponse ok mais pas d'etablissement", "siret-length");
                                }
                            }
                        };
                        xhr.open('GET', urlApi + inputField.value);
                        xhr.send();
                    } catch (e) {
                        console.log('catch xhr exception :', e)
                    }
                }
                getSiretXhr(siretField);
            }
        } else {
            console.log('ne respecte pas regex');
            styleFieldInvalid(this);
            elementMessage(siretGroup, messageSiretFormat, "siret-length");
        }
    });
}



/***
 * 
 * Display message for user about the password length
 */
const checkPasswordLength = function () {
    passwordField.addEventListener('keyup', function () {
        checkMessageField("password-length");
        if (this.value.length > 7) {
            this.classList.remove("invalid");
            this.classList.add('valid');
        } else {
            this.classList.remove("valid");
            this.classList.add("invalid");
            elementMessage(passwordGroup, messagePasswordLength(this), "password-length");
        }
    });
}


/***
 * 
 * Display message for user about confirmation password
 */
const checkPasswordMatch = function () {
    passwordField.addEventListener('keyup', function () {
        checkMessageField("password-match");
        removeClassInvalid(this);
        if (this.value.length > 7) {
            if (this.value == passwordConfirmField.value) {
                this.classList.remove("invalid");
                passwordConfirmField.classList.remove("invalid");
                this.classList.add('valid');
                passwordConfirmField.classList.add("valid");
            } else {
                this.classList.remove("valid");
                this.classList.add("invalid");
                passwordConfirmField.classList.add("invalid");
                elementMessage(passwordGroup, messagePasswordMatch, "password-match");
            }
            // statusButton();
        }
    });

    passwordConfirmField.addEventListener('keyup', function () {
        checkMessageField("password-match");
        removeClassInvalid(this);
        if (this.value.length > 7) {
            if (this.value == passwordField.value) {
                passwordField.classList.remove("invalid");
                this.classList.remove("invalid");
                passwordField.classList.add("valid");
                this.classList.add('valid');
            } else {
                // passwordField.classList.add("invalid");
                this.classList.remove("valid");
                this.classList.add("invalid");
                elementMessage(passwordGroup, messagePasswordMatch, "password-match");
            }
            // statusButton();
        }
    });
}


/**
 * 
 * status of the button according to the state of the field
 */
const statusButton = function () {
    document.addEventListener('keyup', function () {
        console.log('ecoute doc');
        if (fieldsRequired.length == document.getElementsByClassName('valid').length) {
            console.log('fieldsRequired ok')
            styleButtonOn();
        } else {
            console.log('fieldsRequired ko')
            styleButtonOff();
        }
    })
}


eventOnLoad();
usernameGroup ? checkUsernameField() : "";
emailGroup ? checkEmailField() : "";
usernameEmailGroup ? checkUsernameEmailField() : "";
siretGroup ? checkSiret() : "";
passwordGroup ? checkPasswordLength() : "";
passwordGroup && passwordConfirmGroup ? checkPasswordMatch() : "";
button ? statusButton() : "";