<?php
/**
 * Created by PhpStorm.
 * User: SteveChurch
 * Date: 15/07/2017
 * Time: 17:11
 *
 *
 *
 * ARM - ProductionPlant Conveyor Coding Challenge.
 *
 * This simulation is designed to simulate the process of parts entering a production line, being handled by workers,
 * products being created and doing so following the guide lines of the coding challenge sent over.
 *
 *
 * The classes have been named to simulate the structure of a production line housed within a factory.
 * Project Files:
 *
 * @file index.php                        - Bootloader file, wrapper to initiate whole project
 * @file objects/Factory.class.php        - Container and core handler
 * @file objects/Conveyor.class.php       - Conveyor object, stores the conveyor array
 * @file objects/Worker.class.php         - Worker Object, stored data about a worker, their hands, their slot position
 * @file objects/Product.class.php        - This is a helper file to store some global constants.
 *
 */

include('objects/Factory.class.php');

// Initiate the factory passing through Conveyor Ticks and Workers
$factory = new \ProductionPlant\Factory(100, 6);

$factory->getDebugData();

/***
 * For further scope its important to note, the structure has been done this way to allow for the use of
 * multiple factories.
 *
 * A Factory can run as long as required with as many workers as required. Workers will be split into 2 teams.
 * This is to simulate a production line having to and bottom line workers.
 *
 * If further time was to be spent on this project it would be a good idea to pass the parts & end product via the core
 * Factory class. This way you could instantiate 2 factories producing different products.
 */


