var queryDB = function(querySt) {
  var req = new XMLHttpRequest();
  if(!req) {
    document.getElementById("error").innerHTML = "Unable to create HttpRequest";
  }
  //from lecture on AJAX
  req.onreadystatechange = function() {
    if(this.readyState === 4) {
      if(this.status === 200) {
        document.getElementById("main").innerHTML = req.responseText;
      }
      else {
        document.getElementById("error").innerHTML = "There was a problem with the query";
      }
    }
    req.open('POST', 'query.php', true);
    req.send(querySt);
  }
}

function loginDB() {
  var noteForm = document.getElementById('loginform');
  var params = 'type=login';
  var params += '&user=' + noteForm.elements['username'].value;
  var params += '&pass=' + noteForm.elements['password'].value;
  queryDB(params);
}

function adduserDB() {
  var noteForm = document.getElementById('adduser');
  var params = 'type=adduser';
  var params += '&user=' + noteForm.elements['username'].value;
  var params += '&pass=' + noteForm.elements['password'].value;
  queryDB(params);
}