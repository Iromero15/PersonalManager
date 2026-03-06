var app = angular.module('ExpenseApp', []);

// Configuramos el provider de HTTP
app.config(function($httpProvider) {
    
    // Inyectamos nuestro interceptor en la pila
    $httpProvider.interceptors.push(function($q, $window) {
        return {
            // Se ejecuta ANTES de que el request salga al backend
            'request': function(config) {
                // Solo inyectamos el token si le pegamos a nuestra API
                if (config.url.startsWith('/api')) {
                    var token = $window.localStorage.getItem('auth_token');
                    if (token) {
                        config.headers['Authorization'] = 'Bearer ' + token;
                    }
                }
                return config;
            },
            
            // Se ejecuta CUANDO VUELVE la respuesta y hay un error
            'responseError': function(rejection) {
                // Si el backend nos devuelve 401 (No autorizado) porque el token expiró o no existe
                if (rejection.status === 401) {
                    $window.localStorage.removeItem('auth_token'); // Limpiamos la basura
                    $window.location.href = 'login.html'; // Lo pateamos al login
                }
                return $q.reject(rejection);
            }
        };
    });
});