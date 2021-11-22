<?php
/*
 * @package marspress/short-code
 */

namespace MarsPress\ShortCode;

if( ! class_exists( 'Tag' ) )
{

    final class Tag
    {

        private string $tagName;

        private $callback;

        private array $defaultAttributes;

        private bool $tagExists;

        private bool $override;

        private string $adminNotices;

        public function __construct( string $_tagName, $_callback, array $_defaultAttributes = [], $_override = false )
        {

            $this->tagExists = shortcode_exists( $_tagName );
            $this->override = $_override;
            $this->tagName = $_tagName;
            $this->callback = $_callback;
            $this->defaultAttributes = $_defaultAttributes;

            if( ! $this->override && $this->tagExists ){

                $this->adminNotices = "The short code with the tag name of <strong><em>{$this->tagName}</em></strong> already exists. Please update your tag name to something unique.";
                add_action( 'admin_notices', function (){
                    $message = $this->output_admin_notice();
                    echo $message;
                }, 10, 0 );
                return;

            }

            add_shortcode( $this->tagName, [ $this, 'execute_callback' ] );

        }

        public function execute_callback( $_attributes, $_content, $_tag ): string{

            if( ! is_callable( $this->callback ) ){

                $this->adminNotices = "The short code with the tag name of <strong><em>{$this->tagName}</em></strong> has an invalid callback. Please update your tag's callback to a callable function.";
                return $this->output_admin_notice();

            }

            $return =  call_user_func_array( $this->callback, [ $this->parse_default_attributes( $_attributes ), $_content, $_tag ] );

            if( is_string( $return ) ){

                return $return;

            }

            return '';

        }

        private function parse_default_attributes( $_attributes ): object{

            return (object) shortcode_atts( $this->defaultAttributes, $_attributes );

        }

        private function output_admin_notice(): string{

            if( isset( $this->adminNotices ) && current_user_can( 'administrator' ) ){

                return "<div style='background: white; padding: 12px 20px; border-radius: 3px; border-left: 5px solid #dc3545;' class='notice notice-error is-dismissible'><p style='font-size: 16px;'>$this->adminNotices</p><small><em>This message is only visible to site admins</em></small></div>";

            }

            return '';

        }

    }

}