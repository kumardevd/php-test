<?php
class Notfound extends Controller
{
    function __construct() {

    }

    public function notFound404()
    {
        $data = ['message' => 'Not Found']; /** whatever you're serializing **/;
        $this->response($data, 404);
    }
}