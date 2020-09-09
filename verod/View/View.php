<?php

namespace Midun\View;

class View
{
    /**
     * Constant synchronous view mode
     * 
     * @var string
     */
    const SYNC = 'sync';

    /**
     * Constant caching view mode
     * 
     * @var string
     */
    const CACHE = 'cache';

    /**
     * Master layout
     * 
     * @var string
     */
    protected $masterLayout;

    /**
     * Directory of views
     * 
     * @var string
     */
    protected $directory;

    /**
     * Directory of cache views
     * 
     * @var string
     */
    protected $cacheDirectory;

    /**
     * Current working section
     * 
     * @var string
     */
    protected $section;

    /**
     * List of sections
     * 
     * @var array
     */
    protected $sections = [];

    /**
     * List of php start tags
     * 
     * @var array
     */
    const START_TAGS = [
        "@php"
    ];

    /**
     * List of php end tags
     * 
     * @var array
     */
    const END_TAGS = [
        "@endphp"
    ];

    /**
     * Initial constructor of views
     * 
     * @param string $directory
     * @param string $cacheDirectory
     */
    public function __construct(string $directory, string $cacheDirectory)
    {
        $this->directory = $directory;
        $this->cacheDirectory = $cacheDirectory;
        ob_get_clean();
    }

    /**
     * Get directory
     * 
     * @return string
     */
    public function getDirectory()
    {
        return str_replace("/", DIRECTORY_SEPARATOR, $this->directory);
    }

    /**
     * Get caching directory
     * 
     * @return string
     */
    public function getCachingDirectory()
    {
        return str_replace("/", DIRECTORY_SEPARATOR, $this->cacheDirectory);
    }

    /**
     * Set master layout
     * 
     * @param string $masterCLayout
     * 
     * @return void
     */
    public function setMaster(string $masterLayout)
    {
        $this->masterLayout = $masterLayout;
    }

    /**
     * Make view caching
     * 
     * @param string $file
     * 
     * @return void
	 *
	 * @throws ViewException
     */
    public function makeCache(string $file)
    {
        $file = $this->getTrueFormat($file);

        $viewPath = $this->getDirectory() . DIRECTORY_SEPARATOR . $file;

        if (!file_exists($viewPath)) {
            throw new ViewException("View {$file} not found.");
        }

        $html = $this->compileHtml($viewPath);

        $this->makeCachingDirectory(
            $this->getCachingDirectory()
        );

        $tickets = explode(DIRECTORY_SEPARATOR, $file);

        $file = array_pop($tickets);

        $cacheDirectory = $this->getCachingDirectory();

        foreach ($tickets as $f) {
            if ($f !== '') {
                $cacheDirectory .= DIRECTORY_SEPARATOR . $f;
            }
            if (!is_dir($cacheDirectory)) {
                mkdir($cacheDirectory);
            }
        }
        if (false === is_dir($cacheDirectory)) {
            mkdir($cacheDirectory);
        }
        $filePath = $cacheDirectory . DIRECTORY_SEPARATOR . $file;

        $cacheFile = fopen($filePath, "w") or die("Unable to open file!");
        fwrite($cacheFile, $html);
        fclose($cacheFile);
    }

    /**
     * Make caching directory
     * 
     * @param string $directory
     * 
     * @return void
     */
    protected function makeCachingDirectory(string $directory)
    {
        if (false === is_dir($directory)) {
            $dir = '';
            foreach (explode(DIRECTORY_SEPARATOR, $directory) as $k => $f) {
                $dir .= $f . DIRECTORY_SEPARATOR;
                if (false === is_dir($dir)) {
                    mkdir($dir);
                }
            }
        }
    }

    /**
     * Compile html
     * 
     * @param string $file
     * 
     * @return string
     */
    protected function compileHtml(string $path)
    {
        $compiler = new ViewCompiler($path);

        $compiler->compilePhpTag(self::START_TAGS, self::END_TAGS);
        
        $compiler->compileEcho();

        $compiler->compileSpecialTags();

        $compiler->compileComment();

        return $compiler->getHtml();
    }

    /**
     * Get true format for view file
     * 
     * @param string $file
     * 
     * @return string
     */
    protected function getTrueFormat(string $file)
    {
        $file = strpos($file, '.php') !== false
            ? str_replace('.php', '', $file)
            : $file;

        return str_replace('.', DIRECTORY_SEPARATOR, $file) . '.php';
    }

    /**
     * Get content of view from cache file with implement arguments
     * 
     * @param string $file
     * @param array $arguments
     * 
     * @return string
	 * 
	 * @throws ViewException
     */
    public function getContentFromCacheWithArguments(string $file, array $arguments)
    {
        $file = str_replace('.', '/', $file) . '.php';

        $fileCachePath = $this->getCachingDirectory() . DIRECTORY_SEPARATOR . $file;

        if (!file_exists($fileCachePath)) {
            throw new ViewException("File $fileCachePath not found");
        }

        ob_start();

        extract($arguments, EXTR_PREFIX_SAME, "data");

        require $fileCachePath;

        return ob_get_clean();
    }

    /**
     * Set current section
     * 
     * @param string $section
     * 
     * @return void
     * 
     * @throws ViewException
     */
    public function setCurrentSection($section)
    {
        if ($this->existsSection()) {
            throw new ViewException("Missing tag `endsection` before start new section.<br>Current section `{$this->section}`");
        }
        $this->section = $section;
    }

    /**
     * Check exists current section
     * 
     * @return bool
     */
    private function existsSection()
    {
        return !is_null(
            $this->getCurrentSection()
        );
    }

    /**
     * Set section with data
     * 
     * @param string $section
     * @param mixed $section
     * 
     * @return void
     */
    public function setSectionWithData(string $section, $data)
    {
        $this->sections[$section] = $data;
    }

    /**
     * Get current section
     * 
     * @return string|null
     */
    private function getCurrentSection()
    {
        return $this->section;
    }

    /**
     * Set data for current section
     * 
     * @param mixed $data
     * 
     * @return void
     */
    public function setDataForSection($data)
    {
        $this->sections[$this->section] = htmlentities($data);
        $this->section = null;
    }

    /**
     * Get list of sections
     * 
     * @return array
     */
    protected function getSections()
    {
        return $this->sections;
    }

    /**
     * Get master layouts
     * 
     * @return string|null
     */
    protected function getMasterLayout()
    {
        return $this->masterLayout;
    }

    /**
     * Get needed section
     * 
     * @param string $section
     * @param string $instead
     * 
     * @return mixed|null
     */
    public function getNeedSection(string $section, string $instead)
    {
        return isset($this->sections[$section])
            ? \html_entity_decode($this->sections[$section])
            : $instead;
    }

    /**
     * Render view
     * 
     * @param string $file
     * @param array $arguments
     * 
     * @return mixed
	 *
	 * @throws ViewException
     */
    public function render(string $file, array $arguments = [], string $mode = '')
    {
        $mode = $mode ?: env('VIEW_MODE', View::SYNC);

        switch ($mode) {
            case View::CACHE:
            	$this->cachingRendering($file, $arguments);
            	break;
            case View::SYNC:
            	$this->syncRendering($file, $arguments);
            	break;
            default:
                $exception = new ViewException("Unknown view rendering mode `{$mode}`");
                return $this->render('exception', compact('exception'), View::SYNC);
        }

        exit(0);
    }

    /**
     * Synchronous rendering view
     * 
     * @param string $file
     * @param array $arguments
     * 
     * @return void
	 *
	 * @throws ViewException
	 */
    private function syncRendering(string $file, array $arguments)
    {
        $this->makeCache($file);

        ob_start();

        $content = $this->getContentFromCacheWithArguments(
            $file,
            $arguments
        );

        ob_get_clean();

        if ($this->getMasterLayout()) {

            $this->makeCache(
                $this->getMasterLayout()
            );

            $content = $this->getContentFromCacheWithArguments(
                $this->getMasterLayout(),
                $arguments
            );
        }

        eval(' ?>' . $content);
    }

    /**
     * Caching rendering view
     * 
     * @param string $file
     * @param array $arguments
     * 
     * @return void
	 *
	 * @throws ViewException
     */
    private function cachingRendering(string $file, array $arguments)
    {
        ob_start();

        $content = $this->getContentFromCacheWithArguments(
            $file,
            $arguments
        );

        ob_get_clean();

        if ($this->getMasterLayout()) {

            $content = $this->getContentFromCacheWithArguments(
                $this->getMasterLayout(),
                $arguments
            );
        }

        eval(' ?>' . $content);
    }
}
