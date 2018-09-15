<?php
namespace App\View\Template;

class DefaultPaginationTemplate extends \Pagerfanta\View\Template\Template {

    static protected $defaultOptions = array(
        'prev_message'        => '',
        'next_message'        => '',
        'dots_message'        => '...',
        'active_suffix'       => '',
        'css_container_class' => 'pagination',
        'css_li_class'        => 'pagination-main__item',
        'css_link_class'      => 'pagination-main__link',
        'css_prev_class'      => 'pagination__item prev',
        'css_next_class'      => 'pagination__item next',
        'css_disabled_class'  => 'pagination__item--disabled',
        'css_dots_class'      => 'pagination-main__item--inside',
        'css_active_class'    => 'pagination-main__item--active',
        'rel_previous'        => 'prev',
        'rel_next'            => 'next'
    );

    public function container()
    {
        return sprintf('<div class="%s"><div class="pagination-main">%%pages%%</div></div>',
            $this->option('css_container_class')
        );
    }

    public function page($page)
    {
        $text = $page;

        return $this->pageWithText($page, $text);
    }

    public function pageWithText($page, $text)
    {
        $class = null;

        return $this->pageWithTextAndClass($page, $text, $class);
    }

    private function pageWithTextAndClass($page, $text, $itemClass, $rel = null)
    {
        $href = $this->generateRoute($page);
        $linkClass = $this->option('css_link_class');

        return $this->linkItem($itemClass, $linkClass, $href, $text, $rel);
    }

    public function previousDisabled()
    {
        $itemClass = $this->previousDisabledClass();
        $text = $this->option('prev_message');

        return $this->previousItem($itemClass, '#', $text);
    }

    private function previousDisabledClass()
    {
        return $this->option('css_prev_class').' '.$this->option('css_disabled_class');
    }

    public function previousEnabled($page)
    {
        $href = $this->generateRoute($page);
        $text = $this->option('prev_message');
        $class = $this->option('css_prev_class');
        $rel = $this->option('rel_previous');

        return $this->previousItem($class, $href, $text, $rel);
    }

    public function nextDisabled()
    {
        $itemClass = $this->nextDisabledClass();
        $text = $this->option('next_message');

        return $this->nextItem($itemClass, '#', $text);
    }

    private function nextDisabledClass()
    {
        return $this->option('css_next_class') . ' '.$this->option('css_disabled_class');
    }

    public function nextEnabled($page)
    {
        $href = $this->generateRoute($page);
        $text = $this->option('next_message');
        $class = $this->option('css_next_class');
        $rel = $this->option('rel_next');

        return $this->nextItem($class, $href, $text, $rel);
    }

    public function first()
    {
        return $this->page(1);
    }

    public function last($page)
    {
        return $this->page($page);
    }

    public function current($page)
    {
        $text = trim($page.' '.$this->option('active_suffix'));
        $liClass = $this->option('css_active_class');
        $linkClass = $this->option('css_link_class');

        return $this->linkItem($liClass, $linkClass, '#', $text);
    }

    public function separator()
    {
        $liClass = $this->option('css_dots_class');
        $linkClass = $this->option('css_link_class');
        $text = $this->option('dots_message');

        return $this->linkItem($liClass, $linkClass,'#', $text);
    }

    protected function nextItem($linkClass,  $href, $text, $rel = null)
    {
        $linkClass = $linkClass ? sprintf(' class="%s"', $linkClass) : '';

        $rel = $rel ? sprintf(' rel="%s"', $rel) : '';

        return sprintf('<a href="%s"%s %s>%s</a>', $href, $linkClass, $rel, $text);
    }

    protected function previousItem($linkClass,  $href, $text, $rel = null)
    {
        $linkClass = $linkClass ? sprintf(' class="%s"', $linkClass) : '';

        $rel = $rel ? sprintf(' rel="%s"', $rel) : '';

        return sprintf('<a href="%s"%s %s>%s</a>', $href, $linkClass, $rel, $text);
    }

    protected function linkItem($itemClass, $linkClass,  $href, $text, $rel = null)
    {
        $itemClass = $this->option('css_li_class') . ' ' . $itemClass;
        $linkClass = $this->option('css_link_class') . ' ' . $linkClass;

        $itemClass = $itemClass ? sprintf(' class="%s"', $itemClass) : '';
        $linkClass = $linkClass ? sprintf(' class="%s"', $linkClass) : '';

        $rel = $rel ? sprintf(' rel="%s"', $rel) : '';

        return sprintf('<div%s><a href="%s"%s %s>%s</a></div>', $itemClass, $href, $linkClass, $rel, $text);
    }
}