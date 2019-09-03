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
let regexSiret = new RegExp('^[0-9]{14,14}$');

// Messages
let messageUsernameLength = "Certains caractéres ne sont pas autorisés."
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
    button.removeAttribute("disabled", "");
    button.classList.remove("btn-invalid");
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
 * Create html error message down input
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
const messageField = function (classField) {
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
        // for (let i = 0; i < fieldsRequired.length; i++ ) {
        //     fieldsRequired[i].classList.add('valid');
        // }
    })
}



/***
 * 
 * Display message for user about the username length
 */
const checkUsernameLength = function () {
    usernameField.addEventListener('blur', function () {
        messageField("username-length");
        if (regexUsername.test(this.value)) {
            this.classList.remove("invalid");
            this.classList.add('valid');
        } else {
            this.classList.remove("valid");
            this.classList.add("invalid");
            elementMessage(usernameGroup, messageUsernameLength, "username-length");
        }
        statusButton();
    });
}


/***
 * 
 * Display message for user about the email format
 */
const checkEmailFormat = function () {
    emailField.addEventListener('blur', function () {
        messageField("email-format");
        if (regexEmail.test(this.value)) {
            this.classList.remove("invalid");
            this.classList.add('valid');
        } else {
            this.classList.remove("valid");
            this.classList.add("invalid");
            elementMessage(emailGroup, messageEmailFormat, "email-format");
        }
        statusButton();
    });
}

/***
 * 
 * Display message for user about the usernameEmail field format
 */
const checkUsernameEmailFormat = function () {
    usernameEmailField.addEventListener('blur', function () {
        messageField("username-email-format");
        if (this.value !== "") {
            this.classList.remove("invalid");
            this.classList.add('valid');
        } else {
            this.classList.remove("valid");
            this.classList.add("invalid");
            elementMessage(usernameEmailGroup, messageUsernameEmailFormat, "username-email-format");
        }
        statusButton();
    });
}


/***
 * 
 * Display message for user about the siret format
 */
const checkSiretFormat = function () {
    siretField.addEventListener('keyup', function () {
        messageField("siret-length");
        if (regexSiret.test(this.value)) {
            this.classList.remove("invalid");
            this.classList.add('valid');
        } else {
            this.classList.remove("valid");
            this.classList.add("invalid");
            elementMessage(siretGroup, messageSiretFormat, "siret-length");
        }
        statusButton();
    });
}

/**
 * check the validity of the Siret number
 * Use fect() or XMLHttpRequest
 * @param {string} inputField 
 */
const checkSiret = function () {
    siretField.addEventListener('blur', function () {
        messageField("siret-length");
        if (regexSiret.test(this.value)) {
            const urlApi = 'https://entreprise.data.gouv.fr/api/sirene/v1/siret/';
            // const urlApi = 'http://127.0.01:1234';  // test erreur serveur
            if (window.fetch) { // fetch
            // if (1 == 2) { // xhr
                const getSiretFetch = async function (inputField) {
                    try {
                        let requestApi = new Request(urlApi + inputField.value);
                        let requestApiInit = {
                            method: 'GET',
                            headers: {
                                'Accept-Encoding': 'gzip, deflate',
                                'Referer': 'https://entreprise.data.gouv.fr/api_doc_sirene'
                            },
                            cache: 'no-cache'
                        }
                        let responseRequest = await fetch(requestApi, requestApiInit);
                        let responseApiData = await responseRequest.json();
                        if (responseRequest.ok) {

                            if (responseApiData.etablissement) {
                                console.log(responseRequest.statusText)
                                inputField.classList.remove("invalid");
                                inputField.classList.add('valid');

                            } else {
                                console.log("ce siret n'exsite pas");
                                console.log(responseRequest.statusText)
                                inputField.classList.remove("valid");
                                inputField.classList.add("invalid");
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
                                inputField.classList.remove("invalid");
                                inputField.classList.add('valid');
                            } else {
                                console.log("siret pas valide");
                                siretField.classList.remove("valid");
                                siretField.classList.add("invalid");
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
            this.classList.remove("valid");
            this.classList.add("invalid");
            elementMessage(siretGroup, messageSiretFormat, "siret-length");
        }
        statusButton();
    });
}


/***
 * 
 * Display message for user about the password length
 */
const checkPasswordLength = function () {
    passwordField.addEventListener('keyup', function () {
        messageField("password-length");
        if (this.value.length > 7) {
            this.classList.remove("invalid");
            this.classList.add('valid');
        } else {
            this.classList.remove("valid");
            this.classList.add("invalid");
            elementMessage(passwordGroup, messagePasswordLength(this), "password-length");
        }
        statusButton();
    });
}


/***
 * 
 * Display message for user about confirmation password
 */
const checkPasswordMatch = function () {
    passwordField.addEventListener('keyup', function () {
        messageField("password-match");
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
            statusButton();
        }
    });

    passwordConfirmField.addEventListener('keyup', function () {
        messageField("password-match");
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
            statusButton();
        }
    });
}


/**
 * 
 * status of the button according to the state of the field
 */
const statusButton = function () {
    if (fieldsRequired.length == document.getElementsByClassName('valid').length) {
        styleButtonOn();
    } else {
        styleButtonOff();
    }
}


eventOnLoad();
usernameGroup ? checkUsernameLength() : "";
emailGroup ? checkEmailFormat() : "";
usernameEmailGroup ? checkUsernameEmailFormat() : "";
siretGroup ? checkSiret() : "";
passwordGroup ? checkPasswordLength() : "";
passwordGroup && passwordConfirmGroup ? checkPasswordMatch() : "";