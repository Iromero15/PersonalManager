angular.module('ExpenseApp').controller('DashboardController', function($scope, $http, $window) {
    $scope.resumen = {
        balance: 0,
        ingresos: 0,
        gastos: 0
    };
        // Objeto para manejar la edición
    $scope.editando = {};

    // 1. Abrir el modal y cargar los datos
    $scope.prepararEdicion = function(tx) {
        // Hacemos una copia (shallow copy) para no modificar la tabla en vivo
        $scope.editando = angular.copy(tx);
        // Abrimos el modal de Bootstrap por ID (usando jQuery que ya tenés cargado)
        $('#modalEdicion').modal('show');
    };

    // 2. Guardar los cambios (PUT /api/transactions/{id})
    $scope.guardarEdicion = function() {
        $http.put('/api/transactions/' + $scope.editando.id, $scope.editando)
            .then(function() {
                $('#modalEdicion').modal('hide'); // Cerramos el modal
                $scope.cargarTransacciones();     // Refrescamos lista
                $scope.cargarResumen();           // Refrescamos totales
            })
            .catch(function(err) {
                console.error("Error al editar", err);
                alert("No se pudo guardar el cambio.");
            });
    };
    $scope.cargarResumen = function() {
        $http.get('/api/summary')
            .then(function(response) {
                // Mapeamos lo que venga del back a nuestro objeto de la vista
                $scope.resumen.balance = response.data.balance;
                $scope.resumen.ingresos = response.data.income;
                $scope.resumen.gastos = response.data.expense;
            })
            .catch(function(err) {
                console.error("Error al cargar el resumen", err);
            });
    };
    $scope.titulo = "Gestor de Gastos";
    $scope.transacciones = [];
    $scope.categorias = []; // Nuestra "tabla" de categorías en memoria
    
    $scope.nuevaTransaccion = {
        description: '',
        amount: null,
        type: 'expense',
        category_id: null, // Ahora lo va a elegir el usuario
        transaction_date: new Date().toISOString().split('T')[0]
    };

    $scope.nuevaCategoriaNombre = ""; // Buffer para la nueva categoría

    // --- CARGAR CATEGORÍAS ---
    $scope.cargarCategorias = function() {
        $http.get('/api/categories')
            .then(function(response) {
                $scope.categorias = Array.isArray(response.data) ? response.data : response.data.data;
                // Si hay categorías, pre-seleccionamos la primera por defecto
                if ($scope.categorias.length > 0) {
                    $scope.nuevaTransaccion.category_id = $scope.categorias[0].id;
                }
            });
    };

    // --- AGREGAR CATEGORÍA NUEVA ---
    $scope.agregarCategoria = function() {
        if (!$scope.nuevaCategoriaNombre) return;

        $http.post('/api/categories', { name: $scope.nuevaCategoriaNombre })
            .then(function(response) {
                $scope.nuevaCategoriaNombre = ""; // Limpiamos
                $scope.cargarCategorias(); // Recargamos la lista
                alert("Categoría creada!");
            })
            .catch(function(error) {
                console.error("Error al crear categoría", error);
            });
    };

    // --- CARGAR TRANSACCIONES (Lo que ya tenías) ---
    $scope.cargarTransacciones = function() {
        $http.get('/api/transactions').then(function(response) {
            $scope.transacciones = Array.isArray(response.data) ? response.data : response.data.data;
        });
    };

    $scope.agregarTransaccion = function() {
        $http.post('/api/transactions', $scope.nuevaTransaccion)
            .then(function() {
                $scope.nuevaTransaccion.description = '';
                $scope.nuevaTransaccion.amount = null;
                $scope.cargarTransacciones();
            });
    };
    $scope.eliminarTransaccion = function(id) {
        if (!confirm("¿Estás seguro de que querés borrar este movimiento?")) return;

        $http.delete('/api/transactions/' + id)
            .then(function() {
                // Sincronizamos la lista y el resumen
                $scope.cargarTransacciones();
                $scope.cargarResumen();
            })
            .catch(function(err) {
                console.error("No se pudo borrar la transacción", err);
                alert("Error al intentar borrar.");
            });
    };

    // --- ELIMINAR CATEGORÍA (DELETE /api/categories/{id}) ---
    $scope.eliminarCategoria = function(id) {
        if (!confirm("Ojo: Si borrás la categoría, podés romper las transacciones asociadas. ¿Seguís?")) return;

        $http.delete('/api/categories/' + id)
            .then(function() {
                $scope.cargarCategorias();
            })
            .catch(function(err) {
                console.error("Error al borrar categoría", err);
                // Laravel te va a tirar error si la categoría tiene transacciones (por el 'restrict' de tu migración)
                alert("No podés borrar una categoría que ya tiene gastos asociados.");
            });
    };
    $scope.logout = function() {
        // 1. Avisamos al backend (Ruta: POST /api/logout)
        $http.post('/api/logout')
            .finally(function() {
                // 2. Pase lo que pase con la red, limpiamos el disco local
                $window.localStorage.removeItem('auth_token');
                // 3. Patada al login
                $window.location.href = 'login.html';
            });
    };
    // Inicialización: Traemos todo de entrada
    $scope.cargarResumen();
    $scope.cargarCategorias();
    $scope.cargarTransacciones();
    $scope.cargarCategorias();
    $scope.cargarTransacciones();
});