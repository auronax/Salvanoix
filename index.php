<!DOCTYPE html>
     <?php
        $host = "localhost";
        $user = "root";
        $pass = "";
        $banco = "fotos";
        $conecta = new mysqli($host,$user,$pass,$banco);
    if($conecta->connect_error){
        die("conexão falhou:".$conecta->connect_error."<br>");
    }
    mysqli_select_db($conecta,$banco) or die(mysqli_error());
        $msg = false;
        if(isset($_FILES['arquivo'])){
            $extensao = strtolower(substr($_FILES['arquivo']['name'],-4));// pega os quatro útimos caracteres do nome do arquivo
            $novo_nome = md5(time()).$extensao;//cripografa o nome do arquivo
            $diretorio = "imagens1/";//variavel que recebe o diretorio do arquivo
            
            move_uploaded_file($_FILES['arquivo']['tmp_name'], $diretorio.$novo_nome);//upload q move o arquivo com o novo nome
            
                        //Insere no banco de dados
            $sql_code = "INSERT INTO arquivo(codigo, imagens, data) VALUES (null, '$novo_nome', NOW())";
            $sql2 = mysqli_query($conecta,$sql_code);
          if($sql2){
              $msg = "Arquivo enviado com sucesso!";
            }
          else {
            $msg = "Falha ao enviar arquivo";
        }
     }  
     
   ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Cadastro imagem</title>
    </head>
 <body>
        <?php if($msg != false) echo"<p> $msg </p>"; ?>
     <form action="" method="POST" enctype="multipart/form-data" name="upload">
            <input type="file" required name="arquivo">
            <input type="submit" value="Salvar">
        </form>
        <br/>
        <br/>
     <?php
        $arquivos = glob('imagens1/*.*');//pegar todo tipo de imagens mC:\xampp\htdocs\PhpProject1\
        $qtd = 3;//número de imagens q serão exibidas na pagina
        $atual = (isset($_GET['pg'])) ? intval($_GET['pg']):1;//verifica se existe a página ? caso não haja 3 imagens na última página manter (1)
        $pginaarquivo = array_chunk($arquivos, $qtd);//faz uma divisão de 3 em 3 na váriavel $qtd 
        $contar = count($pginaarquivo);//conta as paginas
        $resultado = $pginaarquivo[$atual - 1];//resgata todos os arquivos,fazendo a paginação com array 
      ?>
       <?php
      foreach ($resultado as $valor){//exibição de imagens
          printf('<img src="%s" width="150" heigth="150" />',$valor);//exobe as imagens
        }
        echo'<hr></hr>';
        //Paginação
        for($i = 1;$i <= $contar; $i++){
            if($i === $atual){//
                printf('<a href="#"> (%s) </a>',$i,$i);
            }
            else{
                printf('<a href="?pg=%s"> () </a>',$i,$i);
            }
        }
       
       
       
       ?>
</body>
</html>
