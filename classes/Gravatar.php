<?php

/**
 *  Class Gravatar
 *
 * From Gravatar Help:
 *        "A gravatar is a dynamic image resource that is requested from our server. The request
 *        URL is presented here, broken into its segments."
 * Source:
 *    http://site.gravatar.com/site/implement
 *
 * Usage:
 * <code>
 *        $email = "youremail@yourhost.com";
 *        $default = "http://www.yourhost.com/default_image.jpg";    // Optional
 *        $gravatar = new Gravatar($email, $default);
 *        $gravatar->size = 80;
 *        $gravatar->rating = "G";
 *        $gravatar->border = "FF0000";
 *
 *        echo $gravatar; // Or echo $gravatar->toHTML();
 * </code>
 *
 *    Class Page: http://www.phpclasses.org/browse/package/4227.html
 *
 * @property string default
 * @property string extra
 * @property int    size
 * @property  array email
 * @author  Lucas Araújo <araujo.lucas@gmail.com>
 * @version 1.0
 * @package Gravatar
 */
class Gravatar {
	/**
	 *    Gravatar's url
	 */
	const GRAVATAR_URL = "http://www.gravatar.com/avatar.php";

	/**
	 *    Ratings available
	 */
	private $GRAVATAR_RATING = [ "G", "PG", "R", "X" ];

	/**
	 *    Query string. key/value
	 */
	protected $properties = [
		"gravatar_id" => NULL,
		"default"     => NULL,
		"size"        => 40,
		"rating"      => "G"
	];

	/**
	 *    Extra attributes to the IMG tag like ALT, CLASS, STYLE...
	 */
	protected $_extra = "";


	/**
	 * @param $email
	 *
	 * @return bool
	 */
	public function setEmail( $email ) {
		if ( $this->isValidEmail( $email ) ) {
			$this->properties['gravatar_id'] = md5( strtolower( trim( $email ) ) );
			return true;
		}
		return false;
	}

	/**
	 * @param $default
	 *
	 * @return bool
	 */
	public function setDefault( $default ) {
		$this->properties['default'] = $default;
		return true;
	}

	/**
	 * @param $rating
	 *
	 * @return bool
	 */
	public function setRating( $rating ) {
		if ( in_array( $rating, $this->GRAVATAR_RATING ) ) {
			$this->properties['rating'] = $rating;
			return true;
		}
		return false;
	}

	/**
	 * @param $size
	 *
	 * @return bool
	 */
	public function setSize( $size ) {
		$size = (int) $size;
		if ( $size <= 0 )
			$size = NULL; // Use the default size
		$this->properties['size'] = $size;
		return true;
	}

	/**
	 * @param $extra
	 *
	 * @return bool
	 */
	public function setExtra( $extra ) {
		$this->_extra = $extra;
		return true;
	}


	/**
	 * @param $email
	 *
	 * @return int
	 */
	public function isValidEmail( $email ) {
		// Source: http://www.zend.com/zend/spotlight/ev12apr.php
		return preg_match( "/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i", $email );
	}

	/**
	 *    Object property overloading
	 *
	 * @param $var
	 *
	 * @return
	 */
	public function __get( $var ) {
		return @$this->properties[$var];
	}

	/**
	 *    Object property overloading
	 *
	 * @param $var
	 * @param $value
	 *
	 * @return bool
	 */
	public function __set( $var, $value ) {
		switch ( $var ) {
			case "email":
				return $this->setEmail( $value );
			case "rating":
				return $this->setRating( $value );
			case "default":
				return $this->setDefault( $value );
			case "size":
				return $this->setSize( $value );
			case "extra":
				return $this->setExtra( $value );
		}
		return $this->properties[$var] = $value;
	}

	/**
	 *    Object property overloading
	 *
	 * @param $var
	 *
	 * @return bool
	 */
	public function __isset( $var ) {
		return isset( $this->properties[$var] );
	}

	/**
	 *    Object property overloading
	 *
	 * @param $var
	 *
	 * @return bool
	 */
	public function __unset( $var ) {
		return NULL == @$this->properties[$var];
	}

	/**
	 *    Get source
	 */
	public function getSrc() {
		$url   = self::GRAVATAR_URL . "?";
		$first = true;
		foreach ( $this->properties as $key => $value ) {
			if ( isset( $value ) ) {
				if ( ! $first )
					$url .= "&";
				$url .= $key . "=" . $value ;
				$first = false;
			}
		}
		return $url;
	}

	/**
	 *    toHTML
	 *
	 * @param null $src
	 *
	 * @return string
	 */
	public function toHTML($src = NULL) {
		$url = !empty($src)?$src:$this->getSrc();
		return "<img src='". $url ."'"
		. ( ! isset( $this->size ) ? '' : " width='{$this->size}px' height='{$this->size}px' ")
		. $this->_extra
		. " />";
	}

	/**
	 *    toString
	 */
	public function __toString() {
		return $this->toHTML();
	}
}

?> 