<?php
namespace App\PaymentGateways\Contracts;

interface PaymentMethodManager
{
    /**
     * Create a new payment method.
     *
     * @param array $data Payment method details (e.g., card number, expiration date).
     * @return mixed
     */
    public function createPaymentMethod(array $data);

    /**
     * Retrieve details of a specific payment method.
     *
     * @param string $paymentMethodId The payment method's unique identifier.
     * @return mixed
     */
    public function getPaymentMethod(string $paymentMethodId);

    /**
     * Update a payment method's details.
     *
     * @param string $paymentMethodId The payment method's unique identifier.
     * @param array $data Data to update (e.g., expiration date, billing details).
     * @return mixed
     */
    public function updatePaymentMethod(string $paymentMethodId, array $data);

    /**
     * Delete a payment method.
     *
     * @param string $paymentMethodId The payment method's unique identifier.
     * @return mixed
     */
    public function deletePaymentMethod(string $paymentMethodId);

    /**
     * Attach a payment method to a customer.
     *
     * @param string $customerId The customer's unique identifier.
     * @param string $paymentMethodId The payment method's unique identifier.
     * @return mixed
     */
    public function attachPaymentMethodToCustomer(string $customerId, string $paymentMethodId);

    /**
     * Detach a payment method from a customer.
     *
     * @param string $paymentMethodId The payment method's unique identifier.
     * @return mixed
     */
    public function detachPaymentMethodFromCustomer(string $paymentMethodId);

    /**
     * List all payment methods for a customer.
     *
     * @param string $customerId The customer's unique identifier.
     * @return mixed
     */
    public function listCustomerPaymentMethods(string $customerId);

    /**
     * Set a default payment method for a customer.
     *
     * @param string $customerId The customer's unique identifier.
     * @param string $paymentMethodId The payment method's unique identifier.
     * @return mixed
     */
    public function setDefaultPaymentMethod(string $customerId, string $paymentMethodId);
}