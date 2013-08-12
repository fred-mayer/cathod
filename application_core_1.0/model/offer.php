<?php

class TOffer extends TMySQL
{
    public function getAdvertiser( TObject $offer )
    {
        $where = ($offer->idbrand == 0) ? '' : ' AND idbrand='.$offer->idbrand;

        return $this->select( 'SELECT a.name AS advertiser, o.url, o.price FROM offer o, advertiser a 
                                    WHERE o.idadvertiser=a.id 
                                        AND o.name=\''.$offer->name.'\'
                                        AND o.idcategory='.$offer->idcategory.$where )->orderBy( 'price' );
    }

    public function getAdvertiserById( $id )
    {
        return $this->select( 'SELECT * FROM advertiser WHERE id='.$id )->current();
    }

    public function getCategory( $id ) //return TObject или false в случаи отсутствия данных
    {
        if ( ($category = $this->getCategoryById( $id )) === false )
        {
            return false;
        }

        $result_category[] = $category;

        while ( $category->parent_id > 0 )
        {
            $category = $this->getCategoryById( $category->parent_id );

            array_unshift( $result_category, $category );
        }
        
        return new TObject( $result_category );
    }

    public function getCategoryById( $id ) //return TObject или false в случаи отсутствия данных
    {
        return $this->select( 'SELECT * FROM category WHERE id='.$id )->current();
    }
    
    public function getCategoryByParent_id( $parent_id ) //return TMySQLSelect
    {
        return $this->select( 'SELECT * FROM category WHERE parent_id='.$parent_id );
    }
    
    // Функция возвращает список брендов отсортированый по алфавиту из конкретной категории
    public function getBrand( $idcategory ) //return TObject или false в случаи отсутствия данных
    {
        return $this->select( 'SELECT b.id, b.name, c.count_offer 
                                    FROM category_brand c, brand b 
                                    WHERE c.idbrand=b.id AND c.idcategory='.$idcategory )->orderBy( 'name' );
    }

    public function getOfferById( $id ) //return TObject или false в случаи отсутствия данных
    {
        return $this->select( 'SELECT * FROM offer WHERE id='.$id )->current();
    }

    // Функция возвращает список офферов по id категории также если указан id бренда
    // то функция возвращает список офферов конкретного бренда в категории
    public function getOffer( $idcategory, $idbrand=0, $order='', $start=0, $limit=10 ) //return TMySQLSelect
    {
        $where = ($idbrand == 0) ? '' : ' AND idbrand='.$idbrand;
        $order = ($order == '') ? '' : ' ORDER BY '.$order;
        $limit = ($limit == 0) ? '' : ' LIMIT '.$start.', '.$limit;
        
        return $this->select( 'SELECT * FROM offer WHERE idcategory='.$idcategory.$where.$order.$limit );
    }
}

?>
