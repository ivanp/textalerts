var App = window.App || {};

/** We will put all of our variables and resources (URL-s, listings etc) **/
App.data = {};

// All widgets should be defined here
App.widgets = {};

/**
 * Send post request to specific link
 *
 * @param string the_link
 */
App.postLink = function(the_link) {
  var form = $(document.createElement('form'));
  form.attr({
    'action' : the_link,
    'method' : 'post'
  });

  var submitted_field = $(document.createElement('input'));
  submitted_field.attr({
    'type'  : 'hidden',
    'name'  : 'submitted',
    'value' : 'submitted'
  });

  form.append(submitted_field);

  $('body').append(form);

  form.submit();
  return false;
};

/**
 * Convert & -> &amp; < -> &lt; and > -> &gt;
 *
 * @param str
 * @return string
 */
App.clean = function(str) {
  if(typeof(str) == 'string') {
    str = str.replace(/&/g, '&amp;');
    str = str.replace(/\>/g, '&gt;');
    str = str.replace(/\</g, '&lt;');
  }

  return str;
};

/**
 * JS version of lang function / helper
 *
 * @param string content
 * @param object params
 */
App.lang = function(content, params) {
  var translation = content;

  if(typeof(App.langs) == 'object') {
    if(App.langs[content]) {
      translation = App.langs[content];
    }
  }

  if(typeof params == 'object') {
    for(key in params) {
      translation = translation.replace(':' + key, App.clean(params[key]));
    } // if
  } // if
  return translation;
};

/**
 * JavaScript implementation of isset() function
 *
 * Usage example:
 *
 * if(isset(undefined, true) || isset('Something')) {
 *   // Do stuff
 * }
 *
 * @param value
 * @return boolean
 */
App.isset = function(value) {
  return !(typeof(value) == 'undefined' || value === null);
};

/**
 * Add async variables to async link
 *
 * @param string link
 * @return string
 */
App.makeAsyncUrl = function(link) {
  if (link) {
    if (link.indexOf('?') < 0) {
      link += '?async=1&skip_layout=1'
    } else {
      link += '&async=1&skip_layout=1'
    } // if
    return link;
  } else {
    return false;
  }
};

/**
 * Convert MySQL formatted datetime string to Date() object
 *
 * @params String timestamp
 * @return Date
 */
App.mysqlToDate = function(timestamp) {
  var regex=/^([0-9]{2,4})-([0-1][0-9])-([0-3][0-9]) (?:([0-2][0-9]):([0-5][0-9]):([0-5][0-9]))?$/;
  var parts=timestamp.replace(regex, "$1 $2 $3 $4 $5 $6").split(' ');
  return new Date(parts[0], parts[1], parts[2], parts[3], parts[4], parts[5]);
};

/**
 * Attach more parameters to URL
 *
 * @param string url
 * @param object extend_with
 */
App.extendUrl = function(url, extend_with) {
  if(!url || !extend_with) {
    return url;
  } // if

  var extended_url = url;
  var parameters = [];

  extended_url += extended_url.indexOf('?') < 0 ? '?' : '&';

  for(var i in extend_with) {
    if(typeof(extend_with[i]) == 'object') {
      for(var j in extend_with[i]) {
        parameters.push(i + '[' + j + ']' + '=' + extend_with[i][j]);
      } // for
    } else {
      parameters.push(i + '=' + extend_with[i]);
    } // if
  } // for

  return extended_url + parameters.join('&');
};

/**
 * Parse numeric value and return integer or float
 *
 * @param String value
 * @return mixed
 */
App.parseNumeric = function(value) {
  if(typeof(value) == 'number') {
    return value;
  } else if(typeof(value) == 'string') {
    if(value.indexOf('.') > -1) {
      var separator = '.';
    } else if(value.indexOf(',') > -1) {
      var separator = ',';
    } else {
      return value == '' ? 0 : parseInt(value);
    } // if

    var separator_pos = value.indexOf(separator);
    var whole_number = parseInt(value.substring(0, separator_pos));
    var decimal = parseFloat('0.' + value.substring(separator_pos + 1));

    return value.indexOf('-', 0) ? whole_number + decimal : whole_number - decimal;
  } else {
    return NaN;
  }
};

/**
 * Parse string and return version object
 *
 * @param String str
 * @return Object
 */
App.parseVersionString = function (str) {
    if (typeof(str) != 'string') { return false; }
    var x = str.split('.');
    // parse from string or default to 0 if can't parse
    var maj = parseInt(x[0]) || 0;
    var min = parseInt(x[1]) || 0;
    var pat = parseInt(x[2]) || 0;
    return {
        major: maj,
        minor: min,
        patch: pat
    }
}; // parseVersionString

/**
 * compare versions, if they are same returns 0, if first is lower returns -1, and
 * if second is lower returns 1
 *
 * @var string version1
 * @var string version2
 * @return int
 */
App.compareVersions = function (version1, version2) {
  version1 = App.parseVersionString(version1);
  version2 = App.parseVersionString(version2);

  if (version1.major < version2.major) {
    return -1;
  } else if (version1.major > version2.major) {
    return 1;
  } else {
    if (version1.minor < version2.minor) {
      return -1;
    } else if (version1.minor > version2.minor) {
      return 1;
    } else {
      if (version1.patch < version2.patch) {
        return -1;
      } if (version1.patch > version2.patch) {
        return 1;
      } else {
        return 0;
      } // if
    } // if
  } // if
} // compareVersions

jQuery.fn.highlightFade = function() {
  return this.effect("highlight", {}, 1000)
};

function ucfirst( str ) {
  str += '';
  var f = str.charAt(0).toUpperCase();
  return f + str.substr(1);
}