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
    }

    public function getColumns()
    {
        $columns = array(
          'id' => 'ID',
          'title' => 'Title',
          'author' => 'Author',
        );
        return $columns;
    }



    public function prepareItems()
    {
        $columns = $this->getColumns();
        $hidden = [];
        $sortable = $this->get_sortable_columns();
        $this->_column_headers = array($columns, $hidden, $sortable);


        $data = [];
        $posts = $this->postType->getPosts();
        foreach($posts as $post) {
            $data[] = [
                'id' => $post->getId(),
                'title' => $post->getTitle(),
                'author' => $post->getAuthor()->getDisplayName()
            ];
        }

        usort($data, array( &$this, 'usort_reorder' ));
        $this->items = $data;
    }

    public function column_default($item, $columnName)
    {
        return $item[$columnName];
    }


    public function get_sortable_columns()
    {
        $sortable_columns = array(
          'id'  => array('id',false),
          'title' => array('title',false),
          'author'   => array('author',false)
        );
        return $sortable_columns;
    }

    public function usort_reorder($a, $b)
    {
        // If no sort, default to title
        $orderby = (! empty($_GET['orderby'])) ? $_GET['orderby'] : 'id';
        // If no order, default to asc
        $order = (! empty($_GET['order'])) ? $_GET['order'] : 'asc';
        // Determine sort order
        $result = strcmp($a[$orderby], $b[$orderby]);
        // Send final sort direction to usort
        return ($order === 'asc') ? $result : -$result;
    }

    public function column_id($item)
    {

        $editLink = sprintf('<a href="?page=%s&action=%s&post=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['id']);
        if($this->editLinkCallback) {
            $editLink = call_user_func_array($this->editLinkCallback, [$item]);
        }

        $deleteLink = sprintf('<a href="?page=%s&action=%s&post=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id']);
        if($this->deleteLinkCallback) {
            $deleteLink = call_user_func_array($this->deleteLinkCallback, [$item]);
        }


        $actions = array(
            'edit'      => $editLink,
            'delete'    => $deleteLink,
        );

        return sprintf('%1$s %2$s', $item['id'], $this->row_actions($actions));
    }
}