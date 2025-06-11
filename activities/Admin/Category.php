<?php

namespace Activities\Admin;


class Category{
    public function index()
    {
        echo 'Index';
    }

    public function create()
    {
        echo 'create';
    }
    public function edit($id)
    {
        echo "edit " . $id;
    }
}