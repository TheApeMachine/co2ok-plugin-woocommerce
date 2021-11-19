export default class Meta {

  windowCache = false;

  constructor(str) {
    this.cacheWindow();
    return window.getFunctionFromString(str);
  }

  cacheWindow() {
    // Guard against setting this on every call to the constructor.
    // Think of it as a singleton pattern.
    if (this.windowCache) { return; }

    // We define a new method on `window` which allows us to pass in a string and get
    // a function definition back we can call. This is a pretty useful little piece of
    // meta-programming allowing you to pass in strings to a method and react to those
    // with functions.
    window.getFunctionFromString = function(string) {
      var scope = window;
      var scopeSplit = string.split('.');
      
      for (var i=0; i<scopeSplit.length-1; i++) {
        scope = scope[scopeSplit[i]];
        if (scope == undefined) { return; }
      }
      
      return scope[scopeSplit[scopeSplit.length - 1]];
    }

    this.windowCache = true;
  }

}
