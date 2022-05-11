/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// Pour empêcher les copier-coller des champs email et password
window.onload = function() {
    var email = document.getElementById('courriel');
    var confirmcourriel = document.getElementById('confirmcourriel');
    var password = document.getElementById('password');
    var confirmpassword = document.getElementById('confirmpassword');  

    courriel.onpaste = function(e) {
        e.preventDefault();
    }
    confirmcourriel.onpaste = function(e) {
        e.preventDefault();
    }
    password.onpaste = function(e) {
        e.preventDefault();
    }
    confirmpassword.onpaste = function(e) {
        e.preventDefault();
    }
}