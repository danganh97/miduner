<?php

namespace Midun\View;

class ViewCompiler
{
	/**
	 * Path of the view
	 *
	 * @var string
	 */
	protected $path;

	/**
	 * Content of html
	 *
	 * @var string
	 */
	protected $html;

	/**
	 * Initial constructor of view compiler
	 *
	 * @param string $path
	 *
	 * @method @setPath()
	 * @method @setHtml()
	 *
	 * @return void
	 */
	public function __construct(string $path)
	{
		$this->setPath($path);
		$this->setHtml();
	}

	/**
	 * Set path of view
	 *
	 * @param string $path
	 *
	 * @return void
	 */
	protected function setPath(string $path)
	{
		$this->path = $path;
	}

	/**
	 * Set html content view
	 *
	 * @property $this->html
	 * @property $this->path
	 *
	 * @return void
	 */
	protected function setHtml()
	{
		$this->html = file_get_contents($this->path);
	}

	/**
	 * Compile echo
	 *
	 * @return void
	 */
	public final function compileEcho()
	{
		$newViewData = [];

		foreach(explode(PHP_EOL, $this->getHtml()) as $line) {
			if(strpos($line, " //  ") === false) {
				$line = preg_replace('/\{\{\{(.+?)\}\}\}/', '<?php echo this->htmlentities($1); ?>', $line);

				$newViewData[] = preg_replace('/\{\{(.+?)\}\}/', '<?php echo $1; ?>', $line);
			} else {
				$newViewData[] = $line;
			}
		}

		$this->resetHtml(implode(PHP_EOL, $newViewData));
	}

	/**
	 * Compile php tag
	 *
	 * @param array $start_tags
	 * @param array $end_tags
	 *
	 * @return void
	 */
	public final function compilePhpTag(array $start_tags, array $end_tags)
	{
		foreach($start_tags as $tag) {
			$html = str_replace($tag, '<?php', $this->getHtml());
		}
		foreach($end_tags as $tag) {
			$html = str_replace($tag, '?>', $html);
		}

		$this->resetHtml($html);
	}

	/**
	 * Compile special tags
	 *
	 * @return void
	 */
	public final function compileSpecialTags()
	{
		$newViewData = [];

		foreach(explode(PHP_EOL, $this->getHtml()) as $line) {
			switch(true) {
				case strpos($line, '@if(') !== false:
				case strpos($line, '@foreach(') !== false:
					$line = str_replace('@', '', $line);
					$newViewData[] = "<?php {$line}: ?>";
					break;
				case strpos($line, '@endif') !== false:
				case strpos($line, '@endforeach') !== false:
					$line = str_replace('@', '', $line);
					$newViewData[] = "<?php {$line}; ?>";
					break;
				default:
					$newViewData[] = $line;
					break;
			}
		}

		$this->resetHtml(implode(PHP_EOL, $newViewData));
	}

	/**
	 * Compile comment
	 *
	 * @return void
	 */
	public final function compileComment()
	{
		$html = preg_replace('/\{\{--(.+?)(--\}\})?\n/', "<?php // $1 ?>\n", $this->getHtml());

		$html = preg_replace('/\{\{--((.|\s)*?)--\}\}/', "<?php /* $1 */ ?>\n", $html);

		$html = preg_replace('/\<\!\-\-(.+?)(\-\-\>)?\n/', "<?php // $1 ?>\n", $html);

		$html = preg_replace('/\<\!--((.|\s)*?)--\>/', "<?php /* $1 */ ?>\n", $html);

		$this->resetHtml($html);
	}

	/**
	 * Reset html
	 *
	 * @param string $html
	 *
	 * @return void
	 */
	protected final function resetHtml(string $html)
	{
		$this->html = $html;
	}

	/**
	 * Get html
	 *
	 * @return string
	 */
	public final function getHtml()
	{
		return $this->html;
	}
}
