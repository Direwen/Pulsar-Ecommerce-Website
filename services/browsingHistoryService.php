<?php

class BrowsingHistoryService {

    private $viewed_items = [];
    private $cookie_name = 'VIEWED_PRODUCTS';
    private $max_items = 3; // Limit to 3 viewed items

    public function __construct() {
        // Check if the cookie exists
        if (isset($_COOKIE[$this->cookie_name])) {
            // Assign the cookie value to $viewed_items
            $this->viewed_items = explode(',', $_COOKIE[$this->cookie_name]);
        } else {
            // Create a new cookie and assign it to $viewed_items
            $this->viewed_items = [];
            setcookie($this->cookie_name, '', time() + (86400 * 30), "/"); // 30 days expiration
        }
    }

    // Getter for $viewed_items
    public function getViewedItems() {
        return $this->viewed_items;
    }

    // Method to add the product ID to the cookie and update $viewed_items
    public function addViewedItem($productId) {
        // Remove the product if it already exists
        $this->viewed_items = array_diff($this->viewed_items, [$productId]);

        // Add the new product ID to the front
        array_unshift($this->viewed_items, $productId);

        // Limit to max_items
        if (count($this->viewed_items) > $this->max_items) {
            array_pop($this->viewed_items); // Remove the oldest item
        }

        // Update the cookie
        setcookie($this->cookie_name, implode(',', $this->viewed_items), time() + (86400 * 30), "/"); // 30 days expiration
    }

    // Method to remove the product ID from the front like a queue and update $viewed_items
    public function removeOldestViewedItem() {
        if (!empty($this->viewed_items)) {
            array_pop($this->viewed_items); // Remove the oldest item
            // Update the cookie
            setcookie($this->cookie_name, implode(',', $this->viewed_items), time() + (86400 * 30), "/"); // 30 days expiration
        }
    }
}