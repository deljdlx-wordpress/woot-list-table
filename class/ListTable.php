<?php

namespace Woof\ListTable;

use WP_List_Table;

class ListTable extends WP_List_Table
{

    protected $editLinkCallback;

    public function get_columns()
    {
        $columns = [];
        return $columns;
    }

    public function setEditURLCallBack($callback)
    {
      $this->editLinkCallback = $callback;
      return $this;
    }
}
