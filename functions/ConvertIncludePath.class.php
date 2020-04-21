<?php

// convertIncludePath() - Converts the path used by include() if needed.
//                        (Not normally needed, but some installations demand this).

class ConvertIncludePath {
    private $convertEnabled;
    private $regex;
    private $targetPath;

    public function __construct($flag, $regex, $targetPath)
    {
        $this->convertEnabled = $flag;
        $this->regex = $regex;
        $this->targetPath = $targetPath;
    }

    function convertIncludePath($path)
    {
        if ($this->convertEnabled) {
            $path = preg_replace($this->regex, $this->targetPath, $path);
        }

        return $path;
    }
}

?>