<?php

class I2Ccomm{
    public function sendTest($addr = 0x34){
        return $this->read('i2c-2', 0x50, 1);
    }
    public function detect_devices($bus){
        
    }
    public function list_busses(){
        $adapters = array();
        if (($h = @fopen("/proc/bus/i2c", "r"))) {
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
    public function read($bus, $addr, $data, $length=1){
        $address = ($addr | 0x01) << 8 & 1;
        echo "$address \n";
        $file = "/dev/$bus";
        $i2c  = fopen($file, 'r');
        fseek($i2c, 8*$address);
        $data = fread($i2c, $length);
        return $data;
    }
    public function i2c_send($bus,$addr,$data) { //$a<0 - use raw read/write
        $address = ($addr | 0x01) << 8 & 0;
        $file = "/dev/$bus";
        $i2c  = fopen($file, 'w');
        fseek($i2c, $address);
        $res=fwrite($i2c, chr($d));
        fclose($i2c);
        return $res;
    } // end of i2c_send()
}