<?php
    namespace Admin\Helper;

    class PaginatorHelper
    {
        // $count - total count of entities
        // $current - current page
        // $per_page - count of entities per page
        // $show_pages - counts of pages (numbers) showed in paginator
        // $show_first, $show_previous, $show_last, $show_next - elements appearance control

        public static function prepare($count, $current, $per_page = 20, $show_first = true, $show_previous = true, $show_last = true, $show_next = true, $show_pages = 7)
        {
            $total_count = ($count%$per_page == 0) ? $count/$per_page : ((int)($count/$per_page)) + 1;

            if($total_count == 1) {
                return null;
            }

            $result = array();
            $min_page = $current - 3;
            $min_page = ($min_page > 0) ? $min_page : 1;
            $for_min = $current - $min_page;
            $max_page = $show_pages - $for_min;
            $max_page = ($max_page > $total_count) ? $total_count : $max_page;

            // first button
            if($show_first) {
                $result[] = array(
                    'class' => ($current == 1) ? 'class="disabled"' : '',
                    'value' => ($current == 1) ? 'javascript:void(0)' : '?page=1',
                    'show' => '&laquo;',
                );
            }

            // previous button
            if($show_previous) {
                $result[] = array(
                    'class' => ($current == 1) ? 'class="disabled"' : '',
                    'value' => ($current == 1) ? 'javascript:void(0)' : '?page=' .($current - 1),
                    'show' => '&lsaquo;',
                );
            }

            // begin separator
            if($min_page > 1) {
                $result[] = array(
                    'class' => 'class="disabled"',
                    'value' => 'javascript:void(0)',
                    'show' => '...',
                );
            }

            // main paginator buttons
            for($i = $min_page; $i <= $max_page; $i++) {
                $result[] = array(
                    'class' => ($i == $current) ? 'class="active"' : '',
                    'value' => ($i == $current) ? 'javascript:void(0)' : '?page=' .$i,
                    'show' => $i,
                );
            }

            // end separator
            if(($max_page) != $total_count) {
                $result[] = array(
                    'class' => 'class="disabled"',
                    'value' => 'javascript:void(0)',
                    'show' => '...',
                );
            }

            // next button
            if($show_next) {
                $result[] = array(
                    'class' => ($current == $total_count) ? 'class="disabled"' : '',
                    'value' => ($current == $total_count) ? 'javascript:void(0)' : '?page=' .($current + 1),
                    'show' => '&rsaquo;',
                );
            }

            // last button
            if($show_last) {
                $result[] = array(
                    'class' => ($current == $total_count) ? 'class="disabled"' : '',
                    'value' => ($current == $total_count) ? 'javascript:void(0)' : '?page=' .$total_count,
                    'show' => '&raquo;',
                );
            }

            return $result;
        }
    }