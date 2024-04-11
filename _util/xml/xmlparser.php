<?

//***************************************************************
//** title  : A very basic and fast XML parser
//**
//** story  : Two objects are included here, a reader and a parser.
//**          All you need to do is instantiate the xmlParser
//**          object and let it do the job for you. nextToken()
//**          will return the next token, which can be a tag or
//**          the text between 2 tags. the isTag() will determine
//**          if this is a tag so you can do something smart with
//**          it. Lastly you can jumpTo($tagname) if you know the
//**          name of the tag you want to skip to. This can be
//**          "<p>" or something like "<div class='menuItems'>"
//**          Run the example and enjoy.
//**
//** author : Ioannis Cherouvim
//** e-mail : morales@hack.gr
//** date   : 2005-05-25
//*******************************************************


class fileReader {
    var $file;
    var $filename;
    var $step = 2048;
    var $bytesRead = 0;
    var $pointer = 0;
    var $buffer = "";
    var $bufferLength = 0;
    var $eof = false;

    function fileReader($filename) {
        $this->filename = $filename;
    }

    function open() {
        $this->file = fopen ($this->filename, "rb");
    }

    function close() {
        fclose ($this->file);
    }

    function getBytesRead() {
        return $this->bytesRead;
    }

    function eof() {
        return $this->eof;
    }

    function readByte() {
        if ($this->pointer == $this->bufferLength) {
            if (!feof($this->file)) {
                $this->buffer = fread ($this->file, $this->step);
                $this->bufferLength = strlen($this->buffer);
                $this->bytesRead += $this->bufferLength;
                $this->pointer = 0;
            } else {
                $this->eof = true;
                $this->close();
                return "";
            }
        }
        return $this->buffer[$this->pointer++];
    }
}


class xmlParser {

    function xmlParser($filename) {
        $this->reader = new fileReader($filename);
        $this->open();
        $this->ch0 = $this->reader->readByte();
        $this->isTag = ($this->ch0 == "<");
        $this->delim = ($this->isTag?">":"<");
        $this->buffer = "";
    }

    function open() {
        $this->reader->open();
    }

    function getBytesRead() {
        return $this->reader->getBytesRead();
    }

    function eof() {
        return $this->reader->eof();
    }

    function isTag() {
        return $this->isTag;
    }

    function feedBuffer() {
        $this->buffer .= $this->ch0;
        if (!$this->reader->eof()) {
            $this->ch0 = $this->reader->readByte();
        } else {
            $this->ch0 = "";
        }
    }

    function nextToken() {
        while ($this->ch0 != $this->delim && !$this->reader->eof()) {
            $this->feedBuffer();
        }
        if ($this->isTag) {
            $this->feedBuffer();
        }
        $this->isTag = ($this->ch0 == "<");
        $this->delim = ($this->isTag?">":"<");
        $temp = $this->buffer;

        $this->buffer = "";

        $this->feedBuffer();
        return $temp;
    }

    function jumpTo($tagname) {
        $token = "";
        while (strpos($token, "<$tagname") !== 0 && !$this->reader->eof()) {
            $token = $this->nextToken();
        }
        $this->tagFound = (strpos($token, "<$tagname") === 0);
    }

}
?>