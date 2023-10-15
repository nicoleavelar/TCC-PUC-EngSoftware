document.addEventListener('DOMContentLoaded', function () {
    document.getElementById('loginForm').addEventListener('submit', function (e) {
        e.preventDefault();
        console.log('Formulário enviado');
        const email = document.getElementById('email').value;
        const password = document.getElementById('password').value;


        // Envia os dados para o servidor (login.php)
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'login.php', true);
        xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState == 4 && xhr.status == 200) {
                    window.location.href = 'dashboard.php';
            }} ;
            
        
        xhr.send('email=' + email + '&password=' + password);
    });
});
