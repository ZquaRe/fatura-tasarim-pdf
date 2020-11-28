<?php

require_once 'dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class

$options = new \Dompdf\Options();
$options->setIsRemoteEnabled(true);
$dompdf = new Dompdf($options);



$dompdf->loadHtml(file_get_contents('invoice_yeni.php'));


//$dompdf->set_base_path(__DIR__);
// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait'); //portrait landscape

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
//Düz çıktı veriyor
//$dompdf->stream();
//dompdf_out adında çıktı veriyor, false yerine true yapılırsa kullanıcının pcsine indiriyor sunucuya aktarmıyor.


//Sunucuda tutmak istemeyip kullanıcya tarayıcı üzerinden için bu şekilde
//$dompdf->stream("dompdf_out.pdf", array("Attachment" => false));
//Sunucuda tutmak istemeyip kullanıcının cihazına indirtmek için bu şekilde kullanınız
//$dompdf->stream("dompdf_out.pdf", array("Attachment" => true));

//Aşağıda bulunan işlemlerin hepsi pdfi şifrelemek ve şifrelenmiş pdfi açmak için vardır.
//Veriyi çıktı haline alıyor
$pdf = $dompdf->output();




//Anahtar
$key = 'bRuD5WYw5wd0rdHR9yLlM6wt2vteuiniQBqE70nAuhU=';

echo $name = md5(rand(1,1000000));

//Veriyi şifreliyor
$encrypted_code = my_encrypt($pdf, $key); //Encrypt the code.
file_put_contents($name.'.pdf', $encrypted_code); //Save the ecnypted code somewhere.


//Hangi verinni şifresini çözeceğini buluyor
$encrypted_code = file_get_contents($name.'.pdf'); //Get the encrypted code.
$decrypted_code = my_decrypt($encrypted_code, $key);//Decrypt the encrypted code.

//Şifresi çözülmüş veriyi sunucuya ekletiyor
file_put_contents('za.pdf', $decrypted_code); //Save the decrypted code somewhere.

//Pdf ismini yeniden düzenleyerek indirilmesini sağlıyor
pdf_rename('za.pdf', 'xd');



/*//pdfi açıyor
open_pdf('za.pdf');
//pdfi siliyor
unlink('za.pdf');
*/



function my_encrypt($data, $key) {
    $encryption_key = base64_decode($key);
    $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
    $encrypted = openssl_encrypt($data, 'aes-256-cbc', $encryption_key, 0, $iv);
    return base64_encode($encrypted . '::' . $iv);
}

function my_decrypt($data, $key) {
    $encryption_key = base64_decode($key);
    list($encrypted_data, $iv) = explode('::', base64_decode($data), 2);
    return openssl_decrypt($encrypted_data, 'aes-256-cbc', $encryption_key, 0, $iv);
}

function open_pdf($pdf_name)
{
    // Store the file name into variable 
  
// Header content type 
header('Content-type: application/pdf'); 
  
header('Content-Disposition: inline; filename="' . $pdf_name . '"'); 
  
header('Content-Transfer-Encoding: binary'); 
  
header('Accept-Ranges: bytes'); 
  
// Read the file 
readfile($pdf_name); 
}

function pdf_rename($original_name,$rename)
{  
    if(!empty($original_name) && !empty($rename)) {
    if(empty(explode('.', $original_name)[1])) $original_name =  $original_name.'.pdf'; else if(!empty(explode('.', $original_name)[1]) && explode('.', $original_name)[1] != 'pdf') $original_name =  explode('.', $original_name)[0].'.pdf';
    if(empty(explode('.', $rename)[1])) $rename =  $rename.'.pdf'; else if(!empty(explode('.', $rename)[1]) && explode('.', $rename)[1] != 'pdf') $original_name =  explode('.', $rename)[0].'.pdf';

        header('Content-type: application/pdf');

        header('Content-Disposition: attachment; filename="'.$rename.'"');

        readfile($original_name);
    }
}

?>