angular.module('orderApp', [])
  .controller('OrderController', function($scope) {
    $scope.open = false;
    $scope.orders = [];
    $scope.newOrder = {};
    $scope.isLogged = false;
    $scope.user = {};
    $scope.token = '';
    $scope.products = [];
    $scope.tokenLocalStorage = localStorage.getItem('token');
    $scope.selectedProduct = [];

    var submit = this;

    //user login with email and password
    submit.login = function() {
        axios.post('http://127.0.0.1:8000/api/login', $scope.user)
        .then(function (response) {
            $scope.isLogged = true;
            $scope.user = response.data;
            $scope.token = $scope.user.token;
            localStorage.setItem('token', $scope.token);
        })
        .catch(function (error) {
            console.log(error);
        });
    }

    //check user logged token
     function checkToken() {
        if(localStorage.getItem('token') != null) {
            $scope.isLogged = true;
            console.log($scope.isLogged);
        }
    }

    checkToken();

    //récuperer toute les commandes

    function getOrders() {
        axios.get('http://127.0.0.1:8000/api/orders', {
          headers: {
            'Authorization': 'Bearer ' + $scope.tokenLocalStorage
          }
        })
        .then(function (response) {
          $scope.orders = response.data.orders;;
          console.log($scope.orders);
        })
        .catch(function (error) {
          console.log(error);
        });
      }

    getOrders();

    //ajouter une commande
    submit.addOrder = function() {
        axios.post('http://127.0.0.1:8000/api/orders', {
            product_id: [$scope.selectedProduct],
            quantity: $scope.newOrder.quantity
        }, {
            headers: {
                'Authorization': 'Bearer ' + $scope.tokenLocalStorage
            }
        })
        .then(function (response) {
            // $scope.orders.push(response.data);
            $scope.newOrder = {};
            $scope.open = false;
            $timeout(function() {
                $scope.orders.push(response.data);
            }, 0);
        })
        .catch(function (error) {
            console.log(error);
        });
    }

    //supprimer une commande
    submit.deleteOrder = function(order) {
        axios.delete('http://127.0.0.1:8000/api/orders/' + order.id, {
            headers: {
                'Authorization': 'Bearer ' + $scope.tokenLocalStorage
            }
        })
        .then(function (response) {
            var index = $scope.orders.indexOf(order);
            $scope.orders.splice(index, 1);
            $timeout(function() {
                console.log("Suppression réussie");
            }, 0);
        })
        .catch(function (error) {
            console.log(error);
        });
    }

    //modifier une commande
    submit.updateOrder = function(order) {
        axios.put('http://127.0.0.1:8000/api/orders/' + order.id, order, {
            headers: {
                'Authorization': 'Bearer ' + $scope.tokenLocalStorage
            }
        })
        .then(function (response) {
            order.edit = false;
        })
        .catch(function (error) {
            console.log(error);
        });
    }

    //fetch products
    function getProducts() {
        axios.get('http://127.0.0.1:8000/api/products', {
            headers: {
                'Authorization': 'Bearer ' + $scope.tokenLocalStorage
              }
        })
        .then(function (response) {
            $scope.products = response.data;
        })
        .catch(function (error) {
            console.log(error);
        });
    }

    getProducts();

    
  });