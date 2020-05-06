$(document).ready(function() {

  // REGISTRATION USERNAME AVAILABILITY BORDER CHENGER
  $("#form-register input[type='username']").on('blur', function(e) {
    $.get("checkusername.php", {
        username: $("#form-register input[type='username']").val()
      })
      .done(function(data) {
        if (data) {
          $("#form-register input[type='username']").css("border-color", "red");
        } else {
          $("#form-register input[type='username']").css("border-color", "green");
        }
      });
  });
  // if you click a delete button confirm
  $("input[name='delete']").on("click", function(ev) {
    let x = confirm("Are you sure you'd like to delete this?");
    if (!x) {
      ev.preventDefault();
    }
  });

// Password strength using the plugin
  $("#form-register input[name='password']").passtrength({
    minChars: 8,
    tooltip: true,
    passwordToggle: false,
    textWeak: "Weak",
    textMedium: "Medium",
    textStrong: "Strong",
    textVeryStrong: "Very Strong"
  });

  $("form[name='form-r'] input[name='submit']").on("click", function(ev) {
    let errors = [];
    //check username
    $.get("checkusername.php", {
        username: $("#form-register input[type='username']").val()
      })
      .done(function(data) {
        if (data) {
          errors.push("Username is taken");
        }
      });
      
    //check email
    $.get("checkuseremail.php", {
        email: $("#form-register input[type='email']").val()
      })
      .done(function(data) {
        if (data) {
          errors.push("Email is taken");
        }
      });
      
    // check that a passwords was entered
    if ($("#form-register input[name='password']").val() == "" || $("#form-register input[name='password']").val() == "") {
      errors.push("Passwords are required")
    }
    
    // check that password is greater than 8 characters
    if ($("#form-register input[name='password']").val().length < 8) {
      errors.push("Passwords must be at least 8 characters in length");
    }
    
    // check password strength
    let passstrength = $("#form-register input[name='password']").parent().attr('class');
    if (passstrength.includes("weak") || passstrength.includes("medium")) {
      errors.push("Password must be stronger than weak/medium strength")
    }
    
    //check both passwords are the same
    if ($("#form-register input[name='password']").val() != $("#form-register input[name='password']").val()) {
      errors.push("Both passwords must be the same");
    }
    if ($("#form-register input[name='securityq1']").val() == "" ||
      $("#form-register input[name='securitya1']").val() == "" ||
      $("#form-register input[name='securityq2']").val() == "" ||
      $("#form-register input[name='securitya2']").val() == "") {
      errors.push("Both security questions and answers are required");
    }

    confirm(errors.join("\n"));
    if (errors.length > 0) {
      ev.preventDefault();
    }
  });

// Part of the code taken from the following tutorial:
// https://www.w3schools.com/howto/howto_css_login_form.asp
  // Get the modal
  let addmodal = document.getElementById("modal-window");
  let editmodal = document.getElementById("editmodal-window");

  // When the user clicks anywhere outside of the modal, close it
  window.onclick = function(event) {
    if (event.target == addmodal) {
      addmodal.style.display = "none";
    }
    else if (event.target == editmodal){
      editmodal.style.display = "none";
    }
  }
});

function deletelistitem(target, owner) {
  let x = confirm("Are you sure you'd like to delete this?");
  if (x) {
    let itemid = target.parentElement.parentElement.parentElement.id;
    $.post("deletelistitem.php",
      {
        itemid: itemid,
        userid: owner
      }
    ).done( function(data){
      alert(data);

      window.location = window.location.href;
    });
  }
}

// Edit list item and I feel lucky modal windows
function editlistitem(target, owner) {
  let itemid = target.parentElement.parentElement.parentElement.id;
  $.get("editlistitem.php",
    {
      itemid: itemid
    }
  ).done(function(jdata) {
    let data = JSON.parse(jdata);
    $("#editmodal-window input[name='id']").val(itemid);
    $("#editmodal-window input[name='title']").val(data.listtitle);
    $("#editmodal-window input[name='description']").val(data.listdescription);
    // if image exists
    if (data.listimage != ""){$("#editmodal-window label[for='avatar'] strong").html("Upload a NEW picture");}
    $("#editmodal-window input[name='date-completed']").val(String(data.listcompletedate));
    $("#editmodal-window input[name='public']").prop("checked", data.listpublic);
    $("#editmodal-window input[name='completed']").prop("checked", data.listcompleted);
    $("#editmodal-window").css("display", "block");
  });
}

function luckylistitem(userid) {
  if (userid == null) { userid = ""}
  $.get("luckylistitem.php",
    {
      id: userid
    }
  ).done(function(jdata) {
    console.log(jdata);
    let data = JSON.parse(jdata);
    $("#modal-window #listtitle").text(data.listtitle);
    $("#modal-window #listdescription").text(data.listdescription);
    $("#modal-window .bucket-image").attr("src", (data.listimage != null ? data.listimage : "./img/placeholder.png"));
    $("#modal-window #listcompletedate").text(data.listcompletedate == null ? "" : String(data.listcompletedate));
    $("#modal-window").css("display", "block");
  });
}
