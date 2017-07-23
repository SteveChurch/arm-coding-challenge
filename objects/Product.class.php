<?php
/**
 * Created by PhpStorm.
 * User: SteveChurch
 * Date: 15/07/2017
 * Time: 18:04
 *
 * This is a helper class which will used throughout the project.
 *
 * This makes it easy to change any references in the future and have
 * no static coding.
 *
 * These should all be CONSTANT values, non chanheable.
 *
 */

namespace ProductionPlant;


class Product
{
    /***
     * Define constants,
     *  These will be used for global cross referencing to allow
     *  for scalability and additions of parts.
     */
    private $PARTS           = array('A', 'B');
    private $PARTS_BUFFER    = array(' ');
    private $PRODUCT         = 'P';

    /***
     * @return array
     */
    public function getParts() {
        return $this->PARTS;
    }

    /***
     * @return array
     */
    public function getPartsBuffer() {
        return $this->PARTS_BUFFER;
    }

    /***
     * @return string
     */
    public function getProduct() {
        return $this->PRODUCT;
    }


}