<?php

namespace SAPb1;

/**
 * Service class contains methods to perform CRUD actions on a service.
 */
class Service{
    
    private $config;
    private $session;
    private $serviceName;
    private $headers = [];
    
    /**
     * Initializes a new instance of Service.
     */
    public function __construct(Config $configOptions, array $session, string $serviceName){
        $this->config = $configOptions;
        $this->session = $session;
        $this->serviceName = $serviceName;
    }

    /** 
     * Returns a single result using the specified $id.
     */
    public function find($id) : Response{
        
        if(is_string($id)){
            $id = "'" . str_replace("'", "''", $id) . "'";
        }
        
        return $this->doRequest('GET', $this->config->getServiceUrl($this->serviceName) . '(' . $id . ')');
    }

    /**
     * Creates an entity. Returns an HTTP Response.
     */
    public function create(array $data) : Response{
        return $this->doRequest('POST', $this->config->getServiceUrl($this->serviceName), $data);
    }
    
    /**
     * Updates an entity using $id.
     * The HTTP method used is PACTH.
     * Returns an HTTP Response.
     */
    public function update($id, array $data) : Response{
        
        if(is_string($id)){
            $id = "'" . str_replace("'", "''", $id) . "'";
        }

        return $this->doRequest('PATCH', $this->config->getServiceUrl($this->serviceName) . '(' . $id . ')', $data);
    }

    /**
     * Updates an entity using $id.
     * The HTTP method used is PUT.
     * Returns an HTTP Response.
     */
    public function put($id, array $data) : Response{
        
        if(is_string($id)){
            $id = "'" . str_replace("'", "''", $id) . "'";
        }

        return $this->doRequest('PUT', $this->config->getServiceUrl($this->serviceName) . '(' . $id . ')', $data);
    }
    
    /**
     * Deletes an entity using $id.     
     * Returns an HTTP Response.
     */
    public function delete($id) : Response{
        
        if(is_string($id)){
            $id = "'" . str_replace("'", "''", $id) . "'";
        }

        return $this->doRequest('DELETE', $this->config->getServiceUrl($this->serviceName) . '(' . $id . ')');
    }
    
    /**
     * Performs an action on an entity using $id.     
     * Returns an HTTP Response.
     */
    public function action($id, string $action) : Response{
        
        if(is_string($id)){
            $id = "'" . str_replace("'", "''", $id) . "'";
        }

        return $this->doRequest('POST', $this->config->getServiceUrl($this->serviceName) . '(' . $id . ')' . '/' . $action);
    }

    /**
     * Returns a new instance of SAPb1\Query.
     */
    public function query() : Query{
        return new Query($this->config, $this->session, $this->serviceName, $this->headers);
    }

    /**
     * Specifies request headers.
     */
    public function setHeaders(array $headers) : Service{
        $this->headers = $headers;
        return $this;
    }
    
    private function doRequest($method, $path, array $postData = []) : Response{
        $request = new Request($path, $this->config->getSSLOptions());
        $request->setMethod($method);
        $request->setCookies($this->session);
        $request->setHeaders($this->headers);

        if($method != 'GET'){
            $request->setPost($postData);
        }
        
        return $request->getResponse();
    }
}