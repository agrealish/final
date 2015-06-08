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

//function to grab data from the form on addproject.php
function addproj() {
  var noteForm = document.getElementById('addproject');
  var params = 'type=addproj';
  var params += '&pm=' + noteForm.elements['pm'].value;
  var params += '&projname=' + noteForm.elements['pname'].value;
  queryDB(params);
}
