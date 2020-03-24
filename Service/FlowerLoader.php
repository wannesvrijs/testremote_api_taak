<?php
class FlowerLoader implements DataLoader
{
    private $dbinterface; //werkt met MYSQLI_Manager, maar kan ook met PDO_Manager werken
    private $items;          //array of items (Flower objects)

    public function __construct( DBInterface $DBI )
    {
        $this->dbinterface = $DBI;
    }

    public function MakeSQL( $id = null )
    {
        $sql = "select * from images INNER JOIN flower ON flo_img_id=img_id";
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
            $flower = new Flower();

            //general properties
            $flower->setId( $row['img_id'] );
            $flower->setFileName( $row['img_filename'] );
            $flower->setTitle( $row['img_title'] );
            $flower->setWidth( $row['img_width'] );
            $flower->setHeight( $row['img_height'] );

            //city specific properties
            $flower->setName( $row['flo_name'] );
            $flower->setColor( $row['flo_color'] );
            $flower->setMonths( $row['flo_months'] );

            $this->items[] = $flower;
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