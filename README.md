# SAPb1 (Version 2)
A simple and easy to use PHP library for SAP Business One Service Layer API. This documentation is also available at https://youtu.be/ADnmzLQ4ifU 

## Usage
Create an array to store your SAP Business One Service Layer configuration details. 

```php
$config = [
    'https' => true,
    'host' => 'IP or Hostname',
    'port' => 50000,
    "username" => "SAP USERNAME",
    "password" => "SAP PASSWORD",
    "company" => "SAP COMPANY",
    'sslOptions' => [
        "cafile" => "path/to/certificate.crt",
        "verify_peer" => true,
        "verify_peer_name" => true,
    ],
    'version' => 2
];
```

Create a new Service Layer session.

```php
$sap = SAPClient::new($config);
```

The static `new()` method will return a new instance of `SAPClient`. The SAPClient object provides a `service($name)` method which returns a new instance of Service with the specified name. Using this Service object you can perform CRUD actions.

### Querying A Service

The `query()` method of the Service class returns a new instance of Query. The Query class allows you to use chainable methods to filter the requested service.

The following code sample shows how to filter Sales Orders using the Orders service.

```php
$sap = SAPClient::new($config);
$orders = $sap->getService('Orders');

$result = $orders->query()
    ->select('DocEntry,DocNum')
    ->orderBy('DocNum', 'asc')
    ->limit(5)
    ->find(); 
```
The `find()` method will return a collection of records that match the search criteria. To return a specific record using an `id` use the `find($id)` method on a Service object.

```php
...
$order = $sap->getService('Orders')
    ->find(123456); // DocEntry value
```
Depending on the service, `$id` may be a numeric value or a string. 

### Creating A Service

The following code sample shows how to create a new Sales Order using the create() method of the Service object.

```php
...
$orders = $sap->getService('Orders');

$result = $orders->create([
    'CardCode' => 'BP Card Code',
    'DocDueDate' => 'Doc due date',
    'DocumentLines' => [
        [
            "ItemCode" => "Item Code",
            "Quantity" => 100,
        ]
    ]
]);
```
You must provide any User Defined Fields that are required to create a Sales Order.

### Updating A Service

The following code sample demonstrates how to update a service using the `update()` method of the Service object.

```php
...
$orders = $sap->getService('Orders');

$result = $orders->update(19925, [
    'Comments' => 'Comment added here'
]);
```
Note that the first argument to the update() method is the `id` of the entity to update. In the case of a Sales Order the `id` is the DocEntry field.

### Adding Headers

You can specify oData headers by calling the headers() method on a Service instance with an array of headers.

```php
...
$orders = $sap->getService('Orders');
$orders->setHeaders(['Prefer' => 'odata.maxpagesize=0']);

$result = $orders->query()
    ->select('DocEntry,DocNum')
    ->find();
```
