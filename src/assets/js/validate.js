'use strict'

// Variables
let usernameGroup = document.querySelector("div[name='usernameGroup']");
let emailGroup = document.querySelector("div[name='emailGroup']");
let usernameEmailGroup = document.querySelector("div[name='usernameEmailGroup']");
let siretGroup = document.querySelector("div[name='siretGroup']");
let passwordGroup = document.querySelector("div[name='passwordGroup']");
let passwordConfirmGroup = document.querySelector("div[name='passwordConfirmGroup']");
let companyNameGroup = document.querySelector("div[name='companyNameGroup']");
let button = document.querySelector("button");


let fieldsRequired = document.querySelectorAll('input:required');

let usernameField = document.getElementById("username");
let emailField = document.getElementById("email");
let usernameEmailField = document.getElementById("username-email");
let siretField = document.getElementById("siret");
let passwordField = document.getElementById("password");
let passwordConfirmField = document.getElementById("password-confirm");
let companyNameField = document.getElementById("company-name");

let regexUsername = new RegExp('^[a-zA-Z0-9_]+$');
let regexEmail = new RegExp('^[a-zA-Z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$');
let regexSiret = new RegExp('^[0-9]+$');

// Messages
let messageUsernameFormat = "Certains caractéres ne sont pas autorisés."
let messageEmailFormat = "Le format de l'email est incorrecte."
let messageUsenameEmailFormat = "Ce champ est obligatoire."
let messageSiretFormat = "Le numéro de Siret n'est pas valide."
let messageCompanyNameFormat = "Veuillez renseigner un nom pour votre établissement."


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
    element.setAttribute('status', 'valid-field');
}


const styleFieldInvalid = function (element) {
    element.classList.remove("valid");
    element.classList.add("invalid");
    element.removeAttribute('status', 'valid-field');

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
 * Create html message
 * 
 * @param {string} parentNode 
 * @param {string} text 
 * @param {string} classField 
 */
const elementMessage = function (parentNode, text, idField) {
    let messageHtml = document.createElement('div');
    messageHtml.id = idField;
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
        checkMessageField("username-field");
        if (checkRegex(this, regexUsername)) {
            styleFieldValid(this);
        } else {
            styleFieldInvalid(this);
            elementMessage(usernameGroup, messageUsernameFormat, "username-field");
        }
    });
}


/***
 * 
 * Display message for user about the email format
 */
const checkEmailField = function () {
    emailField.addEventListener('blur', function () {
        checkMessageField("email-field");
        if (checkRegex(this, regexEmail)) {
            styleFieldValid(this);
        } else {
            styleFieldInvalid(this);
            elementMessage(emailGroup, messageEmailFormat, "email-field");
        }
    });
}

/***
 * 
 * Display message for user about the usernameEmail field format
 */
const checkUsernameEmailField = function () {
    usernameEmailField.addEventListener('keyup', function () {
        checkMessageField("username-email-field");
        if (this.value !== "") {
            styleFieldValid(this);
        } else {
            styleFieldInvalid(this);
            elementMessage(usernameEmailGroup, messageUsernameEmailFormat, "username-email-field");
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
            styleFieldValid(this);
        } else {
            styleFieldInvalid(this);
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
                styleFieldValid(this);
                styleFieldValid(passwordConfirmField);
            } else {
                styleFieldInvalid(this);
                passwordConfirmField.classList.add("invalid");
                elementMessage(passwordGroup, messagePasswordMatch, "password-match");
            }
        }
    });

    passwordConfirmField.addEventListener('keyup', function () {
        console.log('confirmPasswrd');
        checkMessageField("password-match");
        removeClassInvalid(this);
        if (this.value.length > 7) {
            if (this.value == passwordField.value) {
                styleFieldValid(this);
                styleFieldValid(passwordField);
            } else {
                styleFieldInvalid(this);
                passwordField.classList.add("invalid");
                elementMessage(passwordConfirmField, messagePasswordMatch, "password-confirm-match");
            }
        }
    });
}


/***
 * 
 * Display message for user about the companyName field format
 */
const checkCompanyNameField = function () {
    companyNameField.addEventListener('keyup', function () {
        checkMessageField("company-name-field");
        if (this.value !== "") {
            styleFieldValid(this);
        } else {
            styleFieldInvalid(this);
            elementMessage(companyNameGroup, messageCompanyNameFormat, "company-name-field");
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
        // if (fieldsRequired.length == document.querySelectorAll('input[status="valid-field"]').length) {
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
companyNameGroup ? checkCompanyNameField() : "";
button ? statusButton() : "";