<?php

namespace Woof\ListTable;

use WP_List_Table;

class ListTable extends WP_List_Table
{

    protected $editLinkCallback;
    protected $deleteLinkCallback;

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

    public function setDeleteURLCallBack($callback)
    {
      $this->deleteLinkCallback = $callback;
      return $this;
    }
}
