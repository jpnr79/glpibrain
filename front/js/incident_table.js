function searchonTableId() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  //there are various inputs on the page, by specifying the column we can search only in the specified column
  input = document.getElementById("searchById");
  filter = input.value.toUpperCase();
  table = document.getElementById("incidentTable");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[0];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function searchonTableTitle() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  //there are various inputs on the page, by specifying the column we can search only in the specified column
  input = document.getElementById("searchByTitle");
  filter = input.value.toUpperCase();
  table = document.getElementById("incidentTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function searchonTableDate() {
  // Declare variables
  var input, filter, table, tr, td, i, dateValue;
  input = document.getElementById("searchByDate");
  //look from the input date to the end of the month
  filter = input.value.toUpperCase();
  table = document.getElementById("incidentTable");
  tr = table.getElementsByTagName("tr");

  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[2];
    if (td) {
      dateValue = td.textContent || td.innerText;
      if (dateValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function searchonTableAsignee() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  //there are various inputs on the page, by specifying the column we can search only in the specified column
  input = document.getElementById("searchByAsignee");
  filter = input.value.toUpperCase();
  table = document.getElementById("incidentTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[3];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function searchonTableStatus() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  //this is select tag, so we need to get the value of the selected option
  input = document.getElementById("searchByStatus");
  filter = input.options[input.selectedIndex].value.toUpperCase();
  table = document.getElementById("incidentTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[4];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function searchonTableCategory() {
  // Declare variables
  var input, filter, table, tr, td, i, txtValue;
  //there are various inputs on the page, by specifying the column we can search only in the specified column
  input = document.getElementById("searchByCategory");
  filter = input.value.toUpperCase();
  table = document.getElementById("incidentTable");
  tr = table.getElementsByTagName("tr");

  // Loop through all table rows, and hide those who don't match the search query
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[5];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }
  }
}

function hello() {
  alert('Hello World!');
}

function openWindow(id, details, token) {
  //Create a div block over the screen which size adjustes dynamically, also append a title to this div window 
  //First obscure the screen
  var div1 = document.createElement('div');
  div1.style.position = 'absolute';
  div1.style.top = '0';
  div1.style.left = '0';
  div1.style.width = '100%';
  div1.style.height = '100%';
  div1.style.backgroundColor = 'black';
  div1.style.opacity = '0.5';
  div1.style.zIndex = '1000';
  document.body.appendChild(div1);
  //Then create the window
  var div2 = document.createElement('div');
  div2.style.position = 'absolute';
  div2.style.top = '50%';
  div2.style.left = '50%';
  div2.style.width = '50%';
  div2.style.height = '50%';
  div2.style.border = '1px solid black';
  div2.style.borderRadius = '10px';
  div2.style.transform = 'translate(-50%, -50%)';
  div2.style.backgroundColor = 'white';
  div2.style.zIndex = '1001';
  div2.style.overflow = 'auto';
  document.body.appendChild(div2);
  var title = document.createElement('h1');
  title.style.textAlign = 'center';
  title.innerHTML = 'Add solution';
  div2.appendChild(title);
  //Then create the content of the window
  var content = document.createElement('div');
  content.style.padding = '10px';
  content.style.textAlign = 'center';
  content.innerHTML = id + ' - ' + details;
  div2.appendChild(content);
  var form = document.createElement('form');
  form.style.width = '80%';
  form.style.margin = '10px';
  form.style.marginLeft = 'auto';
  form.style.marginRight = 'auto';
  form.style.display = 'block';
  var textarea = document.createElement('textarea');
  textarea.style.width = '100%';
  textarea.style.height = '200px';
  textarea.style.margin = '10px';
  textarea.style.display = 'block';
  form.appendChild(textarea);
  div2.appendChild(form);
  var submit = document.createElement('button');
  submit.innerHTML = 'Submit';
  submit.style.width = '80%';
  submit.style.margin = '10px';
  submit.style.marginLeft = 'auto';
  submit.style.marginRight = 'auto';
  submit.style.display = 'block';
  div2.appendChild(submit);
  submit.onclick = function () {
    $.ajax({
      method: 'POST',
      url: 'middleware.php',
      dataType: 'Json',
      //add {% csrf_token %}
      data: {
        '_glpi_csrf_token': token,
        'action': 'retrainSolution',
        'arguments': [id, textarea.value]
      },
      //Alert the error if there is one
      error: function (error) {
        alert('Error: ' + JSON.stringify(error));
      },
      //If successfull alert the user
      success: function (response) {
        alert(response['message']);
      }

    })

    document.body.removeChild(div1);
    document.body.removeChild(div2);
  }
  //Finally create a button to close the window
  var button = document.createElement('div');
  button.innerHTML = 'X';
  button.style.position = 'absolute';
  button.style.top = '10px';
  button.style.right = '10px';
  //on button hover change the color and cursor style
  button.onmouseover = function () {
    button.style.color = 'red';
    button.style.cursor = 'pointer';
  }
  button.onclick = function () {
    document.body.removeChild(div1);
    document.body.removeChild(div2);
  }
  div2.appendChild(button);
}

function closeDiv() {
  document.body.removeChild(document.body.lastChild);
}

function showDetail(content) {
  //Create a speech bubble with the content of the incident in the position of the coursor and close it when the mouse is not over it
  var bubble = document.createElement('div');
  bubble.style.position = 'absolute';
  bubble.style.top = event.clientY + 'px';
  bubble.style.left = event.clientX + 'px';
  bubble.style.width = '10%';
  bubble.style.height = 'auto';
  bubble.style.padding = '10px';
  bubble.style.backgroundColor = 'white';
  bubble.style.border = '1px solid black';
  bubble.style.borderRadius = '10px';
  //bubble.style.zIndex = '1001';
  bubble.innerHTML = content;
  document.body.appendChild(bubble);
  bubble.onmouseleave = function () {
    document.body.removeChild(bubble);
  }
}

function loading() {
  var loading = document.createElement('div');
  loading.style.position = 'absolute';
  loading.style.top = '50%';
  loading.style.left = '50%';
  loading.style.transform = 'translate(-50%, -50%)';
  loading.style.width = '100px';
  loading.style.height = '100px';
  loading.style.borderRadius = '50%';
  loading.style.border = '5px solid black';
  loading.style.borderTop = '5px solid red';
  loading.style.animation = 'spin 2s linear infinite';
  document.body.appendChild(loading);
}