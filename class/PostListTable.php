<?php

namespace Woof\ListTable;

use Woof\Model\Wordpress\PostType;

class PostListTable extends ListTable
{


    protected $postTypeName;

    protected $postType;


    public function __construct($postTypeName)
    {
        parent::__construct();
        $this->postTypeName = $postTypeName;

        $this->postType = new PostType();
        $this->postType->loadByName($postTypeName);

        echo '<div style="border: solid 2px #F00">';
            echo '<div style="; background-color:#CCC">@'.__FILE__.' : '.__LINE__.'</div>';
            echo '<pre style="background-color: rgba(255,255,255, 0.8);">';
            print_r($this->postType);
            echo '</pre>';
        echo '</div>';
    }

    public function get_columns()
    {
        $columns = array(
          'booktitle' => 'Title',
          'author'    => 'Author',
          'isbn'      => 'ISBN'
        );
        return $columns;
    }

    public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);

        $example_data = array(
            array('ID' => 1,'booktitle' => 'Quarter Share', 'author' => 'Nathan Lowell',
                  'isbn' => '978-0982514542'),
            array('ID' => 2, 'booktitle' => '7th Son: Descent','author' => 'J. C. Hutchins',
                  'isbn' => '0312384378'),
            array('ID' => 3, 'booktitle' => 'Shadowmagic', 'author' => 'John Lenahan',
                  'isbn' => '978-1905548927'),
            array('ID' => 4, 'booktitle' => 'The Crown Conspiracy', 'author' => 'Michael J. Sullivan',
                  'isbn' => '978-0979621130'),
            array('ID' => 5, 'booktitle'     => 'Max Quick: The Pocket and the Pendant', 'author'    => 'Mark Jeffrey',
                  'isbn' => '978-0061988929'),
            array('ID' => 6, 'booktitle' => 'Jack Wakes Up: A Novel', 'author' => 'Seth Harwood',
                  'isbn' => '978-0307454355')
          );
        usort($example_data, array( &$this, 'usort_reorder' ));
        $this->items = $example_data;
    }
    public function column_default($item, $column_name)
    {
        switch ($column_name) {
          case 'booktitle':
          case 'author':
          case 'isbn':
            return $item[ $column_name ];
          default:
            return print_r($item, true) ; //Show the whole array for troubleshooting purposes
        }
    }


    public function get_sortable_columns()
    {
        $sortable_columns = array(
          'booktitle'  => array('booktitle',false),
          'author' => array('author',false),
          'isbn'   => array('isbn',false)
        );
        return $sortable_columns;
    }

    public function usort_reorder($a, $b)
    {
        // If no sort, default to title
        $orderby = (! empty($_GET['orderby'])) ? $_GET['orderby'] : 'booktitle';
        // If no order, default to asc
        $order = (! empty($_GET['order'])) ? $_GET['order'] : 'asc';
        // Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);
        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }

    public function column_booktitle($item)
    {
        $actions = array(
            'edit'      => sprintf('<a href="?page=%s&action=%s&book=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['ID']),
            'delete'    => sprintf('<a href="?page=%s&action=%s&book=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['ID']),
        );

        return sprintf('%1$s %2$s', $item['booktitle'], $this->row_actions($actions));
    }
}