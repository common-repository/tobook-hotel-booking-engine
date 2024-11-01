/* console.log() suppression */
window.debug = true;

if (!window.debug) {
    if (!window.console) window.console = {};

    var methods = ["log", "debug", "warn", "info"];

    for (var i = 0; i < methods.length; i++) {
        console[methods[i]] = function () { };
    }
}
