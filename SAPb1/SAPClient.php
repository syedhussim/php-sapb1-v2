<?php

namespace SAPb1;

/**
 * SAPClient manages access to SAP B1 Service Layer and provides methods to 
 * perform CRUD operations.
 */
class SAPClient{
    
    private $config = [];
    private $session = [];

    /**
     * Initializes SAPClient with configuration.
     */
    public function __construct(array $configOptions){
        $this->config = new Config($configOptions);
    }
    
    /**
     * Returns the current SAP B1 session data.
     */
    public function getSession() : array{
        return $this->session;
    }

    /**
     * Sets the current SAP B1 session data.
     */
    public function setSession(array $session) : SAPClient{
        $this->session = $session;
        return $this;
    }

    /**
     * Returns a new instance of SAPb1\Service.
     */
    public function getService(string $serviceName) : Service{
        return new Service($this->config, $this->session, $serviceName);
    }

    /**
     * Creates a new SAP B1 session and returns a new instance of SAPb1\Client.
     * Throws SAPb1\SAPException if an error occurred.
     */
    public static function new(array $configOptions) : SAPClient{
        
        $config = new Config($configOptions);

        $request = new Request($config->getServiceUrl('Login'), $config->getSSLOptions());
        $request->setMethod('POST');
        $request->setPost(['UserName' => $config->getUsername(), 'Password' => $config->getPassword(), 'CompanyDB' => $config->getCompany()]);
        $response = $request->getResponse(); 
        
        if($response->getStatusCode() === 200){
            $client = new SAPClient($configOptions);
            $client->setSession($response->getCookies());
            return $client;
        }
        
        throw new SAPException($response);
    }
}
