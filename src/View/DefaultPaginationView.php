<?php

namespace App\View;

use Pagerfanta\View\DefaultView;
use App\View\Template\DefaultPaginationTemplate;

class DefaultPaginationView extends DefaultView {
    protected function createDefaultTemplate() {
        return new DefaultPaginationTemplate();
    }

    protected function getDefaultProximity() {
        return 2;
    }

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'default-app-pagination';
    }
}