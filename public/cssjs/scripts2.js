/**
 * scripts.js
 *
 * local javascript for text version
 *
 * based on from CS50 problem set 7
 */

$(document).ready(function() {
    $("#clockpick").clockpick({
    starthour : 8,
    endhour : 18,
    showminutes : true,
    military : true
    }
    ); 
});