<?php
    class r0mdauDb
    {
        private $directory;
        
        public function __construct($directory){
            $this->directory = $directory;
        }
        
        public function table($str){
            return new r0mdauTable($this->directory, $str);
        }
    }
    
    class r0mdauTable
    {
        private $file;
        private $data;
        private $find;
        
        public function __construct($dir, $file){
            $this->file = $dir.'/'.$file;
            $this->data = array();
            if(!file_exists($this->file)){
		try{
		    if(touch($this->file) === false)
			throw new Exception("Impossible de créer le fichier ".$this->file);
		}catch(Exception $e){
		    print $e->getMessage();
		}
            }else{
		try{
		    $handle = fopen($this->file, 'r');
		    if ($handle !== false){
			while (!feof($handle)){
			    $buffer = fgets($handle);
			    $this->data[] = json_decode($buffer);
			}fclose($handle);
		    }else throw new Exception("Impossible d'ouvrir le fichier ".$this->file);
		}catch(Exception $e){
		    print $e->getMessage();
		}              
            }            
        }
        
        public function insert($array){
            try{
                if(!isset($array['_rid'])) $array['_rid'] = md5(microtime(true));
                if(!fwrite(fopen($this->file, 'a'), json_encode($array)."\n"))
                    throw new Exception('Ecriture pas possible');
                else
                    $this->data[] = $this->arrayToObject($array);
                return true;
            }catch(Exception $e){
                print $e->getMessage();
            }
            return false;
        }
        
        public function find($array = NULL){            
            return $this->search($array);
        }
        
        public function find1($array){
            $result = $this->search($array);
            return isset($result[0]) ? $result[0] : $result;
        }
        
        public function update($array){
            $bool = false; $i = 0;
            foreach($this->data as $data){
		$tmp = key($array);
		if(isset($data->$tmp)){
		    if($data->$tmp == $array[$tmp]){
			$this->data[$i] = $array;
                        $bool = true;
		    }
		}
	        $i++;
	    }
	    if($bool){
		$this->eraseFile();
	    	foreach($this->data as $data){
		    $entry = (array) $data;
                    if(count($entry) > 1)
                        $this->insert($entry);
	    	}
	    }
	    return $bool;
        }
        
        public function delete($array){
	    $bool = false; $i = 0;
            foreach($this->data as $data){
		$tmp = key($array);
		if(isset($data->$tmp)){
		    if($data->$tmp == $array[$tmp]){
			unset($this->data[$i]);
			$this->data = array_values($this->data);
                        $bool = true;
		    }
		}
	        $i++;
	    }
	    if($bool){
		$this->eraseFile();
	    	foreach($this->data as $data){
		    $entry = (array) $data;
                    if(count($entry) > 1)
                        $this->insert($entry);
	    	}
	    }
	    return $bool;
        }
        
        public function truncate(){
	    $this->eraseFile();
            $this->data = array();
        }
       	
	private function eraseFile(){
	    try{
		if(file_put_contents($this->file, '') === false)
		throw new Exception("Le fichier n'a pas pu être vidé");
	    }catch(Exception $e){
                print $e->getMessage();
	    }
	}
	 
        private function search($array = NULL){
            if(empty($this->data)) return array();
            if(is_null($array)) return $this->data;            
            
            $result = array();
            foreach($this->data as $arr)
                if(isset($arr->{key($array)}) AND $arr->{key($array)} == $array[key($array)])
                    $result[] = $arr;
            
            return $result;
        }
        
        private function arrayToObject($str){
            return json_decode(json_encode($str));
        }
    }
