$(document).ready(function() {
  url1 = window.location.href.replace(location.protocol + '//' + location.host, '');
  url  = url1.split('/');
  
  if( url.length >= 3 )
    url = url[0] + '/' + url[1] + '/' + url[2];
  else if( url.length == 2 )
    url = url[0] + '/' + url[1];
  else if( url.length == 1 )
    url = url[0];
  
  url = url.replace('/show', '/index');
  
  $('nav a[href*="/' + url + '"]').css('text-decoration', 'underline');
  $('nav a[href*="/' + url1 + '"]').css('text-decoration', 'underline');
  $('nav a[href ="/' + url + '"]').css('color', 'White');
  $('nav a[href ="/' + url1 + '"]').css('color', 'White');
});


$(document).ready(function() {
  $('table.sort').tablesorter();
});
