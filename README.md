# MarsPress ShortCode
### Installation
Require the composer package `marspress/short-code` or run `composer install marspress/short-code`

###Usage
`new \MarsPress\ShortCode\Tag()` takes 4 parameters, 2 required and 2 optional.
* Tag Name (required)(string)
  * The tag name to use inside of WordPress. E.g. `output_user_info` would be used as such: `[output_user_info]`
  * If the tag is already in use, it will not register the tag and output an admin notice to admins regarding the duplicate tag name.
* Callback (required)(callable)
  * A callable method. This can be a Closure function or an array with a class method passed as such: `[ $this, '<method name>' ]`(non-static) OR `[ __CLASS__, '<method name>' ]`(static)
  * If the callback is not callable, a message will be outputted to admins in place of the short code output.
  * IMPORTANT: your callback will be passed 3 parameters:
    * Attributes (object)
      * This will be the parsed attributes, combining the default attributes and the ones defined in the short code.
    * Content (string)
      * This is content inside the short code tags. This is used when using opening and closing tags such as `[user_logged_in]User can see this![/user_logged_in]`
      * Generally you should also call `do_shortcode` on the content parameter before returning it in case there are more short codes within the content.
    * Tag Name (string)
      * The name of the tag being used.
* Default Attributes (optional)(array)
  * An associative array for the default short code attributes available in your callback after being merged with the user defined ones.
  * Defaults to an empty array.
* Override (boolean)
  * Whether to override existing tag names or not.
  * Defaults to false.