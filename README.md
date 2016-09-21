Dinkly Output Sanitizer Plugin v1.00
====================================

Sanitize view variables automatically before output rendering.


Installation
------------

  1. Move the `output_sanitizer` folder into your dinkly project's `plugins` folder

  2. Enable the plugin by copying the following code into `classes/core/custom/Dinkly.php`

  ```php
  public function filterVariable($key, $value)
  {
    $sanitizer = new OutputSanitizer();
    return $sanitizer->sanitizeOutput($key, $value);
  }
  ```

Usage
-----

By default, all view variables will be sanitized. You may find the built-in rules too basic for your needs, in which case you may easily apply overrides or additional functionality in `classes/core/custom/OutputSanitizer.php`.

You may also bypass the filter by passing in variable name exceptions:

For example, if you may wish to output unfiltered html. To do so, you would add the following code to `classes/core/custom/Dinkly.php`:

  ```php
  public function filterVariable($key, $value)
  {
    $sanitizer = new OutputSanitizer();
    $sanitizer->setExceptionSubstrings(array(0 => 'html_'));
    return $sanitizer->sanitizeOutput($key, $value);
  }
  ```

Based on this example, you would then append `html_` to any variables in your controller containing html that you would like to have bypass the output sanitization:

  ```php (in controlller)
  public function loadDisplayContent($parameters = array())
  {
    $content = new Content($this->db);
    $content->init($parameters['id']);
    $this->html_content = $content->getHtml();

    return true;
  }
  ```

  ```html (in view)
  <div class="html-content"><?php echo $html_content; ?></div>
  ```

License
-------

The Dinkly Output Sanitizer plugin is open-sourced software licensed under the MIT License.


Contact
-------

  - lewsid@lewsid.com (github.com/lewsid), andrew@bluehousegroup.com (github.com/andrewvt)
