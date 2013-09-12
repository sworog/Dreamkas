<?php

namespace Lighthouse\CoreBundle\Samba;

class SambaStreamWrapper extends Samba
{
    # variables

    public $stream;
    public $url;
    public $parsed_url = array();
    public $mode;
    public $tmpfile;
    public $need_flush = false;
    public $dir = array();
    public $dir_index = -1;


    # directories

    public function dir_opendir($url, $options)
    {
        if ($d = $this->getdircache($url)) {
            $this->dir = $d;
            $this->dir_index = 0;

            return true;
        }
        $pu = Samba::ParseUrl($url);
        switch ($pu['type']) {
            case 'host':
                if ($o = Samba::look($pu)) {
                    $this->dir = $o['disk'];
                    $this->dir_index = 0;
                } else {
                    trigger_error("dir_opendir(): list failed for host '{$pu['host']}'", E_USER_WARNING);
                }
                break;
            case 'share':
            case 'path':
                if ($o = Samba::execute('dir "' . $pu['path'] . '\*"', $pu)) {
                    $this->dir = array_keys($o['info']);
                    $this->dir_index = 0;
                    $this->adddircache($url, $this->dir);
                    foreach ($o['info'] as $name => $info) {
                        Samba::addstatcache($url . '/' . urlencode($name), $info);
                    }
                } else {
                    $this->dir = array();
                    $this->dir_index = 0;
                }
                break;
            default:
                trigger_error('dir_opendir(): error in URL', E_USER_ERROR);
        }

        return true;
    }

    public function dir_readdir()
    {
        return ($this->dir_index < count($this->dir)) ? $this->dir[$this->dir_index++] : false;
    }

    public function dir_rewinddir()
    {
        $this->dir_index = 0;
    }

    public function dir_closedir()
    {
        $this->dir = array();
        $this->dir_index = -1;

        return true;
    }


    # cache

    public function adddircache($url, $content)
    {
        return self::$smb_cache['dir'][$url] = $content;
    }

    public function getdircache($url)
    {
        return isset (self::$smb_cache['dir'][$url]) ? self::$smb_cache['dir'][$url] : false;
    }

    public function cleardircache($url = '')
    {
        if ($url == '') {
            self::$smb_cache['dir'] = array();
        } else {
            unset (self::$smb_cache['dir'][$url]);
        }
    }


    # streams

    public function stream_open($url, $mode, $options, $opened_path)
    {
        $this->url = $url;
        $this->mode = $mode;
        $this->parsed_url = $pu = Samba::ParseUrl($url);
        if ($pu['type'] <> 'path') {
            trigger_error('stream_open(): error in URL', E_USER_ERROR);
        }
        switch ($mode) {
            case 'r':
            case 'r+':
            case 'rb':
            case 'a':
            case 'a+':
                $this->tmpfile = tempnam('/tmp', 'smb.down.');
                Samba::execute('get "' . $pu['path'] . '" "' . $this->tmpfile . '"', $pu);
                break;
            case 'w':
            case 'w+':
            case 'wb':
            case 'x':
            case 'x+':
                $this->cleardircache();
                $this->tmpfile = tempnam('/tmp', 'smb.up.');
        }
        $this->stream = fopen($this->tmpfile, $mode);

        return true;
    }

    public function stream_close()
    {
        return fclose($this->stream);
    }

    public function stream_read($count)
    {
        return fread($this->stream, $count);
    }

    public function stream_write($data)
    {
        $this->need_flush = true;

        return fwrite($this->stream, $data);
    }

    public function stream_eof()
    {
        return feof($this->stream);
    }

    public function stream_tell()
    {
        return ftell($this->stream);
    }

    public function stream_seek($offset, $whence = null)
    {
        return fseek($this->stream, $offset, $whence);
    }

    public function stream_flush()
    {
        if ($this->mode <> 'r' && $this->need_flush) {
            Samba::clearstatcache($this->url);
            Samba::execute('put "' . $this->tmpfile . '" "' . $this->parsed_url['path'] . '"', $this->parsed_url);
            $this->need_flush = false;
        }
    }

    public function stream_stat()
    {
        return Samba::urlStat($this->url);
    }

    public function __destruct()
    {
        if ($this->tmpfile <> '') {
            if ($this->need_flush) {
                $this->stream_flush();
            }
            unlink($this->tmpfile);

        }
    }
}
