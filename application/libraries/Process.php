<?php

/* An easy way to keep in track of external processes.
* Ever wanted to execute a process in php, but you still wanted to have somewhat controll of the process ? Well.. This is a way of doing it.
* @compability: Linux only. (Windows does not work).
* @author: Peec
*/

class Process
{
    private $pid;
    private $command;

    public function run($cl = false)
    {
        if ($cl != false) {
            $this->command = $cl;
            $command = 'nohup ' . $this->command . ' > /dev/null 2>&1 & echo $!';
            exec($command, $op);
            $this->pid = (int)$op[0];
        }
    }

    public function setPid($pid)
    {
        $this->pid = $pid;
    }

    public function getPid()
    {
        return $this->pid;
    }

    public function status($pid = false, $sleep = 0)
    {
        $pid = $pid ? $pid : $this->pid;
        usleep($sleep * 1000 * 1000);
        return is_dir( "/proc/$pid" ) || is_file("/proc/$pid") ? true : false;
    }

    public function start()
    {
        if ($this->command != '') $this->run();
        else return true;
        return true;
    }

    public function stop($pid = false, $signal = "", $sleep = 0)
    {
        $signal = !empty($signal) ? "-".$signal : "";
        $pid = $pid ? $pid : $this->pid;
        usleep($sleep * 1000 * 1000);
        $command = "kill $signal $pid";
        exec($command);
    }
}