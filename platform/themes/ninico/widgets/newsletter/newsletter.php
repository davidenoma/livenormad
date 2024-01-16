<?php

use Botble\Widget\AbstractWidget;

class NewsletterWidget extends AbstractWidget
{
    public function __construct()
    {
        parent::__construct([
            'name' => __('Newsletter form'),
            'description' => __('Display Newsletter form on sidebar'),
            'title' => __('Get Newsletter'),
            'subtitle' => __('Get on the list and get 10% off your first order!'),
        ]);
    }
}
