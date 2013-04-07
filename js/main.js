var JQUERY_JS      = 'jquery-1.8.2.min.js';
var SOCKET_IO      = 'socket.io.min.js';
var UTIL_JS        = 'util.js';
var EXTERN_VARS_JS = '/tmp/externs.js';
var DATA_SERVER    = 'http://localhost/';

phantom.injectJs(JQUERY_JS);
phantom.injectJs(SOCKET_IO);
phantom.injectJs(UTIL_JS);

phantom.onError = function(msg, trace) {
	clog(msg);
};

var htmls = [];

function follow(url, callback)
{
	var page = require('webpage').create();

	page.onLoadStarted = function () {
		console.log('Start loading: ' + url);
	};

	page.onLoadFinished = function (status) {

		if (status === 'fail')
		{
			clog(url + ': Failed');
		}
		else
		{
			var html = page.content;
			html = stripScripts(html);
			htmls.push( { 'url' : url, 'html' : html } );
		}

		page.release();
		callback.apply();
		console.log('Loading finished: ' + url);
	}

	page.open( url );
}

function process(urls) {
	if (urls.length > 0) {
		var url = urls[0];
		urls.splice(0, 1);
		follow(url, process);
	} else {
		var allElemHtml = search();
		prettyPrint(allElemHtml);
		phantom.exit();
	}
}

var search = function() {

	var allElemHtml = {};

	for (var i = 0; i < htmls.length; ++i)
	{
		var page = require('webpage').create();
		page.content = htmls[i]['html'];
		var url = htmls[i]['url'];

		page.injectJs(JQUERY_JS);
		page.injectJs(UTIL_JS);
		page.injectJs(EXTERN_VARS_JS);

		var sel = page.evaluate( function() {
			return sel;
		});

		var elemHtml = getElemHtml(page);

		if (elemHtml.length) {
			allElemHtml[url] = elemHtml;
		}

		page.release();
	}
	return allElemHtml;
}

var getJson = function(url) {
	var json = $.json(DATA_SERVER + 'data.php');
	clog(json);
	return json;
};

var main = function() {

	$.getJSON( 'http://localhost/StyleSpy/php/sspy_get_content.php?url=foo.com&list=1', function(data) {
		$.each(data, function(key, val) {
			clog(key + ' ' + val);
		});
	});

	process(urls);
}();
