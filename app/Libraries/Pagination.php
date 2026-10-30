<?php

/**
 * Minimal CI3-compatible Pagination library for legacy controllers running
 * under CI4 (loaded through LoaderBridge::library('pagination')).
 *
 * Supports the config keys used in this codebase: base_url, total_rows,
 * per_page, uri_segment, num_links, and the *_link / *_tag_open / *_tag_close
 * markup options. Offsets are passed as a URI segment, as in CI3's default mode.
 */
class Pagination
{
    protected string $base_url        = '';
    protected int $total_rows         = 0;
    protected int $per_page           = 10;
    protected int $num_links          = 2;
    protected int $uri_segment        = 0;
    protected string $full_tag_open   = '';
    protected string $full_tag_close  = '';
    protected string $first_link      = '&lsaquo; First';
    protected string $first_tag_open  = '';
    protected string $first_tag_close = '';
    protected string $last_link       = 'Last &rsaquo;';
    protected string $last_tag_open   = '';
    protected string $last_tag_close  = '';
    protected string $next_link       = '&gt;';
    protected string $next_tag_open   = '';
    protected string $next_tag_close  = '';
    protected string $prev_link       = '&lt;';
    protected string $prev_tag_open   = '';
    protected string $prev_tag_close  = '';
    protected string $cur_tag_open    = '<strong>';
    protected string $cur_tag_close   = '</strong>';
    protected string $num_tag_open    = '';
    protected string $num_tag_close   = '';

    public function __construct($params = [])
    {
        if (is_array($params) && !empty($params)) {
            $this->initialize($params);
        }
    }

    public function initialize(array $params = []): self
    {
        foreach ($params as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = is_int($this->$key) ? (int) $value : (string) $value;
            }
        }
        return $this;
    }

    public function create_links(): string
    {
        if ($this->total_rows <= 0 || $this->per_page <= 0) {
            return '';
        }

        $num_pages = (int) ceil($this->total_rows / $this->per_page);
        if ($num_pages <= 1) {
            return '';
        }

        $base   = rtrim($this->base_url, '/');
        $offset = $this->currentOffset($base);
        $offset = max(0, min($offset, ($num_pages - 1) * $this->per_page));
        $cur    = (int) floor($offset / $this->per_page) + 1;

        $start = max(1, $cur - $this->num_links);
        $end   = min($num_pages, $cur + $this->num_links);
        $url   = function (int $page) use ($base) {
            return $base . '/' . (($page - 1) * $this->per_page);
        };

        $out = '';
        if ($this->first_link !== '' && $cur > $this->num_links + 1) {
            $out .= $this->first_tag_open . '<a href="' . $base . '">' . $this->first_link . '</a>' . $this->first_tag_close;
        }
        if ($this->prev_link !== '' && $cur > 1) {
            $out .= $this->prev_tag_open . '<a href="' . $url($cur - 1) . '">' . $this->prev_link . '</a>' . $this->prev_tag_close;
        }
        for ($page = $start; $page <= $end; $page++) {
            if ($page === $cur) {
                $out .= $this->cur_tag_open . $page . $this->cur_tag_close;
            } else {
                $out .= $this->num_tag_open . '<a href="' . $url($page) . '">' . $page . '</a>' . $this->num_tag_close;
            }
        }
        if ($this->next_link !== '' && $cur < $num_pages) {
            $out .= $this->next_tag_open . '<a href="' . $url($cur + 1) . '">' . $this->next_link . '</a>' . $this->next_tag_close;
        }
        if ($this->last_link !== '' && $cur + $this->num_links < $num_pages) {
            $out .= $this->last_tag_open . '<a href="' . $url($num_pages) . '">' . $this->last_link . '</a>' . $this->last_tag_close;
        }

        return $this->full_tag_open . $out . $this->full_tag_close;
    }

    /**
     * Offset is the URI segment right after base_url (CI3 auto-detect), or
     * the explicitly configured uri_segment.
     */
    protected function currentOffset(string $base): int
    {
        $segments = service('uri')->getSegments();

        if ($this->uri_segment > 0) {
            return (int) ($segments[$this->uri_segment - 1] ?? 0);
        }

        $base_path = trim((string) parse_url($base, PHP_URL_PATH), '/');
        $site_path = trim((string) parse_url(site_url(), PHP_URL_PATH), '/');
        if ($site_path !== '' && strpos($base_path, $site_path) === 0) {
            $base_path = trim(substr($base_path, strlen($site_path)), '/');
        }
        $base_segments = $base_path === '' ? 0 : count(explode('/', $base_path));

        return (int) ($segments[$base_segments] ?? 0);
    }
}
