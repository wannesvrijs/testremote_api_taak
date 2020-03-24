<?php
class City extends AbstractItem
{
    private $name;
    private $number_of_inhabitants;
    private $coordinate_x;
    private $coordinate_y;

    private $weather;

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }


    /**
     * @return mixed
     */
    public function getNumberOfInhabitants()
    {
        return $this->number_of_inhabitants;
    }

    /**
     * @param mixed $number_of_inhabitants
     */
    public function setNumberOfInhabitants($number_of_inhabitants)
    {
        $this->number_of_inhabitants = $number_of_inhabitants;
    }

    /**
     * @return mixed
     */
    public function getCoordinateX()
    {
        return $this->coordinate_x;
    }

    /**
     * @param mixed $coordinate_x
     */
    public function setCoordinateX($coordinate_x)
    {
        $this->coordinate_x = $coordinate_x;
    }

    /**
     * @return mixed
     */
    public function getCoordinateY()
    {
        return $this->coordinate_y;
    }

    /**
     * @param mixed $coordinate_y
     */
    public function setCoordinateY($coordinate_y)
    {
        $this->coordinate_y = $coordinate_y;
    }

    public function Coordinates()
    {
        return $this->coordinate_x . " / " . $this->coordinate_y;
    }

    /**
     * @return mixed
     */
    public function getWeather()
    {
        return $this->weather;
    }

    /**
     * @param mixed $weather
     */
    public function setWeather($weather)
    {
        $this->weather = $weather;
    }

}