<?php

namespace App\Libraries {
    class ResourceQuota
    {
        public function __call($method, $args)
        {
            return null;
        }
    }
}

namespace {
    if (!class_exists('ResourceQuota')) {
        class ResourceQuota
        {
            public function __call($method, $args)
            {
                return null;
            }
        }
    }
}
