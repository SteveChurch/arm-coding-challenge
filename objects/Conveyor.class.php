<?php
/**
 * Created by PhpStorm.
 * User: SteveChurch
 * Date: 15/07/2017
 * Time: 17:12
 *
 * The Conveyor class
 *
 * This object stores all information about the conveyor.
 */

namespace ProductionPlant;

/***
 * Class Conveyor
 * @package ProductionPlant
 */
class Conveyor
{
    /*** @var array */
    // EXAMPLE OF ARRAY
    //    public $conveyor = array(
    //        array(
    //            'part' => 'A',
    //            'locked' => false
    //        ),
    //        array(
    //            'part' => 'B',
    //            'locked' => true
    //        ),
    //
    //    );
    public $conveyor = array();

    /*** @var object */
    private $product_references;


    /***
     * Worker constructor.
     */
    function __construct() {

        $this->product_references = new Product();

    }
    /***
     * Reset all locked sections on the conveyor belt,
     * This should be called after all pickers / workers have done their jazz.
     */
    public function resetAllLockedSections() {

        foreach($this->conveyor as $key => $conveyor_slot) {
             $this->conveyor[$key]['locked'] = false;
        }

    }


    /***
     * @param $slot_id
     * @return mixed
     */
    public function getSlotPart($slot_id) {

        $slot = $this->conveyor[$slot_id];

        if(!$slot['locked']) {

            $part = $slot['part'];      // Get the part
            $slot['part'] = null;       // reset the value of this slot
            $this->lockSlot($slot_id);  // lock this slot

            return $part; // Return the slots value for use by the worker
        }
    }

    /***
     * Lock a given slot
     * @param $slot_id
     * @return bool
     */
    public function lockSlot($slot_id) {
        $this->conveyor[$slot_id]['locked'] = true;
        return true;
    }

    /***
     * Produce a random part from the components list
     */
    public function produceRandomPart() {

        // Combine the parts with the buffer
        $parts          = $this->product_references->getParts();
        $parts_buffer   = $this->product_references->getPartsBuffer();
        $parts_combined = array_merge($parts,$parts_buffer);

        $random_part = array_rand($parts_combined, 1);

        $new_part = array(
            'part'      => $parts_combined[$random_part],
            'locked'    => false
        );

        array_unshift($this->conveyor, $new_part);



    }

    /***
     * Put down a product, this is used after checking a worker has a product
     * @param $check
     * @param $slot
     * @return bool
     */
    public function putDown($check, $slot) {
        $buffer = $this->product_references->getPartsBuffer()[0];
        if($check && $this->conveyor[$slot]['part'] == $buffer) {
            $this->conveyor[$slot]['part'] = $this->product_references->getProduct();
            return true;
        }
        return false;
    }

    /***
     * @param $slot
     */
    public function emptySlot($slot) {
        $this->conveyor[$slot]['part'] = $this->product_references->getPartsBuffer()[0];
    }

    public function canWorkerPutDown($slot) {
        $active_slot = $this->conveyor[$slot];

        if(!$active_slot['locked'] && $active_slot['part'] == ' ') {
            return true;
        }
        return false;
    }

    /***
     * @return mixed
     */
    public function countProducts() {
        $product_counter = 0;
        foreach($this->conveyor as $part) {
            $product_ref = $this->product_references->getProduct();
            if($part['part'] == $this->product_references->getProduct()) {
                $product_counter++;
            }
        }
        return $product_counter;
    }

    /***
     * Look through the parts and return a count of them in the final Conveyor
     */
    public function countParts() {

        foreach($this->product_references->getParts() as $key => $part) {
            $part_counter = 0;

            foreach($this->conveyor as $key => $slot) {
                if($slot['part'] == $part) {
                    $part_counter++;
                }

            }

            $result[$part] = $part_counter;
        }

        return $result;
    }


    /***
     * @return array
     */
    public function getConveyor() {
        return $this->conveyor;
    }

}