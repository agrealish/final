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

function taskadd() {
  var noteForm = document.getElementById('addtask');
  var params = 'type=addtaskdb';
  var params += '&user=' + noteForm.elements['userident'].value;
  var params += '&tname=' + noteForm.elements['taskname'].value;
  var params += '&pname=' + noteForm.elements['projname'].value;
  var params += '&tdesc=' + noteForm.elements['taskdesc'].value;
  var params += '&due=' + noteForm.elements['duedt'].value;
  var params += '&priority=' + noteForm.elements['priority'].value;
  var params += '&notes=' + noteForm.elements['notes'].value;
  queryDB(params);
}