<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RtlLayout extends Component
{
    public $isRtl;
    public $direction;
    public $bootstrapCss;
    public $textAlign;
    public $floatStart;
    public $floatEnd;

    public function __construct()
    {
        $this->isRtl = app()->getLocale() === 'ar';
        $this->direction = $this->isRtl ? 'rtl' : 'ltr';
        $this->bootstrapCss = $this->isRtl 
            ? asset('website/bootstrap-5.3.1-dist/css/bootstrap.rtl.min.css')
            : asset('website/bootstrap-5.3.1-dist/css/bootstrap.min.css');
        $this->textAlign = $this->isRtl ? 'text-end' : 'text-start';
        $this->floatStart = $this->isRtl ? 'float-end' : 'float-start';
        $this->floatEnd = $this->isRtl ? 'float-start' : 'float-end';
    }

    public function render()
    {
        return view('components.rtl-layout');
    }

    public function getMarginClass($position, $size = 3)
    {
        if ($position === 'start') {
            return $this->isRtl ? "me-{$size}" : "ms-{$size}";
        }
        return $this->isRtl ? "ms-{$size}" : "me-{$size}";
    }

    public function getPaddingClass($position, $size = 3)
    {
        if ($position === 'start') {
            return $this->isRtl ? "pe-{$size}" : "ps-{$size}";
        }
        return $this->isRtl ? "ps-{$size}" : "pe-{$size}";
    }
}