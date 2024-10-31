<?php

namespace SAPb1;

class SAPException extends \Exception{
    
    protected $statusCode;
    
    /**
     * Initializes a new instance of SAPException.
     */
    public function __construct(Response $response){
        $this->statusCode = $response->getStatusCode();
        parent::__construct($response->getBody());
    }
    
    public function getStatusCode() : int{
        return $this->statusCode;
    }
}
