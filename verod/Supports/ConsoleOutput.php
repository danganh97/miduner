<?php

namespace Midun\Supports;

use Midun\Container;

class ConsoleOutput
{
	/**
	 * Can colored for termimal
	 *
	 * @var bool
	 */
	protected $canColor = true;

	/**
	 * List of foreground colors
	 *
	 * @var array
	 */
	private $foreground_colors = array();

	/**
	 * List of background colors
	 *
	 * @var array
	 */
	private $background_colors = array();

	/**
	 * Init inital foreground and background colors
	 */
	public function __construct()
	{
		// Set up shell colors
		$this->foreground_colors['black'] = '0;30';
		$this->foreground_colors['dark_gray'] = '1;30';
		$this->foreground_colors['blue'] = '0;34';
		$this->foreground_colors['light_blue'] = '1;34';
		$this->foreground_colors['green'] = '0;32';
		$this->foreground_colors['light_green'] = '1;32';
		$this->foreground_colors['cyan'] = '0;36';
		$this->foreground_colors['light_cyan'] = '1;36';
		$this->foreground_colors['red'] = '0;31';
		$this->foreground_colors['light_red'] = '1;31';
		$this->foreground_colors['purple'] = '0;35';
		$this->foreground_colors['light_purple'] = '1;35';
		$this->foreground_colors['brown'] = '0;33';
		$this->foreground_colors['yellow'] = '1;33';
		$this->foreground_colors['light_gray'] = '0;37';
		$this->foreground_colors['white'] = '1;37';

		$this->background_colors['black'] = '40';
		$this->background_colors['red'] = '41';
		$this->background_colors['green'] = '42';
		$this->background_colors['yellow'] = '43';
		$this->background_colors['blue'] = '44';
		$this->background_colors['magenta'] = '45';
		$this->background_colors['cyan'] = '46';
		$this->background_colors['light_gray'] = '47';
	}

	/**
	 * Returns colored string
	 *
	 * @param string $string
	 * @param string $foreground_color
	 * @param string $background_color
	 *
	 * @return string
	 */
	public function getColoredString($string, $foreground_color = null, $background_color = null)
	{
		if(Container::getInstance()->isWindows()) {
			return $string;
		}

		$colored_string = "";

		// Check if given foreground color found
		if(isset($this->foreground_colors[$foreground_color])) {
			$colored_string .= "\033[" . $this->foreground_colors[$foreground_color] . "m";
		}
		// Check if given background color found
//		if(isset($this->background_colors[$background_color])) {
//			$colored_string .= "\033[" . $this->background_colors[$background_color] . "m";
//		}

		// Add string and end coloring
		$colored_string .= $string . "\033[0m";

		return $colored_string;
	}

	/**
	 * Returns all foreground color names
	 *
	 * @return array
	 */
	public function getForegroundColors()
	{
		return array_keys($this->foreground_colors);
	}

	/**
	 * Returns all background color names
	 *
	 * @return array
	 */
	public function getBackgroundColors()
	{
		return array_keys($this->background_colors);
	}

	/**
	 * Print a message with normal color
	 *
	 * @param string $msg
	 *
	 * @return void
	 */
	public function print($msg)
	{
		echo $this->getColoredString($msg, "dark_gray", "black") . "\n";
	}

	/**
	 * Print a message with error color
	 *
	 * @param string $error
	 *
	 * @return void
	 */
	public function printError($error)
	{
		echo $this->getColoredString($error, "red", "black") . "\n";
	}

	/**
	 * Print a message with warning color
	 *
	 * @param string $warning
	 *
	 * @return void
	 */
	public function printWarning($warning)
	{
		echo $this->getColoredString($warning, "black", "yellow") . "\n";
	}

	/**
	 * Print a message with highlights color
	 *
	 * @param string $highlights
	 *
	 * @return void
	 */
	public function printHighlights($highlights)
	{
		echo $this->getColoredString($highlights, "brown", "") . "\n";
	}

	/**
	 * Print a message with success color
	 *
	 * @param string $success
	 *
	 * @return void
	 */
	public function printSuccess($success)
	{
		echo $this->getColoredString($success, "green", "black") . "\n";
	}

	/**
	 * Print a message with success no background color
	 *
	 * @param string $success
	 *
	 * @return void
	 */
	public function printSuccessNoBackground($success)
	{
		echo $this->getColoredString($success, "green", "") . "\n";
	}
}
