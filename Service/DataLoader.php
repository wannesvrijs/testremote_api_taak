<?php
interface DataLoader
{
    //class needs database access, required
    public function __construct(DBInterface $DBI);

    //class uses SQL statement to query database, with optional id
    public function MakeSQL( $id = null );

    //class needs a method to load data, with optional id
    public function Load( $id = null );

    //class needs a method to transform raw data into an array of objects (AbstractItems or subclasses (Flower, City, ...))
    public function MakeArrayOfItems( $data );

    //class needs a method to get the array of objects (AbstractItems)
    /**
     * @return AbstractItem[]
     */
    public function getItems();

}