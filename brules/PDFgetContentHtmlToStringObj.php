<?php

class getContentHtmlToStringObj {

    public function getContentHtmlToString($url, $postdata){
        $postdata = http_build_query($postdata);

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata
            )
        );

        // $context  = stream_context_create($opts);
        // $result = file_get_contents($url, false, $context);
        // $result = "";

        //Sustituye al metodo file_get_contents ya que en versiones resientes no trabaja porque el servidor deshabilita
        //las variables allow_url_fopen, allow_url_include
        $result = "";
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,30);
        //set data to be posted
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
        $result = curl_exec($ch);
        curl_close($ch);

        return $result;
    }

}
?>
