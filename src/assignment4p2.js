window.onload = function () {
	localStorage.setItem('category', 'allMovies');
    makeRequest('action=init');
    document.getElementById('addButton').addEventListener('click', addVideo);
	document.getElementById('deleteAll').addEventListener('click', deleteAll);
};

function makeRequest(statement) {
    var xmlhttp;
    if (window.XMLHttpRequest)
    {// code for IE7+, Firefox, Chrome, Opera, Safari
	xmlhttp = new XMLHttpRequest();
    }
    else
    {// code for IE6, IE5
	xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function()
    {
	  if (xmlhttp.readyState == 4 && xmlhttp.status==200) {
	    var response = xmlhttp.responseText;
	    var elem = document.getElementById('videos');
	    elem.innerHTML = response;
	    addListeners();
	  }
    }
    if(statement == 'action=add') {
	var elem = document.getElementById('addForm');
	statement += '&name=' + elem.elements['name'].value;
	statement += '&category=' + elem.elements['category'].value;
	statement += '&length=' + elem.elements['vlength'].value;
    }
    var filterCategory = 'filterCatagory=' + localStorage.getItem('category');
	statement += '&' + filterCategory;
    xmlhttp.open("GET",'assignment4p2.php?' + statement,true);
    xmlhttp.send();
}

function addListeners() {
    var positions = document.getElementsByClassName('position');
    for (var i = 0; i < positions.length; i++) {
	console.log(positions[i].textContent);
	positions[i].addEventListener('click', filterPosition);
    }
    var removes = document.getElementsByClassName('remove');
    for (var i = 0; i < removes.length; i++) {
	removes[i].addEventListener('click', removeVideo);
    }
    var checkouts = document.getElementsByClassName('checkout');
    for (var i = 0; i < checkouts.length; i++) {
	checkouts[i].addEventListener('click', checkOutVideo);
    }
	var checkIns = document.getElementsByClassName('checkIn');
    for (var i = 0; i < checkIns.length; i++) {
	checkIns[i].addEventListener('click', checkInVideo);
    }
}

function filter() {
  document.getElementById("videos").innerHTML = "";
  var x = document.getElementById("dropDown");
  var val = x.options[x.selectedIndex].value;
  localStorage.setItem('category', val);
  var statement = 'action=filter&category=' + val;
  makeRequest(statement);
}

function checkOutVideo() {
    var statement = 'action=checkout&id=' + this.parentNode.id;
    makeRequest(statement);
}

function checkInVideo() {
    var statement = 'action=checkIn&id=' + this.parentNode.id;
    makeRequest(statement);
}

function removeVideo() {
    var statement = 'action=remove&id=' + this.parentNode.id;
    makeRequest(statement);
}

function addVideo() {
  var valid = document.forms["addForm"]["name"].value;
  var numeric = document.forms["addForm"]["vlength"].value;
  if (valid==null || valid=="") {
    alert("Name is a required field.");
  }
  else if(isNaN(numeric) || numeric < 0) {
    alert("Video length must be a non-negative number.");
  }
  else {
    var statement = 'action=add';
    makeRequest(statement);
  }
}

function deleteAll() {
    var statement = 'action=deleteAll';
    makeRequest(statement);
}

