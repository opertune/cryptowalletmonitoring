const navbar = $('nav.navbar');
const navbarCollapse = $('.navbar-collapse');

// Adding classes to indicate the navbar state
navbarCollapse.on('show.bs.collapse', function () {
    navbar.addClass('expanded');

    // navbar button style
    $('#logoutButton').removeClass();
    $('#logoutButton').addClass('nav-link');

    $('#loginButton').removeClass();
    $('#loginButton').addClass('nav-link');

    $('#registerButton').removeClass();
    $('#registerButton').addClass('nav-link');
});

navbarCollapse.on('hide.bs.collapse', function () {
    navbar.removeClass('expanded');

    // navbar button style
    $('#logoutButton').removeClass();
    $('#logoutButton').addClass('btn btn-danger');

    $('#loginButton').removeClass();
    $('#loginButton').addClass('btn btn-danger me-2');

    $('#registerButton').removeClass();
    $('#registerButton').addClass('btn btn-primary');
});