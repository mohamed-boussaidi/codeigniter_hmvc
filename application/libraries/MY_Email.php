<?php

class MY_Email extends CI_Email
{
    public function __construct(array $config = array())
    {
        parent::__construct($config);
    }

    /**
     * Assign file attachments
     *
     * @param    string $file Can be local path, URL or buffered content
     * @param string $name
     * @return CI_Email
     * @internal param string $disposition = 'attachment'
     * @internal param string $newname = NULL
     * @internal param string $mime = ''
     */
    public function pdf_attach($file, $name = "attachment") {
        $this->_attachments[] = array(
            'name'		=> array($name.".pdf", $name.".pdf"),
            'disposition'	=> "attachment",
            'type'		=> "application/pdf",
            'content'	=> chunk_split(base64_encode($file))
        );

        return $this;
    }
}