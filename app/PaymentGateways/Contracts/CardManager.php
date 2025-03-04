<?php
namespace App\PaymentGateways\Contracts;

interface CardManager
{
    /**
     * Add a payment method (card) to a customer.
     *
     * @param string $customerId The customer's unique identifier.
     * @param string $paymentMethodId The payment method's unique identifier.
     * @return mixed
     */
    public function addCardToCustomer(string $customerId, string $paymentMethodId);

    /**
     * Set a default payment method for a customer.
     *
     * @param string $customerId The customer's unique identifier.
     * @param string $paymentMethodId The payment method's unique identifier.
     * @return mixed
     */
    public function setDefaultPaymentMethod(string $customerId, string $paymentMethodId);

    /**
     * Remove a payment method (card) from a customer.
     *
     * @param string $paymentMethodId The payment method's unique identifier.
     * @return mixed
     */
    public function removeCardFromCustomer(string $paymentMethodId);

    /**
     * List all payment methods (cards) for a customer.
     *
     * @param string $customerId The customer's unique identifier.
     * @return mixed
     */
    public function listCustomerCards(string $customerId);

    /**
     * Retrieve details of a specific payment method (card).
     *
     * @param string $paymentMethodId The payment method's unique identifier.
     * @return mixed
     */
    public function getCardDetails(string $paymentMethodId);
}