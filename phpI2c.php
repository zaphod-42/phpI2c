<?php

class I2Ccomm{
    public function detect($bus){
        
    }
    public function list_busses(){
        return $this->gather_busses();
    }
    public function gather_busses(){
        $adapters = array();
        if (($h = fopen("/proc/bus/i2c", "r"))) {
            print('/proc/bus/i2c exists, need to handle this case');
            fclose($h);
        }
        if ($h = fopen("/proc/mounts", "r")) {
            $sysfs = false;
            while(($b = fgets($h)) !== false){
                $parts = explode(' ',$b);
                if($parts[2] == 'sysfs'){
                    $sysfs = $parts[1];
                    break;
                }
            }
            fclose($h);
            if($sysfs){
                $dir = $sysfs.'/class/i2c-dev';
                if($files = scandir($dir)){
                    foreach($files as $file){
                        if($file[0] != '.'){
                            $adapters[$file] = file_get_contents($dir.'/'.$file);
                        }
                    }
                }
            }
        }
        return $adapters;
    }
}
