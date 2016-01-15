<?php
$buf = "";
$value = "";
if(isset($_REQUEST["value"])){
    $value = $_REQUEST["value"];
    $buf = $value;
    $tategaki = new Tategaki($value);
    $buf = $tategaki->get();
}
class Tategaki {
    private $value;
    private $lines;
    private $maxcount = 0;
    private $linesize = 0;
    private $data = array();
    
    public function __construct($value){
        $this->value = trim($value);
        $this->plot();
    }

    public function get(){
        $buf = "";
        $sep = "\n"; // sepalator
        /*[x,y]
          にほんご
          ぶんしょうをかくと
          こうなる
          １文字目[0,0] => [ls - 1,0] = [ls - 1 - $j ,0];
          ２文字目[1,0] => [ls - 2,0] = [ls - 1 - $j ,0]
          ３文字目[2,0] => [ls - 3,0] = [ls - 1 - $j ,0]

          ２行目１文字[0,1] => [ls - 1,1] = [ls - 1 - $j , $i]
          ２行目２文字[1,1] => [ls - 2,1] = [ls - 1 - $j , $i]
         */
        $ls = $this->linesize;
        $mc = $this->maxcount;
        $d = $this->data;
        for($i=0;$i<$mc;$i++){
            for($j=0;$j<$ls;$j++){
                $c = "";
                $x = $ls - 1 - $j;
                $y = $i;
                if( isset( $d[$x][$y] ) ) {
                    $c = $d[$x][$y];
                }else{
                    $c = "　";
                }
                $buf .= $c;
            }
            $buf .= $sep;
        }
        return $buf;
    }
    
    private function plot(){
        $cr = array("\r\n","\r");
        $this->value = str_replace($cr,"\n",$this->value);
        $this->lines = explode("\n",$this->value);
        $this->linesize = count($this->lines);
        for( $i=0,$l=count($this->lines); $i<$l ; $i++ ){
            $line = $this->lines[$i];
            $count = mb_strlen($line);
            if($count > $this->maxcount){
                $this->maxcount = $count;
            }
            $chars = preg_split("//u", $line, -1, PREG_SPLIT_NO_EMPTY);
            for($j=0,$k=count($chars);$j<$k;$j++){
                $ch = $chars[$j];
                $ch = $this->normarizeChar($ch);
                if(!isset($this->data[$i])){
                    $this->data[$i] = array();
                }
                $this->data[$i][$j] = $ch;
            }
        }
    }

    private function normarizeChar($char){
        if(!preg_match('/[^ -~｡-ﾟ\x00-\x1f\t]+/u', $char)){
            return $char . " ";
        }else{
            return $char;
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>縦書き文字列生成サービス</title>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
</head>
    <body>
    <h1>縦書き文字列生成サービス(String Generator that string is witten vertical)</h1>
    <p>縦書きの文字列を作成できます。</p>
    <p>You can make string that is writen vertical.</p>
    <form method="post">
    <textarea style="width:100%;height:100px" name="value"><?php echo $value; ?></textarea>
    <input type="submit" value="generate">
    </form>
    <h2>結果(output)</h2>
<pre style="width:100%;">
<?php echo $buf;?>
</pre>
    </body>
</html>
