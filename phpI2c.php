<?php

class I2Ccomm{
    public function sendTest(){
        $address = ($address | 0x34) << 8 & 0;
        $i2c = fopen("/dev/i2c-0", "w+b");
        fseek($i2c, $address);
        $rtn = fread($i2c, 1);
        fclose($i2c);
        return $rtn;
    }
    public function detect_devices($bus){
        
    }
    public function list_busses(){
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
                            $adapters[$file] = trim(file_get_contents($dir.'/'.$file.'/name'));
                        }
                    }
                }
            }
        }
        return $adapters;
    }
}
