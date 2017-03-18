'use strict';

const Promise = require('promise');

//getGiphy function will retrieve the first gif from Giphy based on the provided string
//Export the 'getGiphy' function
//webpack this as a module
//require this module on a page
//exec the getGiphy function on a term
//append that result gif to the page

//What am I going to need?
//Webpack
//Gulp
//NodeJS
//Lodash
//Express

//Takes a set of dimensions and returns a URL of a placehold.it image
const kittyfy = (count) => {
    const endpoint = 'https://api.giphy.com/v1/gifs/search?q=funny+cat&api_key=dc6zaTOxFJmzC&offset=' + randomIntBetween(0,1000) + '&limit=' + count;

    // Use it!
    return get(endpoint);
};

function randomIntBetween(min,max)
{
    return Math.floor(Math.random()*(max-min+1)+min);
}

function get(url) {
  // Return a new promise.
  return new Promise(function(resolve, reject) {
    // Do the usual XHR stuff
    var req = new XMLHttpRequest();
    req.open('GET', url);

    req.onload = function() {
      // This is called even on 404 etc
      // so check the status
      if (req.status == 200) {
        // Resolve the promise with the response text
        resolve(req.response);
      }
      else {
        // Otherwise reject with the status text
        // which will hopefully be a meaningful error
        reject(Error(req.statusText));
      }
    };

    // Handle network errors
    req.onerror = function() {
      reject(Error("Network Error"));
    };

    // Make the request
    req.send();
  });
}

module.exports = kittyfy;
