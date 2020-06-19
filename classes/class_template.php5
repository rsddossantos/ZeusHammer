<?php
/*
 * @author: Rodrigo Silveira 
 * @version: 1.0
 */

class Template{
    
    /*public function home($file){
		$this->html=$html;
        if(file_exists($file)){
            $html=file_get_contents($file);
			echo $html;
        }
    }*/
	public function home($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }	
	// nas páginas com código PHP não funcionou o "file_get_contents". Caracteres não suportados fazendo a não execução do código
	public function reenviacupom($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }	
	public function reenvianota($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }
   public function reprocessanotas($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }
	public function liberacupom($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }
	public function reimprime($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }
	public function geracontrole($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }
	public function consulta_status($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }
	public function notasemcest($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }
	public function consultapreco($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }
	public function consultavenda($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }
	public function geraarquivoxml($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }
	public function config_bd($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }
	public function fechamentocaixa($file){
        if(file_exists($file)){
            ob_start();
			require 'html/template.php5';
			require $file;
			$html= ob_get_contents();
			ob_end_clean();
			echo $html;
        }
    }
    public function consultanota($file){
        if(file_exists($file)){
            ob_start();
            require 'html/template.php5';
            require $file;
            $html= ob_get_contents();
            ob_end_clean();
            echo $html;
        }
    }
    public function fcpinvalido($file){
        if(file_exists($file)){
            ob_start();
            require 'html/template.php5';
            require $file;
            $html= ob_get_contents();
            ob_end_clean();
            echo $html;
        }
    }
    public function ncminexistente($file){
        if(file_exists($file)){
            ob_start();
            require 'html/template.php5';
            require $file;
            $html= ob_get_contents();
            ob_end_clean();
            echo $html;
        }
    }
    public function cpfinvalido($file){
        if(file_exists($file)){
            ob_start();
            require 'html/template.php5';
            require $file;
            $html= ob_get_contents();
            ob_end_clean();
            echo $html;
        }
    }
    public function totalbcdif($file){
        if(file_exists($file)){
            ob_start();
            require 'html/template.php5';
            require $file;
            $html= ob_get_contents();
            ob_end_clean();
            echo $html;
        }
    }
    public function totalfcpdif($file){
        if(file_exists($file)){
            ob_start();
            require 'html/template.php5';
            require $file;
            $html= ob_get_contents();
            ob_end_clean();
            echo $html;
        }
    }
    public function totalcbenef($file){
        if(file_exists($file)){
            ob_start();
            require 'html/template.php5';
            require $file;
            $html= ob_get_contents();
            ob_end_clean();
            echo $html;
        }
    }



}

