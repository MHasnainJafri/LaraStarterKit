<?php
namespace App\PaymentGateways\Contracts;

interface CustomerManager
{
    /**
     * Create a new customer.
     *
     * @param array $data Customer data (e.g., email, name).
     * @return mixed
     */
    public function createCustomer(array $data);

    /**
     * Retrieve a customer by ID.
     *
     * @param string $customerId The customer's unique identifier.
     * @return mixed
     */
    public function getCustomer(string $customerId);

    /**
     * Update a customer's details.
     *
     * @param string $customerId The customer's unique identifier.
     * @param array $data Data to update (e.g., email, name).
     * @return mixed
     */
    public function updateCustomer(string $customerId, array $data);

    /**
     * Delete a customer.
     *
     * @param string $customerId The customer's unique identifier.
     * @return mixed
     */
    public function deleteCustomer(string $customerId);
}