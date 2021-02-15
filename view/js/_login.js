// event on submit ################################################################
$("form").submit(e => {
  e.preventDefault();
  let formData = {
    login_id: $("input[name='login_id']").val(),
    password: $("input[name='password']").val()
  };
  $.ajax({
    type: "POST",
    data: JSON.stringify(formData),
    url: `${baseURL}/login`,
    contentType: "application/json",
    success: function (data) {
      if (data.status == true) {
        if (data.row.token) { // if pin code is verified
            localStorage.setItem('gym_token', JSON.stringify(data.row.token));
            localStorage.setItem('gym_expires', JSON.stringify(data.row.expires));
            localStorage.setItem('gym_user', JSON.stringify(data.row.user));
            window.location = './index.html';
        } else { // if pin code is not verified
          localStorage.setItem('gym_data', JSON.stringify(data.row.user));
          window.location = './pin_code.html';
        }
      } else {
        $('.alert').removeAttr("class").addClass('alert alert-danger');
        $('.alert').html(data.message);
        $('.alert').slideDown();
        setTimeout(() => $('.alert').slideUp(), 5000);
      }
    }
  });
});
