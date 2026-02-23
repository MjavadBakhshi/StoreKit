<?php

namespace Domain\Store\ViewModels;

use Domain\Shared\ViewModels\ViewModel;
use Domain\Store\Models\Store;

class NewStoreViewModel extends ViewModel
{
    function __construct(protected readonly Store $store){}

    function store() :array
    {
        return $this->store->except(['id']);
    }
}