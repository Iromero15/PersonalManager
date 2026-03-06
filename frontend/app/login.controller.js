angular.module('ExpenseApp').controller('LoginController', function($scope, $http, $window) {
    // El payload exacto que pidió el backend
    $scope.credenciales = {
        email: '',
        password: ''
    };
    $scope.error = false;

    $scope.iniciarSesion = function() {
        // Le pegamos al POST /api/login (sin el http://localhost:8080 porque el navegador ya sabe que está ahí)
        $http.post('/api/login', $scope.credenciales)
            .then(function(response) {
                // ATENCIÓN ACÁ: Leemos access_token como exigió el backend
                $window.localStorage.setItem('auth_token', response.data.access_token);
                
                // Si salió todo bien, redirigimos al index
                $window.location.href = 'index.html';
            })
            .catch(function(error) {
                console.error("Error de login:", error);
                $scope.error = true;
            });
    };
});