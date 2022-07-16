<?php

namespace Fsuuaas\LaravelPlupload;

class Html
{
    /**
     * @var array
     */
    protected array $data = [];

    /**
     * @var string
     */
    protected string $id;

    /**
     * @var bool
     */
    protected bool $autoStart = false;

    /**
     * @var array
     */
    protected mixed $options = [];

    /**
     * Class constructor.
     *
     * @param string $id
     * @param string $url
     * @return void
     */
    public function __construct(string $id, string $url)
    {
        $this->id = $id;
        $this->options = config('plupload.global', []);
        $this->options['url'] = $url;
    }

    /**
     * Initialize the options.
     *
     * @return void
     * @throws Exception
     */
    protected function init(): void
    {
        if (empty($this->options['url'])) {
            throw new Exception('Missing URL option.');
        }

        $options = $this->options;
        $id = $this->id;
        $autoStart = $this->autoStart;

        // csrf token
        $options['multipart_params']['_token'] = csrf_token();
        $options['browse_button'] = 'uploader-'.$this->id.'-pickfiles';
        $options['container'] = 'uploader-'.$this->id.'-container';

        $this->data = array_merge($this->data, compact('options', 'id', 'autoStart'));
    }

    /**
     * Set uploader auto start.
     *
     * @param bool $bool
     * @return Html
     */
    public function setAutoStart(bool $bool): Html
    {
        $this->autoStart = (bool) $bool;

        return $this;
    }

    /**
     * Set uploader options.
     *
     * @see https://github.com/moxiecode/plupload/wiki/Options
     * @param  array $options
     * @return $this
     */
    public function setOptions(array $options): static
    {
        $this->options = array_merge($this->options, array_except($options, ['url']));

        return $this;
    }

    /**
     * Set uploader custom params.
     *
     * @param  array $params
     * @return Html
     */
    public function setCustomParams(array $params): Html
    {
        $this->data['params'] = $params;

        return $this;
    }

    /**
     * Render the upload handler buttons.
     *
     * @param string $view
     * @param array $data
     * @return \Illuminate\View\View
     * @throws Exception
     */
    public function render(string $view = 'plupload::uploader', array $data = []): \Illuminate\View\View
    {
        $this->init();

        return view($view, array_merge($this->data, $data));
    }

    /**
     * Get the string contents of the view.
     *
     * @return string
     * @throws Exception
     */
    public function __toString()
    {
        return (string) $this->render();
    }
}
