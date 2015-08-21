<?php

/**
 * PicoCMS plugin to minify the HTML output.
 * This class is a modified version of the Wordpress-Minify class by http://fastwp.de/snippets/html-minify/
 *
 * @author Niklas Teich <niklas@millenworld.de>
 *
 * @link https://github.com/NiklasTeich/Pico-Minify Github Repository
 * @link http://fastwp.de/snippets/html-minify/ Original class reference
 */

/**
 * Class Pico_Minify
 */

class Pico_Minify
{

    /**
     * Main-flag to activate/deactivate the Pico Minify plugin.
     *
     * @var bool
     */

    protected $minify = true;

    /**
     * Flag to compress css.
     *
     * @var bool
     */

    protected $compress_css = true;

    /**
     * Flag to compress js.
     *
     * @var bool
     */

    protected $compress_js = true;

    /**
     * Flag to remove comments.
     *
     * @var bool
     */

    protected $remove_comments = true;

    /**
     * Hook helper-function to manipulate the Pico output.
     *
     * @param $output
     */

    public function after_render(&$output)
    {

        if ($this->minify) {

            $output = $this->minifyHTML($output);

        }

    }

    /**
     * Hook helper-function to load the (optional) custom config settings.
     *
     * @param $settings
     */

    public function config_loaded(&$settings)
    {

        $this->minify = isset($settings['pico_minify']['minify']) ? $settings['pico_minify']['minify'] : true;

        $this->compress_css = isset($settings['pico_minify']['compress_css']) ? $settings['pico_minify']['compress_css'] : true;

        $this->compress_js = isset($settings['pico_minify']['compress_js']) ? $settings['pico_minify']['compress_js'] : true;

        $this->remove_comments = isset($settings['pico_minify']['remove_comments']) ? $settings['pico_minify']['remove_comments'] : true;

    }

    /**
     * Main-function to minify the HTML content.
     *
     * @param string $html
     * @return string
     */

    public function minifyHTML($html) {

        $pattern = '/<(?<script>script).*?<\/script\s*>|<(?<style>style).*?<\/style\s*>|<!(?<comment>--).*?-->|<(?<tag>[\/\w.:-]*)(?:".*?"|\'.*?\'|[^\'">]+)*>|(?<text>((<[^!\/\w.:-])?[^<]*)+)|/si';

        preg_match_all($pattern, $html, $matches, PREG_SET_ORDER);

        $overriding = false;

        $raw_tag = false;

        $html = '';

        foreach ($matches as $token) {

            $strip = true;

            $tag = (isset($token['tag'])) ? strtolower($token['tag']) : null;

            $content = $token[0];

            if (is_null($tag)) {

                if (!empty($token['script'])) {

                    $strip = $this->compress_js;

                } else if (!empty($token['style'])) {

                    $strip = $this->compress_css;

                } else if ($this->remove_comments) {

                    if (!$overriding && $raw_tag != 'textarea') {

                        $content = preg_replace('/<!--(?!\s*(?:\[if [^\]]+]|<!|>))(?:(?!-->).)*-->/s', '', $content);

                    }

                }

            } else {

                if ($tag == 'pre' || $tag == 'textarea') {

                    $raw_tag = $tag;

                } else if ($tag == '/pre' || $tag == '/textarea') {

                    $raw_tag = false;

                } else {

                    if ($raw_tag || $overriding) {

                        $strip = false;

                    } else {

                        $strip = true;

                        $content = preg_replace('/(\s+)(\w++(?<!\baction|\balt|\bcontent|\bsrc)="")/', '$1', $content);

                        $content = str_replace(' />', '/>', $content);

                    }
                }
            }

            if ($strip) {

                $content = $this->removeWhiteSpace($content);

            }

            $html .= $content;

        }

        return $html;

    }

    /**
     * Helper-function to remove whitespaces from a given string.
     *
     * @param string $str
     * @return mixed
     */

    protected function removeWhiteSpace($str) {

        $str = str_replace("\t", ' ', $str);

        $str = str_replace("\n",  '', $str);

        $str = str_replace("\r",  '', $str);

        while (stristr($str, '  ')) {

            $str = str_replace('  ', '', $str);

        }

        return $str;

    }

}