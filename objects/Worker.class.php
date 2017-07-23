<?php
/**
 * Created by PhpStorm.
 * User: SteveChurch
 * Date: 15/07/2017
 * Time: 17:12
 *
 * The main Worker Class,
 *
 * This object will store all information about a worker, their hands, their position etc.
 */

namespace ProductionPlant;

/***
 * Class Worker
 * @package ProductionPlant
 */
class Worker
{

    /*** @var string */
    public $conveyor_row        = ''; // top / bottom

    /*** @var int */
    public $conveyor_position   = null;

    /*** @var int */
    public $worker_id           = null;

    /*** @var array */
    public $worker_hands        = array( 'left' => null, 'right' => null );

    /*** @var object */
    public $product_references;


    /***
     * Worker constructor.
     */
    function __construct($worker_id, $conveyor_position, $conveyor_row) {

        $this->product_references = new Product();

        // Set instantiated variables
        $this->conveyor_row         = $conveyor_row;
        $this->conveyor_position    = $conveyor_position;
        $this->worker_id            = $worker_id;

    }

    /***
     * @param $part
     * @return bool
     */
    public function pickupPart($part) {

        // First make sure we dont already have this part
        if(in_array($part, $this->worker_hands)) {
            return false;
        }

        foreach($this->worker_hands as $key => $hand) {
            if($hand == null || $hand == '' || $hand == ' ') {
                $this->worker_hands[$key] = $part;
                return true;
            }
        }
        return false; // Worker failed to pick up a part, their hands must be full

    }


    /***
     * @return array
     */
    public function getHands() {
        return $this->worker_hands;
    }


    /***
     * Check to see if the worker can make a product,
     * This is done by checking all of workers hands to match against
     * a constant which is part of the product object.
     * If whats in the workers hands matches the parts array then we will create a product.
     *
     * This gives scope for people with more than 2 hands, who knows :)
     *
     */
    public function tryMakeProduct() {

        $total_parts_required = count($this->product_references->getParts());
        $count = 0;

        // first check to make sure our worker has both A and B in their hands,
        // if so then we are ok to begin creating a P (Product)
        foreach($this->product_references->getParts() as $part) {
            foreach($this->worker_hands as $hand) {
                if($part == $hand) {
                    $count++; // start counting the matches we have
                }
            }
        }

        // the parts required matched the parts we have
        if($count == $total_parts_required) {

            // Unset all of our hands
            foreach($this->worker_hands as $key => $hand) {
                $this->worker_hands[$key] = '';
            }

            // Set out first hand to have a product.
            $this->worker_hands['left'] = $this->product_references->getProduct();

            return true;
        }

        return false;



    }

    /***
     * Check to see if this worker have a product in their hand ready to release & release it, return true
     * @return bool
     */
    public function putDownProduct(){
        foreach($this->worker_hands as $key => $hand) {
            if($hand == $this->product_references->getProduct()) {
                $this->worker_hands[$key] = $this->product_references->getPartsBuffer()[0];
                return true;
            }
        }
        return false;
    }

    /***
     * Check we have a product in our hands
     * @return bool
     */
    public function hasProduct() {
        foreach($this->worker_hands as $key => $hand) {
            if($hand == $this->product_references->getProduct()) {
                return true;
            }
        }
        return false;
    }

}