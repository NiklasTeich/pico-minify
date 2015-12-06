Pico Minify
===========

Pico Minify is a simple [PicoCMS](http://picocms.org/) (0.x) plugin to minify your HTML output.
If you need something to support the current Pico version (1.x), check out the other repository!

## Usage

Copy the `pico_minify.php` file to the Pico plugins folder `plugins`. All additional configurations are optional.

## Configuration

Modify your `config.php` to configure the minify plugin.
The following example shows all possible configurations.

```php
$config['pico_minify'] = array(
    'minify' => true,
    'compress_css' => true,
    'compress_js' => true,
    'remove_comments' => true
);
```

### Single configurations
#### Activation

To activate or deactive the plugin, use the `minify` key. `(default: true)`

```php
$config['pico_minify']['minify'] = true;
```

#### Compress CSS

To activate the compression of inline or embedded CSS styles, use the `compress_css` key.  `(default: true)`

```php
$config['pico_minify']['compress_css'] = true;
```

#### Compress JS

To activate the compression of embedded JavaScript, use the `compress_js` key.  `(default: true)`

```php
$config['pico_minify']['compress_js'] = true;
```

#### Remove comments

To remove your HTML comments like `<!-- my example comment -->`, use the `remove_comments` key.  `(default: true)`

```php
$config['pico_minify']['remove_comments'] = true;
```

## Copyright

This plugin class is a modified version of the open source Wordpress-Minify class by http://fastwp.de/snippets/html-minify/
Plugin and modification by Niklas Teich.
