// logout function ############################################################################################
function logout() {
  window.localStorage.clear();
  window.location = 'login.html';
}
// checking token is save  #####################################################################################
if (localStorage.gym_token == null) {
  window.location = 'login.html';
} else if (moment().diff(moment(localStorage.gym_expires)) > 0) {
  window.location = 'login.html';
}

// getting saved token and rest id ############################################################################
const userData = JSON.parse(localStorage.gym_user);
const expires = JSON.parse(localStorage.gym_expires);
const token = JSON.parse(localStorage.gym_token);
