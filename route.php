<?php
class Route extends Controller
{
    private $REQUEST_URI;
    private $ROUTE_LOCATION = [
      'transactions' => 'doTransactions',
      'notfound' => 'notFound404',
    ];
    private $CURRENT_ROUTE;

    function __construct() {
      $this->REQUEST_URI = $_SERVER["REQUEST_URI"];
      $this->getCurrentRoute();
      $this->detectRoute();
    }

    private function getCurrentRoute() {
      $link_array = explode('/', $this->REQUEST_URI);
      $this->CURRENT_ROUTE = end($link_array);
    }

    private function detectRoute() {
      $navigateTo = '';
      $className = '';
      if(isset($this->ROUTE_LOCATION[$this->CURRENT_ROUTE])) {
        $navigateTo = $this->ROUTE_LOCATION[$this->CURRENT_ROUTE];
        $className = $this->CURRENT_ROUTE;
      } else {
        $navigateTo = $this->ROUTE_LOCATION['notfound'];
        $className = 'notfound';
      }
      require_once(strtolower($className).'.php');
      $instance = new $className();
      $instance->$navigateTo();
    }
}

$RouteObj = new Route();