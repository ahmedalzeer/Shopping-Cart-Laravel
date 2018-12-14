<?php

    function presentPrice($price)
    {
        return '$'.number_format($price);
    }
    function setActiveCategory($pass)
    {
        return request()->category == $pass ? 'active':'';
    }
