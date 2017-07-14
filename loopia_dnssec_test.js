// Based on the algorithm used by http://www.braintrust.co.nz/ipv6wwwtest/ and popularized by Wikipedia

var _loopia_dnssectest_done = 0;
var _loopia_dnssectest_goodzone_done = 0;
var _loopia_dnssectest_badzone_done = 0;
var _loopia_dnssectest_timeout = 60000;
var _loopia_dnssectest_timeout_func = false;
var _loopia_dnssectest_resultdiv_id = false;
 
var badzone_url = "https://www.dnssectest-badzone.se/";
var goodzone_url = "https://www.dnssectest.se/";
 
function _loopia_dnssectest_startTest(_loopia_dnssectest_hiddendiv, _loopia_dnssectest_resultdiv) {
	_loopia_dnssectest_resultdiv_id = _loopia_dnssectest_resultdiv;
 
	_loopia_dnssectest_hiddendiv = document.getElementById(_loopia_dnssectest_hiddendiv);
 
	_loopia_dnssectest_hidden_images = '<img height="1" width="1" src="' + goodzone_url + 'empty_goodzone.gif" id="_loopia_dnssectest_goodzone_img" onload="_loopia_dnssectest_goodzone_done = 1; _loopia_dnssectest_checkFinished();" onerror="_loopia_dnssectest_goodzone_done = 2; _loopia_dnssectest_checkFinished();" />';
	_loopia_dnssectest_hidden_images += '<img height="1" width="1" src="' + badzone_url + 'empty_badzone.gif" id="_loopia_dnssectest_badzone_img" onload="_loopia_dnssectest_badzone_done = 1; _loopia_dnssectest_checkFinished();" onerror="_loopia_dnssectest_badzone_done = 2; _loopia_dnssectest_checkFinished();" />';
 
	_loopia_dnssectest_hiddendiv.innerHTML = _loopia_dnssectest_hidden_images;
}
 

function _loopia_dnssectest_checkFinished() {
	if ((!_loopia_dnssectest_goodzone_done) || (!_loopia_dnssectest_badzone_done)) {
		if (!_loopia_dnssectest_timeout_func) {
			_loopia_dnssectest_timeout_func = window.setTimeout('_loopia_dnssectest_sendFinalResults()', _loopia_dnssectest_timeout);
		}
	} else {
		_loopia_dnssectest_sendFinalResults();
	}
}

function _loopia_dnssectest_sendFinalResults() {
	if (!_loopia_dnssectest_done) {
		if (_loopia_dnssectest_timeout_func) {
			window.clearTimeout(_loopia_dnssectest_timeout_func);
		}
 
		var _loopia_dnssectest_status;
 
		if (_loopia_dnssectest_goodzone_done == 0 || _loopia_dnssectest_goodzone_done == 2) {
			_loopia_dnssectest_status = "dnssec_not_working";
		} else {
			if (_loopia_dnssectest_badzone_done == 0 || _loopia_dnssectest_badzone_done == 2) {
				_loopia_dnssectest_status = "dnssec_working_and_verified";
			} else {
				_loopia_dnssectest_status = "dnssec_working_but_not_verified";
			}
		}
 
		var _loopia_dnssectest_resultdiv = document.getElementById(_loopia_dnssectest_resultdiv_id);
		_loopia_dnssectest_resultdiv.className = _loopia_dnssectest_status;
 
		var _loopia_dnssectest_infotext = document.getElementById("dnssec_info_init");
		_loopia_dnssectest_infotext.style.display = "none";
 
		var infotext = "not_working";
		if (_loopia_dnssectest_status == "dnssec_working_and_verified") {
			infotext = "working";
		}
 
		_loopia_dnssectest_infotext = document.getElementById("dnssec_info_" + infotext);
		_loopia_dnssectest_infotext.style.display = "block";
 
                _loopia_dnssectest_done = 1;
	}
}