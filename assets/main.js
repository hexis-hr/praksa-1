
import './styles/main.min.css';
$(document).ready(function() {
    $('[data-support-tickets-table] tbody tr:not(:first-child)').on('click', function() {
        location.href="supportTicket.html"
    })
})

WebFont.load({
    google: {
        families: ['Nunito+Sans:400,700,900']
    }
});
window.App = {
    name: '',
    mediaPath: '../media/',
    scriptPath: '../scripts/',
    debug: true,
    verbose: true,
    device: {}
};
