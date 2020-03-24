<?php
class CityLoader implements DataLoader
{
    private $dbinterface; //werkt met PDO_Manager, maar kan ook met MYSQLI_Manager werken
    private $items;          //array of items (City objects)
    private $OWMS;

    public function __construct( DBInterface $DBI )
    {
        $this->dbinterface = $DBI;
    }

    public function injectOpenWeatherMapService( OpenWeatherMapService $OWMS )
    {
        $this->OWMS = $OWMS;
    }

    public function MakeSQL( $id = null )
    {
        $sql = "select * from images INNER JOIN city ON cit_img_id=img_id";
        if ( $id > 0 ) $sql .= " where img_id=$id";

        return $sql;
    }

    public function Load( $id = null )
    {
        $this->MakeArrayOfItems( $this->dbinterface->GetData( $this->MakeSQL( $id ) ) );

        return $this->items;
    }

    public function MakeArrayOfItems( $data )
    {
        foreach ( $data as $row )
        {
            $city = new City();

            //general properties
            $city->setId( $row['img_id'] );
            $city->setFileName( $row['img_filename'] );
            $city->setTitle( $row['img_title'] );
            $city->setWidth( $row['img_width'] );
            $city->setHeight( $row['img_height'] );

            //city specific properties
            $city->setName( $row['cit_name'] );
            $city->setNumberOfInhabitants( $row['cit_inhabitants'] );
            $city->setCoordinateX( $row['cit_coordinate_x'] );
            $city->setCoordinateY( $row['cit_coordinate_y'] );

            //find and set weather
            $city->setWeather( $this->OWMS->getWeather( $city->getName() ) );

            $this->items[] = $city;
        }
    }

    /**
     * @return AbstractItem[]
     */
    public function getItems()
    {
        return $this->items;
    }

}