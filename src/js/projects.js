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
    req.open('POST', 'query.php');
    req.send(querySt);
  }
};

function showprojs()() {
  var noteForm = document.getElementById('getInfo');
  var params = 'type=' + noteForm.elements['getData'].value;
  queryDB(params);
}

function completeT() {
  var button = document.getElementById('');
  var params = 'type=complete';
  var params += '&id=' + ;
  queryDB(params);
}