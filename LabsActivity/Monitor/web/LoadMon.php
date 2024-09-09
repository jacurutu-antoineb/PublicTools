<?php

function readLogs($filename, $num_lines = 50) 
{
    // Open the file for reading
    $file = fopen($filename, "r");

    if ($file === false) {
        return []; // Return an empty array if the file couldn't be opened
    }

    // Go to the end of the file
    fseek($file, 0, SEEK_END);

    $lines = [];
    $buffer = '';
    $char = '';
    $position = ftell($file);

    // Read file backward
    while ($position >= 0 && count($lines) < $num_lines) {
        fseek($file, $position--);

        $char = fgetc($file);

        // Add characters to the buffer until a newline or beginning of file is reached
        if ($char === "\n" && !empty($buffer)) {
            array_unshift($lines, strrev($buffer));
            $buffer = '';
        } else {
            $buffer .= $char;
        }

        // If the beginning of the file is reached, add the buffer to lines
        if ($position < 0 && !empty($buffer)) {
            array_unshift($lines, strrev($buffer));
        }
    }

    fclose($file);

    return $lines;
}

function LoadHead()
{
	$output = "<html><head>
		   <link rel=\"stylesheet\" href=\"monitor.css\">
		   </head>";
        return $output;
}

function LoadMonitor()
{
   $output = LoadHead();
   $output .= "<body>";
   // Get an array of files and directories in the specified path
   $files = scandir("/logs");

   // We expect modules to have app_function.output (out/err)
   // filter out . .. and stderr (get unique)
   $files = array_diff($files, array('.', '..'));   

   $pattern = '/^(\w+)_([\w]+)\.([\w]+)\.log$/';

   $services = [];

   foreach ($files as $file) 
   {
      if (preg_match($pattern, $file, $matches)) 
      {
         // Extract service, usecase, and outputtype
         $service = $matches[1];
         $usecase = $matches[2];
         $logtype = $matches[3];

	 $services[$service][$usecase] = 1;
        
      }
   }
   foreach (array_keys($services) as $service)
   {
       foreach (array_keys($services[$service]) as $usecase)
       {
          $output .= "<div class=\"service-box\">";
	  $output .= "$service:$usecase";
          $output .= " <div class=\"log-box large\">";
          $output .= " <pre>";
          #### File contents main log LOG ####
	  $lines = readLogs("/logs/$service" . "_" . "$usecase.stdout.log");
	  foreach($lines as $line)
	  {
	       if (($logPosition = strpos($line, "LOG:")) != false)
	       {
		  $output .= substr($line, $logPosition + 4)."\n";
	       }
	  }
          $output .= " </pre>";
          $output .= " </div>";

          $output .= " <div class=\"log-box-container\">";
          $output .= "  <div class=\"log-box small\">";
          $output .= "  <pre>";
	  foreach($lines as $line)
	  {
	       if (($logPosition = strpos($line, "ERR:")) != false)
	       {
		  $output .= substr($line, $logPosition)."\n";
	       }
	  }
          $output .= "  </pre>";
          $output .= "  </div>";
       
          $output .= "  <div class=\"log-box small\">";
          $output .= "  <pre>";
	  #### File contents stderr ####
	  $lines = readLogs("/logs/$service" . "_" . "$usecase.stderr.log");
	  foreach($lines as $line)
	  {
	     $output .= "CRIT: ".$line."\n";
	  }
          $output .= "  </pre>";
          $output .= "  </div>";
          $output .= " </div>";
          $output .= "</div><br>";
       }
   }
   $output .= "<script>
        setTimeout(function(){
            window.location.href = window.location.pathname;
	}, 10000);
              </script>";

   $output .= "</body></html>";
   return $output;
}

?>
