<?php

class I2Ccomm{
    public function sendTest($addr = 0x34){
        $rtn = "Failed\n";
        $address = ($addr | 0x01) << 8 & 1;
        if($i2c = fopen("/dev/i2c-0", "r+")){
            $int = 0;
            $length = 0;
            echo "file opened \n";
            while(($b = stream_get_line($i2c)) !== false){
                $int++;
                echo $b;
                $length+=strlen($b);
                if($length > $addr){
                    echo "should be here \n";
                    echo($b);
                }
                if($int == 100) return "Overflow";
                $rtn.="$b \n";
            }
            echo $length;
            // //fseek($i2c, $address);
            // $rtn = fread($i2c, 1);
            // fclose($i2c);
        }else{
            echo "failed to open file\n";
        };
        return $rtn;
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
}