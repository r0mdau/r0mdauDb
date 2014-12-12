<?php

class r0mdauDb
{
    private $directory;

    public function __construct($directory)
    {
        $this->directory = $directory;
    }

    public function collection($name)
    {
        return new r0mdauCollection($this->directory, $name);
    }
}

class r0mdauCollection
{
    private $file;
    private $data;

    public function __construct($dir, $file)
    {
        $this->file = $dir . '/' . $file;
        $this->data = array();
        if (!file_exists($this->file)) {
            try {
                if (touch($this->file) === false) {
                    throw new Exception("Impossible de créer le fichier " . $this->file);
                }
            } catch (Exception $e) {
                print $e->getMessage();
            }
        } else {
            try {
                $handle = fopen($this->file, 'r');
                if ($handle !== false) {
                    while (!feof($handle)) {
                        $buffer = fgets($handle);
                        $this->data[] = json_decode($buffer);
                    }
                    fclose($handle);
                } else {
                    throw new Exception("Impossible d'ouvrir le fichier " . $this->file);
                }
            } catch (Exception $e) {
                print $e->getMessage();
            }
        }
    }

    public function insert($document)
    {
        try {
            if (!isset($document['_rid'])) {
                $document['_rid'] = md5(microtime(true));
            }
            if (!fwrite(fopen($this->file, 'a'), json_encode($document) . "\n")) {
                throw new Exception('Ecriture pas possible');
            } else {
                $this->data[] = $this->arrayToObject($document);
            }
            return true;
        } catch (Exception $e) {
            print $e->getMessage();
        }
        return false;
    }

    public function find($document = NULL)
    {
        return $this->search($document);
    }

    public function find1($document)
    {
        $result = $this->search($document);
        return isset($result[0]) ? $result[0] : $result;
    }

    public function update($document, $values)
    {
        $bool = false;
        $i = 0;
        foreach ($this->data as $data) {
            $tmp = key($document);
            if (isset($data->$tmp)) {
                if ($data->$tmp == $document[$tmp]) {
                    $this->data[$i] = $this->arrayToObject($values);
                    $bool = true;
                }
            }
            $i++;
        }
        if ($bool) {
            $tmpDatas = $this->data;
            $this->truncate();
            foreach ($tmpDatas as $data) {
                $entry = (array)$data;
                if (count($entry) > 0) {
                    $this->insert($entry);
                }
            }
        }
        return $bool;
    }

    public function delete($document)
    {
        $bool = false;
        $i = 0;
        foreach ($this->data as $data) {
            $tmp = key($document);
            if (isset($data->$tmp)) {
                if ($data->$tmp == $document[$tmp]) {
                    unset($this->data[$i]);
                    $bool = true;
                }
            }
            $i++;
        }
        $this->data = array_values($this->data);

        if ($bool) {
            $this->eraseFile();
            foreach ($this->data as $data) {
                $entry = (array)$data;
                if (count($entry) > 1) {
                    $this->insert($entry);
                }
            }
        }
        return $bool;
    }

    public function truncate()
    {
        $this->data = array();
        return $this->eraseFile();
    }

    private function eraseFile()
    {
        try {
            if (file_put_contents($this->file, '') === false) {
                throw new Exception("Le fichier n'a pas pu être vidé");
            }else return true;
        } catch (Exception $e) {
            print $e->getMessage();
        }
    }

    private function search($document = NULL)
    {
        if (is_null($document)) return $this->data;
        if (empty($this->data)) return array();

        $result = array();
        foreach ($this->data as $arr) {
            if (isset($arr->{key($document)}) AND $arr->{key($document)} == $document[key($document)]) {
                $result[] = $arr;
            }
        }
        return $result;
    }

    private function arrayToObject($str)
    {
        return json_decode(json_encode($str));
    }
}
