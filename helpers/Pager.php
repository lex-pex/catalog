<?php

namespace Helpers;
use Db\Api\Model;

class Pager {

    private $model;
    private $limit = 0;
    private $lifo = true;
    private $item_with = '';

    public function __construct(Model $model, int $limit, bool $lifo = true, $item_with = '') {
        $this->model = $model;
        $this->limit = $limit;
        $this->lifo = $lifo;
        $this->item_with = $item_with;
    }

    public function feed($page) {
        if(!$page || !is_numeric($page) || $page < 0) return null;
        $total = $this->model::amount();
        $amountPages = $this->actualPages($this->limit, $total);
        if($page > $amountPages) return null;
        $skip = ($page - 1) * $this->limit;
        if($this->item_with)
            if(is_string($this->item_with))
                $rs = $this->model::chunkWith($this->item_with, $skip, $this->limit, $this->lifo);
            else
                $rs = $this->model::bulkChunkWith($this->item_with, $skip, $this->limit, $this->lifo);
        else
            $rs = $this->model::chunk($skip, $this->limit, $this->lifo);
        return (['pager' => $this->pager($amountPages, $page), 'result_set' => $rs]);
    }

    private function pager($amount, $active) {
        if($active == 0) $active = 1;
        $res = [];
        if($amount <= 1) return $res;  // Empty when is one page
        $p = 'page/';
        $res[] = ['label' => '<', 'urn' => $p . ($active - 1), 'class' => ($active == 1 ? 'disabled' : '')];
        if($amount < 6) {
            for ($i = 1; $i <= $amount; $i++)
                $res[] = ['label' => $i, 'urn' => $p . $i, 'class' => ($active == $i ? 'active' : '')];
        } else {
            if ($active < 4) {
                $res[] = ['label' => 1, 'urn' => $p . 1, 'class' => ($active == 1 ? 'active' : '')];
                $res[] = ['label' => 2, 'urn' => $p . 2, 'class' => ($active == 2 ? 'active' : '')];
                $res[] = ['label' => 3, 'urn' => $p . 3, 'class' => ($active == 3 ? 'active' : '')];
                $res[] = ['label' => '...', 'urn' => '', 'class' => 'disabled'];
                $res[] = ['label' => $amount, 'urn' => $p . $amount, 'class' => ($active == $amount ? 'active' : '')];
            } elseif (($amount - $active) < 3) {
                $res[] = ['label' => 1, 'urn' => $p . 1, 'class' => ($active == 1 ? 'active' : '')];
                $res[] = ['label' => '...', 'urn' => '', 'class' => 'disabled'];
                $res[] = ['label' => ($amount - 2), 'urn' => $p . ($amount - 2), 'class' => ($active == ($amount - 2) ? 'active' : '')];
                $res[] = ['label' => ($amount - 1), 'urn' => $p . ($amount - 1), 'class' => ($active == ($amount - 1) ? 'active' : '')];
                $res[] = ['label' => ($amount), 'urn' => $p . $amount, 'class' => ($active == $amount ? 'active' : '')];
            } else {
                $res[] = ['label' => 1, 'urn' => $p . 1, 'class' => ($active == 1 ? 'active' : '')];
                $res[] = ['label' => '...', 'urn' => '',  'class' => 'disabled'];
                $res[] = ['label' => $active, 'urn' => $p . $active, 'class' => 'active'];
                $res[] = ['label' => '...', 'urn' => '',  'class' => 'disabled'];
                $res[] = ['label' => $amount, 'urn' => $p . $amount, 'class' => ($active == $amount ? 'active' : '')];
            }
        }
        $res[] = ['label' => '>', 'urn' => $p . ($active + 1), 'class' => ($active == $amount ? 'disabled' : '')];
        return $res;
    }

    // Calculate Actual Amount of Pages
    private function actualPages($limit, $total) {
        $additionalPage = $total % $limit;
        $actual = ($total - ($additionalPage)) / $limit;
        if($additionalPage) $actual++;
        return $actual;
    }

    public function search(string $query, int $num) {
        $limit = $this->limit;
        $products =
            $this->model::where('status', 1)->where('name', 'like', '%' . $query . '%')
                ->orWhere('description', 'like', '%' . $query . '%')
                ->orderByDesc('id');
        $total = $products->count();
        $amountPages = $this->actualPages($limit, $total);
        if($num > $amountPages || $num < 1) return null;
        $skip = ($num - 1) * $limit;
        $rs = $products->skip($skip)->take($limit)->get();
        return (['pager' => $this->searchPager($query, $amountPages, $num), 'result_set' => $rs]);
    }

    private function searchPager($q, $amount, $active) {
        if($active == 0) $active = 1;
        $res = [];
        if($amount <= 1) return $res;  // Empty it is one page
        $p = 'shop/search?query=' . $q . '&page=';
        $res[] = ['label' => '<', 'urn' => $p . ($active - 1), 'class' => ($active == 1 ? 'disabled' : '')];
        if($amount < 6) {
            for ($i = 1; $i <= $amount; $i++)
                $res[] = ['label' => $i, 'urn' => $p . $i, 'class' => ($active == $i ? 'active' : '')];
        } else {
            if ($active < 4) {
                $res[] = ['label' => 1, 'urn' => $p . 1, 'class' => ($active == 1 ? 'active' : '')];
                $res[] = ['label' => 2, 'urn' => $p . 2, 'class' => ($active == 2 ? 'active' : '')];
                $res[] = ['label' => 3, 'urn' => $p . 3, 'class' => ($active == 3 ? 'active' : '')];
                $res[] = ['label' => '...', 'urn' => '', 'class' => 'disabled'];
                $res[] = ['label' => $amount, 'urn' => $p . $amount, 'class' => ($active == $amount ? 'active' : '')];
            } elseif (($amount - $active) < 3) {
                $res[] = ['label' => 1, 'urn' => $p . 1, 'class' => ($active == 1 ? 'active' : '')];
                $res[] = ['label' => '...', 'urn' => '', 'class' => 'disabled'];
                $res[] = ['label' => ($amount - 2), 'urn' => $p . ($amount - 2), 'class' => ($active == ($amount - 2) ? 'active' : '')];
                $res[] = ['label' => ($amount - 1), 'urn' => $p . ($amount - 1), 'class' => ($active == ($amount - 1) ? 'active' : '')];
                $res[] = ['label' => ($amount), 'urn' => $p . ($amount), 'class' => ($active == $amount ? 'active' : '')];
            } else {
                $res[] = ['label' => 1, 'urn' => $p . 1, 'class' => ($active == 1 ? 'active' : '')];
                $res[] = ['label' => '...', 'urn' => '',  'class' => 'disabled'];
                $res[] = ['label' => $active, 'urn' => $p . $active, 'class' => 'active'];
                $res[] = ['label' => '...', 'urn' => '',  'class' => 'disabled'];
                $res[] = ['label' => $amount, 'urn' => $p . $amount, 'class' => ($active == $amount ? 'active' : '')];
            }
        }
        $res[] = ['label' => '>', 'urn' => $p . ($active + 1), 'class' => ($active == $amount ? 'disabled' : '')];
        return $res;
    }
}