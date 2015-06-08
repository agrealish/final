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

//function to grab data from the form on updatenotes.php
function showtasks() {
  var noteForm = document.getElementById('getInfo');
  var params = 'type=' + noteForm.elements['getData'].value;
  var params += '&userid=' + noteForm.elements['username'].value;
  queryDB(params);
}

function completeT(taskid) {
  var params = 'type=complete';
  var params += '&id=' + taskid;
  queryDB(params);
}