<?php
/**
 * Created by PhpStorm.
 * User: SteveChurch
 * Date: 15/07/2017
 * Time: 17:11
 *
 * ARM - ProductionPlant Conveyor Coding Challenge.
 *
 * This simulation is designed to simulate the process of parts enunlockeda production line, being handled by workers,
 * products being created and doing so following the guide lines of the coding challenge sent over.
 *
 *
 * The classes have been named to simulate the structure of a production line housed within a factory.
 * Project Files:
 *
 *  @file objects/Factory.class.php        - Container and core handler
 *  @file objects/Conveyor.class.php       - Conveyor object, stores the conveyor array
 *  @file objects/Worker.class.php         - Worker Object, stored data about a worker, their hands, their slot position
 *
 */

namespace ProductionPlant;

include('Product.class.php');
include('Worker.class.php');
include('Conveyor.class.php');

use ProductionPlant\Product as Product;

/***
 * Class Factory
 * @package ProductionPlant
 */
class Factory
{

    /*** @var array or objects */
    private $workers = array();

    /*** @var object */
    private $product_references;

    /*** @var object */
    private $conveyor;

    private $ticks;

    private $workers_count;

    /***
     * Factory constructor.
     * @param $ticks    = The amount of cycles you would like this factory to run for
     * @param $workers  = The amount of workers you would like on a factory line.
     */
    function __construct($ticks, $workers, $debug = false) {

        $this->ticks    = $ticks;
        $this->workers_count  = $workers;

        // Load objects required to run a factory
        $this->product_references = new Product();
        $this->conveyor           = new Conveyor();


            // Init the workers
        if($this->createWorkers($workers)) {

                // Init the factory, lets start making some P's!
            $result = $this->initiateFactory($ticks);

        } else {

            $result['message']  = "Something went wrong starting up the factory..";
            $result['status']   = false;

        }

        return $result;


    }

    /***
     * Create the workers on our factory floor.
     * @param $workers
     * @return bool
     */
    private function createWorkers($workers) {

        $slot_counter = 0.0;

        $conveyor_build_check = 1;
        for($i = 0; $i < $workers; $i++) {

            // Use a bit check to determine ODD or EVEN. For TOP or BOTTOM workers.
            $worker_row = ($i & 1) ? 'BOTTOM' : 'TOP';

            // Floor a division by 2. This would then make sure every 2
            // increments sit on the same SLOT ID. 0 0 1 1 2 2 etc
            $worker_slot = floor($slot_counter / 2);

            try{

                $this->workers[] = new Worker($i, $worker_slot, $worker_row); // Init worker

                // Start building the production line (conveyor belt)
                // This is done to stop issues when workers try asking whats on the belt
                // and there is no entries in their array to ask. This would throw an exception
                if($conveyor_build_check <= $workers / 2)
                $this->conveyor->conveyor[] = array(
                    'part'      => $this->product_references->getPartsBuffer()[0],
                    'locked'    => false
                );
                $conveyor_build_check++;

            } catch (\Exception $e) {
                return false;
            }

            $slot_counter++;
        }

        return true;

    }

    private function initiateFactory($ticks) {

        for($tick = 0; $tick < $ticks; $tick++) { // For every tick lets start making some products

            $this->conveyor->produceRandomPart();

            foreach($this->workers as $key => $worker) { // For every tick on the line, let the workers do their stuff

                // Check to see if we can put down, then check to see if we have anything, else pickup.
                if($this->conveyor->canWorkerPutDown($worker->conveyor_position) && $worker->hasProduct()) {

                    $this->conveyor->putDown($worker->putDownProduct(), $worker->conveyor_position);

                } else {
                    if(!$worker->tryMakeProduct()) { // We dont have a P, try making one from whats in our hands

                        if($worker->pickupPart($this->conveyor->getSlotPart($worker->conveyor_position))) {// We couldn't make a product, pickup a part

                            // Part picked up.. Lets remove it from the conveyor
                            $this->conveyor->emptySlot($worker->conveyor_position);
                        };

                    }


                }



            }
            $this->conveyor->resetAllLockedSections(); // Reset all locks ready for our next round
        }

//        echo "<pre>";
//        print_r($this->conveyor);
//        echo "</pre>";

    }

    public function getDebugData() {

        echo "<h1>Workers: ".$this->workers_count."</h1>";
        echo "<h1>Cycles: ".$this->ticks."</h1>";
        // PRODUCTS

        echo "<h1>Created products: ".$this->conveyor->countProducts()."</h1>";

        // PARTS
        foreach($this->conveyor->countParts() as $key => $part) {
            echo "<h1>Leftover component -  $key: ".$part."</h1>";
        }

        // FINCAL CONVEYOR
        echo "<pre><h1>Final Conveyor Array</h1><br />";
        echo "<p>This is the total TICKS + TOTAL WORKERS. First slots are blanks, objects are needed to stop exceptions.<br/>
Nothing is locked in the results below as this is the final result. By this 
point everything has been unlocked again<br />";

        print_r($this->conveyor->getConveyor());
        echo "</pre>";


    }





}